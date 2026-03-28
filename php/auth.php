<?php
/**
 * Token-based auth backed by Redis.
 * - Client stores token in localStorage (not PHP sessions).
 * - Server stores token->user data in Redis.
 */

declare(strict_types=1);

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/../config/redis.php';

function session_key(string $token): string
{
    return 'sess:' . $token;
}

function create_session(int $userId, string $username): array
{
    $token = bin2hex(random_bytes(32));
    $redis = redis_connection();

    $payload = [
        'user_id' => $userId,
        'username' => $username,
        'created_at' => time(),
    ];

    $redis->setex(session_key($token), SESSION_TTL_SECONDS, json_encode($payload));

    return [
        'token' => $token,
        'ttl_seconds' => SESSION_TTL_SECONDS,
        'user' => [
            'id' => $userId,
            'username' => $username,
        ],
    ];
}

function require_auth_user(): array
{
    $data = post_json();
    $token = str_required($data, 'token');
    if ($token === '') {
        json_response(['ok' => false, 'message' => 'Missing token'], 401);
    }

    $redis = redis_connection();
    $raw = $redis->get(session_key($token));
    if (!is_string($raw) || $raw === '') {
        json_response(['ok' => false, 'message' => 'Invalid/expired session'], 401);
    }

    $session = json_decode($raw, true);
    if (!is_array($session) || !isset($session['user_id'], $session['username'])) {
        // Corrupted session in Redis
        $redis->del([session_key($token)]);
        json_response(['ok' => false, 'message' => 'Invalid/expired session'], 401);
    }

    return [
        'token' => $token,
        'user_id' => (int)$session['user_id'],
        'username' => (string)$session['username'],
    ];
}

function destroy_session(string $token): void
{
    $redis = redis_connection();
    $redis->del([session_key($token)]);
}

