<?php
// EmployeeRepository.php

require_once __DIR__ . '/../Models/Employee.php';

class EmployeeRepository implements ICreate, IRead, IUpdate, IDelete {
    public static function create(PDO $pdo, object $entity): bool {
        /** @var Employee $employee */
        $employee = $entity;
        $stmt = $pdo->prepare("INSERT INTO employees (employee_name, employee_surname, employee_nickname, employee_phone, employee_email, employee_password, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $employee->name,
            $employee->surname,
            $employee->nickname,
            $employee->phone,
            $employee->email,
            $employee->password,
            $employee->hire_date
        ]);
    }

    public static function delete(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM employees WHERE employee_id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Find employees by given criteria.
     * 
     * @param PDO $pdo
     * @param array $criteria
     * @return Employee[]
     */
    public static function findBy(PDO $pdo, array $criteria): array {
        $query = "SELECT * FROM employees";
        $params = [];

        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $key => $value) {
                $conditions[] = "$key = ?";
                $params[] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Employee');
    }

    /**
     * Get all employees.
     * 
     * @param PDO $pdo
     * @return Employee[]
     */
    public static function getAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM employees");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Employee');
    }

    /**
     * Get an employee by ID.
     * 
     * @param PDO $pdo
     * @param int $id
     * @return Employee|null
     */

    public static function getById(PDO $pdo, int $id): ?Employee {
        $stmt = $pdo->prepare("SELECT * FROM employees WHERE employee_id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Employee');
        return $stmt->fetch() ?: null;
    }

    public static function update(PDO $pdo, object $entity): bool {
        /** @var Employee $employee */
        $employee = $entity;
        $stmt = $pdo->prepare("UPDATE employees SET employee_name = ?, employee_surname = ?, employee_nickname = ?, employee_phone = ?, employee_email = ?, employee_password = ?, hire_date = ? WHERE employee_id = ?");
        return $stmt->execute([
            $employee->name,
            $employee->surname,
            $employee->nickname,
            $employee->phone,
            $employee->email,
            $employee->password,
            $employee->hire_date,
            $employee->id
        ]);
    }
}
