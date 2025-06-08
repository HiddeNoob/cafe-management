<?php

interface ICreate {
    public static function create(PDO $pdo, object $entity): bool;
}

interface IRead {
    public static function getAll(PDO $pdo): array;
    public static function getById(PDO $pdo, int $id): ?object;
    public static function findBy(PDO $pdo, array $criteria): array;
}

interface IUpdate {
    public static function update(PDO $pdo, object $entity): bool;
}

interface IDelete {
    public static function delete(PDO $pdo, int $id): bool;
}