<?php
//Задание 1: Передача данных через GET-запрос
// Проверяем наличие параметров в GET-запросе
if (isset($_GET['name']) && isset($_GET['city'])) {
    // Параметры переданы - безопасно выводим их
    $name = htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8');
    $city = htmlspecialchars($_GET['city'], ENT_QUOTES, 'UTF-8');
    echo "<h3>Пользователь $name проживает в городе $city</h3>";
} else {
    // Параметры отсутствуют
    echo "<h3>Данные не указаны</h3>";
    echo "<p>Используйте: user_info.php?name=Имя&city=Город</p>";
}
?>