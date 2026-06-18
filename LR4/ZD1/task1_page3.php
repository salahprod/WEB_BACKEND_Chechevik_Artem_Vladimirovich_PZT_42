<?php
/**
 * Задание 1: Третья страница - завершение сессии
 * Демонстрирует unset() и session_destroy()
 */

session_start();

// Сохраняем данные для отображения до удаления
$sessionData = $_SESSION;
$sessionId = session_id();

// Удаляем конкретную переменную сессии
unset($_SESSION['username']);

// Очищаем массив сессии
$_SESSION = array();

// Удаляем cookie сессии
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, 
        $params['path'], $params['domain'], 
        $params['secure'], $params['httponly']
    );
}

// Уничтожаем сессию
session_destroy();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Задание 1 - Страница 3 (Завершение сессии)</title>
    <style>
        body { font-family: Arial; margin: 30px; line-height: 1.6; }
        .info { background: #f8e8e8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .nav { margin: 20px 0; }
        .nav a { margin-right: 15px; padding: 5px 10px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>Страница 3 - Завершение сессии</h1>
    
    <div class="info">
        <h3>Данные до уничтожения сессии:</h3>
        <pre><?php print_r($sessionData); ?></pre>
        
        <h3>После выполнения:</h3>
        <ul>
            <li><strong>unset($_SESSION['username'])</strong> - удалена переменная username</li>
            <li><strong>$_SESSION = array()</strong> - очищен массив сессии</li>
            <li><strong>setcookie(session_name(), '', time()-3600)</strong> - удалена cookie</li>
            <li><strong>session_destroy()</strong> - уничтожена сессия на сервере</li>
        </ul>
        
        <p style="color:red;">⚠️ Сессия уничтожена. Данные больше не доступны.</p>
        <p><strong>Попытка доступа к $_SESSION:</strong> <?php var_dump($_SESSION); ?></p>
    </div>
    
    <div class="nav">
        <a href="task1_session.php">Начать новую сессию →</a>
    </div>
</body>
</html>