<?php

class NotificationHandler
{
    /**
     * Stores a notification message in session.
     *
     * @param string $message The message to display.
     * @param string $type The type of notification. Possible values:
     *                     'primary' (default, blue),
     *                     'success' (green),
     *                     'info' (light blue),
     *                     'warning' (yellow),
     *                     'danger' (red).
     */
    public static function notify(string $message, string $type = 'info'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['notification'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    /**
     * Displays and clears the notification if one exists.
     */
    public static function display(): void
    {
        if (isset($_SESSION['notification'])) {
            $type = $_SESSION['notification']['type'];
            $message = $_SESSION['notification']['message'];
            require_once __DIR__ . '/../Views/Notification.php';
            unset($_SESSION['notification']);
        }
    }

    /**
     * Display a success notification
     * @param string $message The success message to display
     */
    public static function success(string $message): void
    {
        self::notify($message, 'success');
    }

    /**
     * Display an error notification
     * @param string $message The error message to display
     */
    public static function error(string $message): void
    {
        self::notify($message, 'danger');
    }
}

?>