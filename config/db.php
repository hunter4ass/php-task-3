<?php
// Для production (wsr.ru и др.): задайте переменные окружения или отредактируйте значения ниже
return [
   'driver' => 'mysql',
   'host' => getenv('DB_HOST') ?: 'localhost',
   'database' => getenv('DB_DATABASE') ?: 'mvc',
   'username' => getenv('DB_USERNAME') ?: 'root',
   'password' => getenv('DB_PASSWORD') ?: '',
   'charset' => getenv('DB_CHARSET') ?: 'utf8',
   'collation' => getenv('DB_COLLATION') ?: 'utf8_unicode_ci',
   'prefix' => getenv('DB_PREFIX') ?: '',
];

