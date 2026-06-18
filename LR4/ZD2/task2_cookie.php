<?php
/**
 * Задание 2: Демонстрация механизма работы с cookies
 * Позволяет создавать несколько разных cookie
 */

// Обработка действий
$message = '';

// Установка новой cookie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_cookie'])) {
    $cookieName = trim($_POST['cookie_name']);
    $cookieValue = trim($_POST['cookie_value']);
    $expireDays = (int)($_POST['expire_days'] ?? 1);
    
    if (!empty($cookieName) && !empty($cookieValue)) {
        $expire = time() + $expireDays * 24 * 3600;
        setcookie($cookieName, $cookieValue, $expire, '/', '', false, true);
        $message = "✅ Cookie '$cookieName' установлена со значением: " . htmlspecialchars($cookieValue);
    } else {
        $message = "⚠️ Заполните оба поля (имя и значение cookie)";
    }
}

// Удаление конкретной cookie
if (isset($_POST['delete_cookie']) && isset($_POST['cookie_to_delete'])) {
    $cookieToDelete = $_POST['cookie_to_delete'];
    setcookie($cookieToDelete, '', time() - 3600, '/');
    $message = "🗑️ Cookie '$cookieToDelete' удалена";
}

// Удаление всех cookie
if (isset($_POST['delete_all_cookies'])) {
    foreach ($_COOKIE as $name => $value) {
        setcookie($name, '', time() - 3600, '/');
    }
    $message = "🗑️ Все cookie удалены";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Задание 2 - Работа с Cookies</title>
    <style>
        body { font-family: Arial; margin: 30px; line-height: 1.6; }
        .container { max-width: 900px; margin: 0 auto; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #ffc107; }
        .success { background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; color: #155724; }
        .form-group { margin: 15px 0; }
        input, select, button { padding: 8px; margin: 5px; }
        button { background: #28a745; color: white; border: none; cursor: pointer; border-radius: 3px; }
        .delete-btn { background: #dc3545; }
        .nav { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .cookie-list { background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .cookie-item { 
            background: white; 
            padding: 8px; 
            margin: 5px 0; 
            border-radius: 3px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            border: 1px solid #ddd;
        }
        .cookie-name { font-weight: bold; color: #007bff; }
        .cookie-value { font-family: monospace; }
        .delete-single { 
            background: #dc3545; 
            color: white; 
            border: none; 
            padding: 3px 8px; 
            border-radius: 3px; 
            cursor: pointer;
            font-size: 12px;
        }
        hr { margin: 20px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>🍪 Задание 2: Работа с Cookies</h1>
    
    <?php if ($message): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <div class="warning">
        <h3>❗ Важное замечание:</h3>
        <p><strong>Каждая cookie имеет УНИКАЛЬНОЕ имя.</strong> Если вы создадите cookie с именем, которое уже существует, оно будет ПЕРЕЗАПИСАНО (старое значение сотрётся).</p>
        <p>Чтобы сохранить несколько разных cookie — используйте разные имена, например: <code>user_name</code>, <code>user_email</code>, <code>user_theme</code> и т.д.</p>
    </div>
    
    <div class="info">
        <h3>📊 Текущие cookies (<?php echo count($_COOKIE); ?> шт.):</h3>
        <?php if (empty($_COOKIE)): ?>
            <p><em>Нет установленных cookies</em></p>
        <?php else: ?>
            <div class="cookie-list">
                <?php foreach ($_COOKIE as $name => $value): ?>
                    <div class="cookie-item">
                        <div>
                            <span class="cookie-name"><?php echo htmlspecialchars($name); ?></span>: 
                            <span class="cookie-value"><?php echo htmlspecialchars($value); ?></span>
                        </div>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="cookie_to_delete" value="<?php echo htmlspecialchars($name); ?>">
                            <button type="submit" name="delete_cookie" class="delete-single">Удалить</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <h3>✏️ Создать новую cookie:</h3>
    <form method="POST">
        <div class="form-group">
            <label><strong>Имя cookie:</strong></label>
            <input type="text" name="cookie_name" placeholder="например: user_name, theme, language" style="width: 300px;" required>
            <small>(должно быть уникальным, иначе перезапишет существующую)</small>
        </div>
        <div class="form-group">
            <label><strong>Значение:</strong></label>
            <input type="text" name="cookie_value" placeholder="значение cookie" style="width: 300px;" required>
        </div>
        <div class="form-group">
            <label><strong>Срок действия:</strong></label>
            <select name="expire_days">
                <option value="1">1 день</option>
                <option value="7">7 дней</option>
                <option value="30">30 дней</option>
                <option value="365">1 год</option>
            </select>
        </div>
        <button type="submit" name="set_cookie">➕ Создать cookie</button>
        <button type="submit" name="delete_all_cookies" class="delete-btn" onclick="return confirm('Удалить ВСЕ cookie?')">🗑️ Удалить все cookie</button>
    </form>
    
    <hr>
    
    <h3>💡 Примеры для тестирования:</h3>
    <ul>
        <li><strong>Cookie 1:</strong> имя = <code>user_name</code>, значение = <code>Иван Петров</code></li>
        <li><strong>Cookie 2:</strong> имя = <code>user_theme</code>, значение = <code>dark_mode</code></li>
        <li><strong>Cookie 3:</strong> имя = <code>last_visit</code>, значение = <code><?php echo date('Y-m-d'); ?></code></li>
    </ul>
    <p><small>Создайте несколько cookie с разными именами — они будут сохранены все вместе!</small></p>
    
    <hr>
    
    <h3>🔬 Что демонстрирует этот скрипт:</h3>
    <ul>
        <li><strong>setcookie()</strong> - установка cookie</li>
        <li><strong>$_COOKIE</strong> - чтение всех cookie</li>
        <li><strong>Удаление конкретной cookie</strong> - установка времени в прошлом</li>
        <li><strong>Удаление всех cookie</strong> - перебор всех и удаление</li>
        <li><strong>Разные имена = разные cookie</strong> (не перезаписываются)</li>
        <li><strong>Одинаковые имена = перезапись</strong> (старое значение теряется)</li>
    </ul>
    
    <div class="nav">
        <a href="task2_view_cookies.php">🔍 Посмотреть все cookies (подробно)</a>
        <a href="task2_cookie.php">🔄 Обновить страницу</a>
    </div>
</div>
</body>
</html>