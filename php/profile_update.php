<?php
/**
 * Profile update API (AJAX only).
 * Authenticates using Redis token.
 * Upserts profile in MongoDB by user_id.
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

$data = post_json();

$age = int_nullable($data, 'age');
$dob = str_nullable($data, 'dob');
$contact = str_nullable($data, 'contact');

if ($age !== null && ($age < 0 || $age > 150)) {
    json_response(['ok' => false, 'message' => 'Age must be between 0 and 150'], 400);
}

if (!is_valid_date_yyyy_mm_dd($dob)) {
    json_response(['ok' => false, 'message' => 'DOB must be YYYY-MM-DD'], 400);
}

if ($contact !== null && strlen($contact) > 30) {
    json_response(['ok' => false, 'message' => 'Contact number is too long'], 400);
}

$update = [
    'age' => $age,
    'dob' => $dob,
    'contact' => $contact,
    'updated_at' => time(),
];

$col = mongo_collection();
$col->updateOne(
    ['user_id' => $userId],
    [
        '$set' => $update,
        '$setOnInsert' => ['user_id' => $userId, 'created_at' => time()],
    ],
    ['upsert' => true]
);

json_response(['ok' => true, 'message' => 'Profile updated']);

