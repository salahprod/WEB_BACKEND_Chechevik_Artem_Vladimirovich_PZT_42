<?php
/**
 * Задание 1: Вторая страница примера с сессиями
 * Показывает, что данные сессии доступны на разных страницах
 */
session_start();

// Приветствие с именем из сессии (если оно установлено)
$username = $_SESSION['username'] ?? 'гость';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Задание 1 - Страница 2</title>
    <style>
        body { font-family: Arial; margin: 30px; line-height: 1.6; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .nav { margin: 20px 0; }
        .nav a { margin-right: 15px; padding: 5px 10px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; }
        .form-group { margin: 10px 0; }
        input, button { padding: 8px; }
    </style>
</head>
<body>
    <h1> Страница 2 (page2)</h1>
    
    <div class="info">
        <p><strong>Привет, <?php echo htmlspecialchars($username); ?>!</strong></p>
        <p><strong>ID сессии:</strong> <?php echo session_id(); ?></p>
        <p><strong>Счётчик просмотров страницы 1:</strong> <?php echo $_SESSION['page_views'] ?? 0; ?> раз(а)</p>
    </div>
    
    <div class="form-group">
        <h3>Установить имя пользователя в сессии:</h3>
        <form method="POST">
            <input type="text" name="username" placeholder="Введите ваше имя">
            <button type="submit">Сохранить</button>
        </form>
    </div>
    
    <?php
    // Обработка формы - сохраняем имя в сессию
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
        $_SESSION['username'] = htmlspecialchars(trim($_POST['username']));
        echo "<p style='color:green;'>Имя сохранено в сессии!</p>";
        echo "<script>setTimeout(function(){ window.location.reload(); }, 1000);</script>";
    }
    ?>
    
    <div class="nav">
        <a href="task1_session.php">← Вернуться на страницу 1</a>
        <a href="task1_page3.php">Перейти на страницу 3 (завершение сессии) →</a>
    </div>
</body>
</html>