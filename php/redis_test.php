<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/redis.php';

try {
    $redis = redis_connection();
    $result = $redis->ping();
    echo 'PING result: ' . var_export($result, true);
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Redis connection failed: ' . $e->getMessage();
}
