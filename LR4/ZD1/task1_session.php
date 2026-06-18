<?php
/**
 * Задание 1: Демонстрация механизма работы с сессиями
 * Пример простой сессии с тремя страницами
 */
// Запускаем сессию (должна быть до любого вывода)
session_start();

// Получаем информацию о текущей сессии
$sessionName = session_name();      // PHPSESSID
$sessionId = session_id();          // Уникальный идентификатор сессии

// Инициализация счётчика посещений страницы
if (!isset($_SESSION['page_views'])) {
    $_SESSION['page_views'] = 0;
}
$_SESSION['page_views']++;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Задание 1 - Работа с сессиями</title>
    <style>
        body { font-family: Arial; margin: 30px; line-height: 1.6; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .nav { margin: 20px 0; }
        .nav a { margin-right: 15px; padding: 5px 10px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; }
        .nav a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>📚 Задание 1: Демонстрация работы сессий</h1>
    
    <div class="info">
        <h3>Информация о текущей сессии:</h3>
        <p><strong>Имя сессии (session_name):</strong> <?php echo htmlspecialchars($sessionName); ?></p>
        <p><strong>ID сессии (session_id):</strong> <?php echo htmlspecialchars($sessionId); ?></p>
        <p><strong>Вы открыли эту страницу:</strong> <?php echo $_SESSION['page_views']; ?> раз(а)</p>
        <p><strong>Время текущего визита:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
    
    <div class="nav">
        <a href="task1_session.php">Обновить страницу</a>
        <a href="?action=clear">Сбросить счётчик</a>
        <a href="task1_page2.php">Перейти на страницу 2</a>
    </div>
    
    <?php
    // Обработка сброса счётчика
    if (isset($_GET['action']) && $_GET['action'] == 'clear') {
        unset($_SESSION['page_views']);
        echo "<p style='color:green;'>Счётчик сброшен!</p>";
        echo "<script>setTimeout(function(){ window.location.href='task1_session.php'; }, 1000);</script>";
    }
    ?>
    
    <hr>
    <h3>🔬 Что демонстрирует этот скрипт:</h3>
    <ul>
        <li><strong>session_start()</strong> - открытие сессии</li>
        <li><strong>session_id()</strong> - получение уникального идентификатора сессии</li>
        <li><strong>session_name()</strong> - получение имени сессии (PHPSESSID)</li>
        <li><strong>$_SESSION</strong> - суперглобальный массив для хранения данных сессии</li>
        <li><strong>Счётчик просмотров</strong> - данные сохраняются между запросами</li>
    </ul>
</body>
</html>