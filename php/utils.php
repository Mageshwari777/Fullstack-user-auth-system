<?php
/**
 * Common API helpers (JSON responses, input parsing).
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

date_default_timezone_set(APP_TIMEZONE);

function json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    // Basic CORS for local testing (adjust/remove for production)
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function handle_preflight(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        json_response(['ok' => true], 200);
    }
}

function post_json(): array
{
    $raw = file_get_contents('php://input') ?: '';
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function str_required(array $data, string $key): string
{
    $val = $data[$key] ?? null;
    if (!is_string($val)) {
        return '';
    }
    return trim($val);
}

function int_nullable(array $data, string $key): ?int
{
    if (!array_key_exists($key, $data) || $data[$key] === null || $data[$key] === '') {
        return null;
    }
    if (is_int($data[$key])) {
        return $data[$key];
    }
    if (is_string($data[$key]) && preg_match('/^\d+$/', $data[$key])) {
        return (int)$data[$key];
    }
    return null;
}

function str_nullable(array $data, string $key): ?string
{
    if (!array_key_exists($key, $data) || $data[$key] === null) {
        return null;
    }
    if (!is_string($data[$key])) {
        return null;
    }
    $v = trim($data[$key]);
    return $v === '' ? null : $v;
}

function is_valid_date_yyyy_mm_dd(?string $date): bool
{
    if ($date === null) {
        return true;
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return false;
    }
    $parts = explode('-', $date);
    return checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0]);
}

