<?php
/**
 * Задание 2: Обработка формы авторизации
 * Получает логин и пароль из POST-запроса
 */
// Проверяем, была ли отправлена форма методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Проверяем наличие логина и пароля
    if (isset($_POST['username']) && isset($_POST['userpass'])) {
        
        // Защита от XSS-атак с помощью htmlentities
        $username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
        $password = $_POST['userpass']; // Пароль не выводим на экран
        
        // Выводим приветствие
        echo "<h3>Добро пожаловать, $username!</h3>";
        echo "<p><a href='login_form.php'>Вернуться к форме</a></p>";
        
    } else {
        echo "<h3>Ошибка: не все поля заполнены</h3>";
        echo "<p><a href='login_form.php'>Попробовать снова</a></p>";
    }
    
} else {
    // Если кто-то обратился к файлу напрямую
    echo "<h3>Доступ запрещён. Используйте форму авторизации.</h3>";
    echo "<p><a href='login_form.php'>Перейти к форме</a></p>";
}
?>