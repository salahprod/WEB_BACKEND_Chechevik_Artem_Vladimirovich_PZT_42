<?php
/**
 * Задание 3: Админ-панель
 * Защищённая страница, доступная только авторизованным пользователям
 */

session_start();

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    // Проверка cookie "запомнить меня"
    if (isset($_COOKIE['remember_token'])) {
        // В реальном проекте здесь проверка токена в БД
        // Для демонстрации просто восстанавливаем сессию
        $_SESSION['user'] = 'admin';
        $_SESSION['role'] = 'admin';
        $_SESSION['name'] = 'Администратор';
        $_SESSION['restored'] = true;
    } else {
        header('Location: login.php');
        exit;
    }
}

// Проверка бездействия (максимум 15 минут = 900 секунд)
$timeout = 900;
if (isset($_SESSION['logged_at']) && (time() - $_SESSION['logged_at']) > $timeout) {
    // Сессия истекла - выходим
    header('Location: logout.php?expired=1');
    exit;
}

// Обновляем время последней активности
$_SESSION['logged_at'] = time();

// Определяем роль для отображения
$isAdmin = ($_SESSION['role'] === 'admin');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin-top: 0;
            color: #667eea;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .stats {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #667eea;
            color: white;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-admin {
            background: #dc3545;
            color: white;
        }
        .badge-user {
            background: #28a745;
            color: white;
        }
        .restored-notice {
            background: #ffc107;
            color: #333;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['restored']) && $_SESSION['restored']): ?>
            <div class="restored-notice">
                🔄 Сессия восстановлена с помощью cookie "Запомнить меня"
            </div>
            <?php unset($_SESSION['restored']); ?>
        <?php endif; ?>
        
        <div class="header">
            <h1>🛡️ Админ-панель</h1>
            <a href="logout.php" class="logout-btn">Выйти</a>
        </div>
        
        <div class="user-info">
            <h2>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
            <p><strong>Логин:</strong> <?php echo htmlspecialchars($_SESSION['user']); ?></p>
            <p><strong>Роль:</strong> 
                <span class="badge <?php echo $isAdmin ? 'badge-admin' : 'badge-user'; ?>">
                    <?php echo $isAdmin ? 'Администратор' : 'Пользователь'; ?>
                </span>
            </p>
            <p><strong>Время входа:</strong> <?php echo date('Y-m-d H:i:s', $_SESSION['logged_at']); ?></p>
        </div>
        
        <div class="cards">
            <div class="card">
                <h3>📊 Статистика сессии</h3>
                <div class="stats">
                    <p><strong>ID сессии:</strong> <?php echo session_id(); ?></p>
                    <p><strong>Имя сессии:</strong> <?php echo session_name(); ?></p>
                    <p><strong>Время бездействия:</strong> 15 минут</p>
                </div>
            </div>
            
            <div class="card">
                <h3>🍪 Cookie</h3>
                <div class="stats">
                    <p><strong>Session cookie:</strong> <?php echo isset($_COOKIE[session_name()]) ? 'установлена' : 'не установлена'; ?></p>
                    <p><strong>Remember token:</strong> <?php echo isset($_COOKIE['remember_token']) ? 'установлен' : 'не установлен'; ?></p>
                </div>
            </div>
            
            <div class="card">
                <h3>🔐 Безопасность</h3>
                <div class="stats">
                    <p>✅ session_regenerate_id() - пересоздание ID</p>
                    <p>✅ Хеширование паролей (bcrypt)</p>
                    <p>✅ Таймаут бездействия (15 мин)</p>
                    <p>✅ HttpOnly cookies</p>
                </div>
            </div>
        </div>
        
        <?php if ($isAdmin): ?>
            <div class="card">
                <h3>👥 Управление пользователями (только для админа)</h3>
                <table>
                    <tr>
                        <th>Логин</th>
                        <th>Роль</th>
                        <th>Имя</th>
                    </tr>
                    <?php
                    $usersFile = __DIR__ . '/users.json';
                    if (file_exists($usersFile)) {
                        $users = json_decode(file_get_contents($usersFile), true);
                        foreach ($users as $login => $data):
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($login); ?></td>
                            <td><?php echo htmlspecialchars($data['role']); ?></td>
                            <td><?php echo htmlspecialchars($data['name']); ?></td>
                        </tr>
                    <?php 
                        endforeach;
                    }
                    ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>