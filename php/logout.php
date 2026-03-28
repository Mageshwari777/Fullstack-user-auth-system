<?php
/**
 * Logout API (AJAX only).
 * Deletes the session from Redis. Client clears localStorage separately.
 */

declare(strict_types=1);

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/auth.php';

handle_preflight();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_response(['ok' => false, 'message' => 'Method not allowed'], 405);
}

$data = post_json();
$token = str_required($data, 'token');
if ($token === '') {
    json_response(['ok' => false, 'message' => 'Missing token'], 400);
}

destroy_session($token);
json_response(['ok' => true, 'message' => 'Logged out']);

