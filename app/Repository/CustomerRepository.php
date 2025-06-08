<?php
// CustomerRepository.php

require_once __DIR__ . '/../Models/Customer.php';

class CustomerRepository implements ICreate, IRead, IUpdate, IDelete {
    public static function create(PDO $pdo, object $entity): bool {
        /** @var Customer $customer */
        $customer = $entity;
        $stmt = $pdo->prepare("INSERT INTO customers (customer_name, customer_surname, customer_phone, customer_email, customer_nickname, customer_password) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $customer->name,
            $customer->surname,
            $customer->phone,
            $customer->email,
            $customer->nickname,
            $customer->password
        ]);
    }

    public static function delete(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM customers WHERE customer_id = ?");
        return $stmt->execute([$id]);
    }

    public static function findBy(PDO $pdo, array $criteria): array {
        $query = "SELECT * FROM customers WHERE ";
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

    public static function getAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM customers");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Customer');
    }

    public static function getById(PDO $pdo, int $id): ?Customer {
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE customer_id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Customer');
        return $stmt->fetch() ?: null;
    }

    public static function update(PDO $pdo, object $entity): bool {
        /** @var Customer $customer */
        $customer = $entity;
        $stmt = $pdo->prepare("UPDATE customers SET customer_name = ?, customer_surname = ?, customer_phone = ?, customer_email = ?, customer_nickname = ?, customer_password = ? WHERE customer_id = ?");
        return $stmt->execute([
            $customer->name,
            $customer->surname,
            $customer->phone,
            $customer->email,
            $customer->nickname,
            $customer->password,
            $customer->id
        ]);
    }
}
