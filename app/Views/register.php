<form method="POST" action="/register">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="surname">Surname:</label>
    <input type="text" id="surname" name="surname" required><br>

    <label for="phone">Phone:</label>
    <input type="tel" id="phone" name="phone" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="nickname">Nickname:</label>
    <input type="text" id="nickname" name="nickname" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit">Register</button>
</form>
<?php
require_once __DIR__ . '/../Views/showException.php';
