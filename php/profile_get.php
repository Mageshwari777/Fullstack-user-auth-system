<?php
/**
 * Profile fetch API (AJAX only).
 * Authenticates using Redis token.
 * Loads profile from MongoDB by user_id.
 */

declare(strict_types=1);

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/mongo.php';

handle_preflight();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_response(['ok' => false, 'message' => 'Method not allowed'], 405);
}

$auth = require_auth_user();
$userId = (int)$auth['user_id'];

$col = mongo_collection();
$doc = $col->findOne(['user_id' => $userId]);

$profile = [
    'age' => null,
    'dob' => null,
    'contact' => null,
];

if ($doc) {
    $profile['age'] = $doc['age'] ?? null;
    $profile['dob'] = $doc['dob'] ?? null;
    $profile['contact'] = $doc['contact'] ?? null;
}

json_response([
    'ok' => true,
    'user' => [
        'id' => $userId,
        'username' => $auth['username'],
    ],
    'profile' => $profile,
]);

