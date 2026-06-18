<?php
// api_news.php - API для получения новостей
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once 'db_config.php';

$pdo = getDB();

try {
    // Проверяем существование таблицы news
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'news'");
    if ($tableCheck->rowCount() == 0) {
        // Создаем таблицу если её нет
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS news (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Вставляем тестовые новости
        $pdo->exec("
            INSERT INTO news (title, content, image) VALUES
            ('Новая антигравийная пленка!', 'Мы запустили новую линейку антигравийных пленок с улучшенными характеристиками. Теперь защита вашего авто еще надежнее!', 'Image/Car1.jpg'),
            ('Скидка 20% на комплексную оклейку', 'При заказе оклейки всего кузова защитной пленкой - скидка 20%. Акция действует до конца месяца.', 'Image/Car2.jpg'),
            ('Открытие нового центра детейлинга', 'Мы открыли современный центр детейлинга с профессиональным оборудованием. Записывайтесь уже сегодня!', 'Image/Car3.jpg')
        ");
    }
    
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
    $stmt = $pdo->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    $news = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'news' => $news
    ]);
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'news' => []
    ]);
}
?>