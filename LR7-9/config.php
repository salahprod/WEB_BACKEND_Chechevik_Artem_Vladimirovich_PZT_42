<?php
// config.php - Конфигурационный файл
session_start();

// НАСТРОЙКИ САЙТА (ЗАМЕНИТЕ НА СВОИ)
define('SITE_NAME', 'Car Musc');
define('ADMIN_EMAIL', 'artemcesevik655@carmusc.ru');      // ← ВАШ EMAIL ДЛЯ ПОЛУЧЕНИЯ ПИСЕМ
define('ADMIN_EMAILS', [
    'artemcesevik655@carmusc.ru',
    'info@carmusc.ru',
    'manager@carmusc.ru'
]);

// НАСТРОЙКИ SMTP ДЛЯ PHPMailer (ЗАМЕНИТЕ НА СВОИ)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'artemcesevik655@gmail.com');    // ← ВАШ EMAIL
define('SMTP_PASS', 'Artem4ik171521');       // ← ПАРОЛЬ ПРИЛОЖЕНИЯ
define('SMTP_SECURE', 'tls');

// ЛОГИРОВАНИЕ
function logMessage($type, $message) {
    $logFile = __DIR__ . '/logs/mail.log';
    $dir = dirname($logFile);
    if (!file_exists($dir)) mkdir($dir, 0777, true);
    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . "] [$type] $message" . PHP_EOL, FILE_APPEND);
}

// СОЗДАНИЕ ПАПОК
foreach (['data', 'logs', 'uploads'] as $dir) {
    if (!file_exists(__DIR__ . '/' . $dir)) mkdir(__DIR__ . '/' . $dir, 0777, true);
}
?>