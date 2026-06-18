<?php
/**
 * Задание 3: Форма регистрации с валидацией
 * Все поля обязательны, пароль минимум 6 символов
 */
// Инициализация переменных
$errors = [];
$formData = ['name' => '', 'email' => '', 'password' => '', 'confirm_password' => ''];
$success = false;

// Обработка отправленной формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Получаем данные из формы
    $formData['name'] = trim($_POST['name'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $formData['password'] = $_POST['password'] ?? '';
    $formData['confirm_password'] = $_POST['confirm_password'] ?? '';
    
    // Валидация: проверяем заполнение всех полей
    if (empty($formData['name'])) {
        $errors['name'] = 'Имя обязательно для заполнения';
    }
    
    if (empty($formData['email'])) {
        $errors['email'] = 'Email обязателен для заполнения';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email адрес';
    }
    
    if (empty($formData['password'])) {
        $errors['password'] = 'Пароль обязателен для заполнения';
    } elseif (strlen($formData['password']) < 6) {
        $errors['password'] = 'Пароль должен содержать не менее 6 символов';
    }
    
    if (empty($formData['confirm_password'])) {
        $errors['confirm_password'] = 'Подтверждение пароля обязательно';
    } elseif ($formData['password'] !== $formData['confirm_password']) {
        $errors['confirm_password'] = 'Пароли не совпадают';
    }
    
    // Если ошибок нет — регистрация успешна
    if (empty($errors)) {
        $success = true;
        // Здесь можно сохранить данные в базу данных (для ИДЗ)
        // Для лабораторной работы просто выводим подтверждение
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        form { width: 350px; }
        input, button { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        button { background: green; color: white; border: none; cursor: pointer; }
        .error { color: red; font-size: 12px; margin: 0 0 10px 0; }
        .error-list { color: red; margin-bottom: 15px; }
        .success { color: green; background: #e8ffe8; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>
    <h2>Регистрация нового пользователя</h2>
    
    <?php if ($success): ?>
        <div class="success">
            <strong>Регистрация успешно завершена!</strong><br>
            <?php 
            // Безопасный вывод данных пользователя
            $safeName = htmlspecialchars($formData['name'], ENT_QUOTES, 'UTF-8');
            $safeEmail = htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8');
            echo "Имя: $safeName<br>";
            echo "Email: $safeEmail";
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors) && !$success): ?>
        <div class="error-list">
            <strong>Исправьте следующие ошибки:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label>Имя:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($formData['name']); ?>">
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($formData['email']); ?>">
        
        <label>Пароль (не менее 6 символов):</label>
        <input type="password" name="password">
        
        <label>Подтверждение пароля:</label>
        <input type="password" name="confirm_password">
        
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>