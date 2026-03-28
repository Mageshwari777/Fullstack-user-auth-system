<?php
/**
 * Redis connection (predis/predis Composer library).
 * Used for server-side session storage keyed by token.
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (!function_exists('redis_connection')) {
    function redis_connection(): Predis\Client
    {
        static $client = null;

        if ($client instanceof Predis\Client) {
            return $client;
        }

        // Composer autoloader
        $autoload = dirname(__DIR__) . '/vendor/autoload.php';
        if (!file_exists($autoload)) {
            throw new RuntimeException('Composer dependencies not installed. Run: composer install');
        }
        require_once $autoload;

        $parameters = [
            'scheme' => 'tcp',
            'host' => REDIS_HOST,
            'port' => REDIS_PORT,
        ];

        if (is_string(REDIS_AUTH) && REDIS_AUTH !== '') {
            $parameters['password'] = REDIS_AUTH;
        }

        $client = new Predis\Client($parameters);
        return $client;
    }
}

