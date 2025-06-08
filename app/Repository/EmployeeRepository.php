<?php
// EmployeeRepository.php

require_once __DIR__ . '/../Models/Employee.php';
require_once __DIR__ . '/../Interfaces/ICreate.php';
require_once __DIR__ . '/../Interfaces/IRead.php';
require_once __DIR__ . '/../Interfaces/IUpdate.php';
require_once __DIR__ . '/../Interfaces/IDelete.php';

class EmployeeRepository implements ICreate, IRead, IUpdate, IDelete {
    public static function create(PDO $pdo, object $entity): bool {
        /** @var Employee $employee */
        $employee = $entity;
        $stmt = $pdo->prepare("INSERT INTO EMPLOYEE (employee_name, employee_surname, employee_nickname, employee_phone, employee_email, employee_password, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $employee->employee_name,
            $employee->employee_surname,
            $employee->employee_nickname,
            $employee->employee_phone,
            $employee->employee_email,
            $employee->employee_password,
            $employee->employee_hire_date
        ]);
    }

    public static function delete(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM EMPLOYEE WHERE employee_id = ?");
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
        $query = "SELECT * FROM EMPLOYEE";
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
        $stmt = $pdo->query("SELECT * FROM EMPLOYEE");
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
        $stmt = $pdo->prepare("SELECT * FROM EMPLOYEE WHERE employee_id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Employee');
        return $stmt->fetch() ?: null;
    }

    public static function update(PDO $pdo, object $entity): bool {
        /** @var Employee $employee */
        $employee = $entity;
        $stmt = $pdo->prepare("UPDATE EMPLOYEE SET employee_name = ?, employee_surname = ?, employee_nickname = ?, employee_phone = ?, employee_email = ?, employee_password = ?, hire_date = ? WHERE employee_id = ?");
        return $stmt->execute([
            $employee->employee_name,
            $employee->employee_surname,
            $employee->employee_nickname,
            $employee->employee_phone,
            $employee->employee_email,
            $employee->employee_password,
            $employee->employee_hire_date,
            $employee->employee_id
        ]);
    }
}
