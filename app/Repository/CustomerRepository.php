<?php
// CustomerRepository.php

require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Interfaces/IRepository.php';


class CustomerRepository implements ICreate, IRead, IUpdate, IDelete {
    public static function create(PDO $pdo, object $entity): bool {
        /** @var Customer $customer */
        $customer = $entity;
        $stmt = $pdo->prepare("INSERT INTO CUSTOMER (customer_name, customer_surname, customer_phone, customer_email, customer_nickname, customer_password) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $customer->customer_name,
            $customer->customer_surname,
            $customer->customer_phone,
            $customer->customer_email,
            $customer->customer_nickname,
            $customer->customer_password
        ]);
    }

    public static function delete(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM CUSTOMER WHERE customer_id = ?");
        return $stmt->execute([$id]);
    }

    public static function findBy(PDO $pdo, array $criteria): array {
        $query = "SELECT * FROM CUSTOMER WHERE ";
        $params = [];
        foreach ($criteria as $key => $value) {
            $query .= "$key = ? AND ";
            $params[] = $value;
        }
        $query = rtrim($query, ' AND ');
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Customer');
    }

    /**
     * Get all customers.
     * @param PDO $pdo
     * @return Customer[]
     */
    public static function getAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM CUSTOMER");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Customer');
    }

    public static function getById(PDO $pdo, int $id): ?Customer {
        $stmt = $pdo->prepare("SELECT * FROM CUSTOMER WHERE customer_id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Customer');
        return $stmt->fetch() ?: null;
    }

    public static function update(PDO $pdo, object $entity): bool {
        /** @var Customer $customer */
        $customer = $entity;
        $stmt = $pdo->prepare("UPDATE CUSTOMER SET customer_name = ?, customer_surname = ?, customer_phone = ?, customer_email = ?, customer_nickname = ?, customer_password = ? WHERE customer_id = ?");
        return $stmt->execute([
            $customer->customer_name,
            $customer->customer_surname,
            $customer->customer_phone,
            $customer->customer_email,
            $customer->customer_nickname,
            $customer->customer_password,
            $customer->customer_id
        ]);
    }
}
