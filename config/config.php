<?php
/**
 * Global configuration.
 * Update these values to match your local environment.
 */

declare(strict_types=1);

// App
define('APP_TIMEZONE', 'UTC');
define('SESSION_TTL_SECONDS', 60 * 60); // 1 hour

// MySQL
define('MYSQL_HOST', '127.0.0.1');
define('MYSQL_PORT', '3306');
define('MYSQL_DB', 'user_auth');
define('MYSQL_USER', 'root');
define('MYSQL_PASS', '');
define('MYSQL_CHARSET', 'utf8mb4');

// MongoDB
define('MONGO_URI', 'mongodb://127.0.0.1:27017');
define('MONGO_DB', 'user_auth');
define('MONGO_PROFILE_COLLECTION', 'profiles');

// Redis
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', 6379);
define('REDIS_AUTH', null); // set to string password if needed

