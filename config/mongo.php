<?php
/**
 * MongoDB connection (mongodb/mongodb Composer library).
 * Stores user profile details (age, dob, contact number).
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (!function_exists('mongo_collection')) {
    function mongo_collection(): MongoDB\Collection
    {
        static $collection = null;

        if ($collection instanceof MongoDB\Collection) {
            return $collection;
        }

        // Composer autoloader
        $autoload = dirname(__DIR__) . '/vendor/autoload.php';
        if (!file_exists($autoload)) {
            throw new RuntimeException('Composer dependencies not installed. Run: composer install');
        }
        require_once $autoload;

        $client = new MongoDB\Client(MONGO_URI);
        $collection = $client->selectCollection(MONGO_DB, MONGO_PROFILE_COLLECTION);
        return $collection;
    }
}

