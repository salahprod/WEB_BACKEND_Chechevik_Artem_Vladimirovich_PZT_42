<?php
// db_config.php - Конфигурация подключения к БД
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Artem4ik171521');
define('DB_NAME', 'car_musc_db');

// Подключение к БД
function getDB() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch(PDOException $e) {
        // Возвращаем JSON ошибку вместо die()
        echo json_encode(['success' => false, 'error' => 'Ошибка подключения к БД: ' . $e->getMessage()]);
        exit;
    }
}

// Запуск сессии для корзины
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Инициализация корзины
if (!isset($_SESSION['shop_cart'])) {
    $_SESSION['shop_cart'] = [];
}

// Функция для получения количества товаров в корзине
function getCartCount() {
    return array_sum(array_column($_SESSION['shop_cart'], 'quantity'));
}
?>