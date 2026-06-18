<?php
/**
 * Задание 3: Выход из системы
 * Очистка сессии и удаление cookies
 */
session_start();

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

// Удаляем cookie "запомнить меня"
setcookie('remember_token', '', time() - 3600, '/', '', true, true);

// Уничтожаем сессию на сервере
session_destroy();

// Перенаправляем на страницу входа
header('Location: login.php?logout=1');
exit;
?>