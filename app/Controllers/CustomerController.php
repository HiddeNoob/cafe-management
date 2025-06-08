<?php
// CustomerController handles customer-related logic
class CustomerController {
    public function index($pdo) {
        // Example: fetch all customers and show the view
        $stmt = $pdo->query('SELECT * FROM customers');
        $customers = $stmt->fetchAll();
        require __DIR__ . '/../Views/customers.php';
    }
    // ...existing code...
}
