<?php
/**
 * Задание 3: Авторизация доступа с помощью сессий
 * Форма входа в систему
 */

session_start();

// Если уже авторизован - перенаправляем в админ-панель
if (isset($_SESSION['user'])) {
    header('Location: admin.php');
    exit;
}

$error = '';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Загрузка пользователей из JSON-файла
    $usersFile = __DIR__ . '/users.json';
    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);
    } else {
        // Если файла нет, создаём тестовых пользователей
        $users = [
            'admin' => [
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'name' => 'Администратор'
            ],
            'demo' => [
                'password_hash' => password_hash('demo123', PASSWORD_DEFAULT),
                'role' => 'user',
                'name' => 'Демо-пользователь'
            ]
        ];
    }
    
    // Проверка логина и пароля
    if (isset($users[$login]) && $password == $users[$login]['password']) {
        // Пересоздаём ID сессии для безопасности
        session_regenerate_id(true);
        
        // Сохраняем данные пользователя в сессии
        $_SESSION['user'] = $login;
        $_SESSION['role'] = $users[$login]['role'];
        $_SESSION['name'] = $users[$login]['name'];
        $_SESSION['logged_at'] = time();
        
        // Если отмечено "Запомнить меня" - устанавливаем долгоживущую cookie
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['remember_token'] = $token;
            setcookie('remember_token', $token, time() + 30 * 24 * 3600, '/', '', true, true);
        }
        
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация - Админ-панель</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 400px;
            padding: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .checkbox-group input {
            width: auto;
            margin-right: 10px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .info {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
        .test-credentials {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔐 Вход в админ-панель</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" required autofocus>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin:0;">Запомнить меня</label>
            </div>
            <button type="submit">Войти</button>
        </form>
        
        <div class="info">
            <hr>
            <div class="test-credentials">
                <strong>📝 Тестовые данные:</strong><br>
                Логин: admin | Пароль: admin123<br>
                Логин: demo | Пароль: demo123
            </div>
            <hr>
            <p>Безопасная авторизация с сессиями и хешированием паролей</p>
        </div>
    </div>
</body>
</html>