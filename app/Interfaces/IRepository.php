<?php

interface IRepository {
    public function getAll(): array;
    public function get(int $id);
    public function delete(int $id): bool;
    public function create($model);
    public function update($model): bool;
    public function findBy(array $criteria): array;
}

abstract class BaseRepository implements IRepository {
    protected $pdo;
    protected $table_name;
    protected $table_columns = [];
    protected $modelClassName;

    public function __construct(PDO $pdo, string $modelClassName) {
        $this->pdo = $pdo;
        $this->modelClassName = $modelClassName;
        $className = strtolower((new \ReflectionClass($modelClassName))->getShortName());
        $this->table_name = strtoupper($className);
        $vars = get_class_vars($modelClassName);
        $this->table_columns = [];
        foreach ($vars as $key => $value) {
            if (strpos($key, $className . '_') === 0) {
                $this->table_columns[] = $key;
            }
        }
    }

    public function getAll(): array {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->modelClassName);
    }

    public function get(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name . " WHERE " . $this->table_name . "_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject($this->modelClassName);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table_name . " WHERE " . $this->table_name . "_id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function create($model) {
        try {
            $this->pdo->beginTransaction();
            
            $modelVars = get_object_vars($model);
            unset($modelVars[strtolower($this->table_name) . '_id']); // Remove primary key
            
            $fields = array_keys($modelVars);
            $placeholders = array_map(fn($field) => ':' . $field, $fields);
            
            // First INSERT
            $sql = "INSERT INTO " . $this->table_name . " (" . implode(',', $fields) . ") 
                    VALUES (" . implode(',', $placeholders) . ")";
            
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt->execute($modelVars)) {
                $this->pdo->rollBack();
                return null;
            }
            
            // Immediately get the inserted ID in the same transaction
            $stmt = $this->pdo->query("SELECT LAST_INSERT_ID()");
            $id = $stmt->fetchColumn();
            
            // Get the complete record
            $result = $this->get($id);
            
            $this->pdo->commit();
            return $result;
            
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function update($model): bool {
        try {
            $modelVars = get_object_vars($model);
            $idField = strtolower($this->table_name) . '_id';
            
            // ID'yi ayır ve diğer alanları al
            $id = $modelVars[$idField];
            unset($modelVars[$idField]);
            
            // Boş değerleri filtrele
            $modelVars = array_filter($modelVars, function($value) {
                return $value !== null && $value !== '';
            });
            
            if (empty($modelVars)) {
                return false;
            }
            
            // UPDATE sorgusunu hazırla
            $set = implode(',', array_map(fn($col) => "$col = :$col", array_keys($modelVars)));
            $sql = "UPDATE " . $this->table_name . " SET $set WHERE $idField = :id";
            
            // Parametreleri hazırla
            $params = $modelVars;
            $params['id'] = $id;
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
            
        } catch (\PDOException $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
    }

    public function findBy(array $criteria): array {
        $query = 'SELECT * FROM ' . $this->table_name;
        $params = [];
        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $key => $value) {
                if (is_array($value)) {
                    $inPlaceholders = [];
                    foreach ($value as $i => $v) {
                        $ph = ":{$key}_$i";
                        $inPlaceholders[] = $ph;
                        $params[$key . "_" . $i] = $v;
                    }
                    $conditions[] = "$key IN (" . implode(",", $inPlaceholders) . ")";
                } else {
                    $conditions[] = "$key = :$key";
                    $params[$key] = $value;
                }
            }
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS, $this->modelClassName);
    }
}