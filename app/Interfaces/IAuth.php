<?php

interface IAuth {
    /**
     * Log in a user with the given credentials.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public static function login(PDO $pdo, string $username, string $password): bool;

    /**
     * Log out the current user.
     *
     * @return void
     */
    public static function logout(): void;
}