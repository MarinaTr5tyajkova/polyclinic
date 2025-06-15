<?php
declare(strict_types=1);

try {
    // Подключаем автозагрузчик Composer
    require_once __DIR__ . '/../vendor/autoload.php';

    // Инициализируем приложение
    $app = require_once __DIR__ . '/../core/bootstrap.php';
    $app->run();
} catch (\Throwable $exception) {
    echo '<pre>';
    print_r($exception);
    echo '</pre>';
}
