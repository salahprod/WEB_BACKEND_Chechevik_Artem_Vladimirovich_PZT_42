<?php
/**
 * Задание 2: Просмотр всех cookies
 * Вспомогательная страница для отображения всех установленных cookies
 */
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр всех Cookies</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
            margin: 30px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .cookie-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .cookie-table th, .cookie-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .cookie-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .cookie-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .empty {
            text-align: center;
            color: #666;
            padding: 40px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .back-link:hover {
            background: #0056b3;
        }
        .info {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍪 Все Cookies на этом сайте</h1>
        
        <div class="info">
            <strong>ℹ️ Информация:</strong> Cookies хранятся на вашем компьютере и отправляются на сервер при каждом запросе.
        </div>
        
        <?php if (empty($_COOKIE)): ?>
            <div class="empty">
                <p>😕 На этом сайте пока нет установленных cookies.</p>
                <p>Вернитесь на <a href="task2_cookie.php">главную страницу задания 2</a> и установите cookie.</p>
            </div>
        <?php else: ?>
            <table class="cookie-table">
                <thead>
                    <tr>
                        <th>Имя cookie</th>
                        <th>Значение</th>
                        <th>Длина (символов)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_COOKIE as $name => $value): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($name); ?></strong></td>
                            <td><?php echo htmlspecialchars($value); ?></td>
                            <td><?php echo strlen($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="info">
                <strong>📊 Статистика:</strong>
                <ul>
                    <li>Всего cookies: <?php echo count($_COOKIE); ?></li>
                    <li>Общий объём данных: ~<?php echo array_sum(array_map('strlen', $_COOKIE)); ?> байт</li>
                </ul>
                <p style="font-size: 12px; color: #666;">
                    <strong>Примечание:</strong> Вы видите только cookies, которые относятся к текущему домену и пути.
                    Каждая cookie имеет срок действия (expire), после которого автоматически удаляется.
                </p>
            </div>
        <?php endif; ?>
        
        <a href="task2_cookie.php" class="back-link">← Вернуться на главную страницу задания 2</a>
    </div>
</body>
</html>