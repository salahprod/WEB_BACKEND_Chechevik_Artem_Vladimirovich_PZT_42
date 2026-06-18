<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        form { width: 300px; }
        input, button { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: blue; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Форма авторизации</h2>
    <form action="auth.php" method="POST">
        <label>Логин:</label>
        <input type="text" name="username" required>
        
        <label>Пароль:</label>
        <input type="password" name="userpass" required>
        
        <button type="submit">Войти</button>
    </form>
</body>
</html>