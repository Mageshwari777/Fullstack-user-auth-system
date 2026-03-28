<?php
/**
 * Registration API (AJAX only).
 * Saves username/email/password_hash in MySQL using prepared statements.
 */

declare(strict_types=1);

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/../config/mysql.php';

handle_preflight();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_response(['ok' => false, 'message' => 'Method not allowed'], 405);
}

$data = post_json();
$username = str_required($data, 'username');
$email = str_required($data, 'email');
$password = str_required($data, 'password');

if ($username === '' || $email === '' || $password === '') {
    json_response(['ok' => false, 'message' => 'All fields are required'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(['ok' => false, 'message' => 'Invalid email'], 400);
}

if (strlen($password) < 6) {
    json_response(['ok' => false, 'message' => 'Password must be at least 6 characters'], 400);
}

$pdo = mysql_connection();

// Check uniqueness (prepared statements)
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$username, $email]);
$exists = $stmt->fetch();
if ($exists) {
    json_response(['ok' => false, 'message' => 'Username or email already exists'], 409);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
$stmt->execute([$username, $email, $hash]);

json_response(['ok' => true, 'message' => 'Registration successful']);

