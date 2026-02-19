<?php

const DIR_CONFIG = '/../config';

// Project root: works on both Windows and Linux, does not depend on DOCUMENT_ROOT
define('PROJECT_ROOT', dirname(__DIR__));

// Load Composer autoloader to resolve PSR-4 classes like Src\Route
require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($className) {
    $paths = include __DIR__ . DIR_CONFIG . '/path.php';
    $className = str_replace('\\', '/', $className);

    foreach ($paths['classes'] as $path) {
        $fileName = PROJECT_ROOT . "/$paths[root]/$path/$className.php";
        if (file_exists($fileName)) {
            require_once $fileName;
        }
    }
});

function getConfigs(string $path = DIR_CONFIG): array {
    $settings = [];
    foreach (scandir(__DIR__ . $path) as $file) {
        $name = explode('.', $file)[0];
        if (!empty($name)) {
            $settings[$name] = include __DIR__ . "$path/$file";
        }
    }
    return $settings;
}

// Initialize Eloquent ORM (Illuminate Database) using config/db.php
$__settings = getConfigs();
if (isset($__settings['db']) && is_array($__settings['db'])) {
    $capsule = new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection([
        'driver' => $__settings['db']['driver'] ?? 'mysql',
        'host' => $__settings['db']['host'] ?? '127.0.0.1',
        'database' => $__settings['db']['database'] ?? '',
        'username' => $__settings['db']['username'] ?? 'root',
        'password' => $__settings['db']['password'] ?? '',
        'charset' => $__settings['db']['charset'] ?? 'utf8',
        'collation' => $__settings['db']['collation'] ?? 'utf8_unicode_ci',
        'prefix' => $__settings['db']['prefix'] ?? '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
}

require_once __DIR__ . '/../routes/web.php';
$app = new Src\Application(new Src\Settings($__settings));

//Функция возвращает глобальный экземпляр приложения
function app() {
   global $app;
   return $app;
}

return $app;
