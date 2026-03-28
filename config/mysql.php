<?php
/**
 * MySQL connection (PDO).
 * Uses prepared statements everywhere (PDO prepared statements).
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function mysql_connection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        MYSQL_HOST,
        MYSQL_PORT,
        MYSQL_DB,
        MYSQL_CHARSET
    );

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, MYSQL_USER, MYSQL_PASS, $options);
    return $pdo;
}

