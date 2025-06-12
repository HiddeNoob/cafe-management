<?php
class DatabaseController{

    private static $pdo = null;
    private static $host = 'YOUR_HOST';
    private static $db = 'YOUR_DATABASE'; 
    private static $user = 'YOUR_USERNAME';
    private static $pass = 'YOUR_PASSWORD'; 

    private static function loadEnv($path) {
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($name, $value) = array_map('trim', explode('=', $line, 2));
            switch ($name) {
                case 'DB_HOST': self::$host = $value; break;
                case 'DB_NAME': self::$db = $value; break;
                case 'DB_USER': self::$user = $value; break;
                case 'DB_PASSWORD': self::$pass = $value; break;
            }
        }
    }

    public static function getPDO() {
        if (DatabaseController::$pdo === null) {
            // .env'den oku ve değişkenlere ata
            self::loadEnv(__DIR__ . '/../../.env');
            $charset = 'utf8mb4';
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=$charset";
            try {
                DatabaseController::$pdo = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }
        return DatabaseController::$pdo;
    }

}