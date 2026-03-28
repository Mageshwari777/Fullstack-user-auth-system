<?php
/**
 * Login API (AJAX only).
 * Validates credentials in MySQL using prepared statements.
 * On success:
 * - returns a token for localStorage
 * - stores session payload in Redis
 */

declare(strict_types=1);

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/../config/mysql.php';
require_once __DIR__ . '/auth.php';

handle_preflight();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_response(['ok' => false, 'message' => 'Method not allowed'], 405);
}

$data = post_json();
$identity = str_required($data, 'identity'); // username or email
$password = str_required($data, 'password');

if ($identity === '' || $password === '') {
    json_response(['ok' => false, 'message' => 'All fields are required'], 400);
}

$pdo = mysql_connection();

$stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$identity, $identity]);
$user = $stmt->fetch();

if (!$user || !isset($user['password_hash'])) {
    json_response(['ok' => false, 'message' => 'Invalid credentials'], 401);
}

if (!password_verify($password, (string)$user['password_hash'])) {
    json_response(['ok' => false, 'message' => 'Invalid credentials'], 401);
}

$session = create_session((int)$user['id'], (string)$user['username']);
json_response(['ok' => true, 'message' => 'Login successful', 'session' => $session]);

