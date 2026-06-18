<?php
// Задание 3: Работа со строками
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><title>ЛР2 - Строки</title></head><body>";
echo "<h1>Задание 3: Работа со строками</h1><pre>";

// Исходные данные (добавлен массив students из задания 2, так как он используется в пункте 11)
$students = [
    ['name' => 'Анна', 'age' => 20, 'grade' => 4.5, 'city' => 'Минск'],
    ['name' => 'Иван', 'age' => 22, 'grade' => 3.8, 'city' => 'Гродно'],
    ['name' => 'Мария', 'age' => 19, 'grade' => 4.9, 'city' => 'Брест'],
    ['name' => 'Петр', 'age' => 21, 'grade' => 4.1, 'city' => 'Гродно'],
    ['name' => 'Елена', 'age' => 20, 'grade' => 4.7, 'city' => 'Минск'],
    ['name' => 'Алексей', 'age' => 23, 'grade' => 3.5, 'city' => 'Витебск']
];

$text1 = " PHP (Hypertext Preprocessor) — это скриптовый язык программирования общего назначения. ";
$text2 = "Я люблю PHP. PHP — это мощный язык. Я учу PHP.";
$userComment = "<b>Отличный сайт!</b> <script>alert('XSS');</script>";
$price = " 1 234,56 руб. ";
$slugSource = "Привет, как дела?";
$csvLine = "Иванов;Иван;ivan@mail.com;29;Минск";

// 1. Способы записи строк
echo "<h2>1. Способы записи строк</h2>";
$myName = "Сергей";
echo 'Одинарные кавычки: \'Привет, $myName!\' → Выведет буквально $myName (без подстановки)' . "\n";
echo "Двойные кавычки: \"Привет, $myName!\" → Подставит значение переменной\n";
$heredoc = <<<EOD
Heredoc-синтаксис:
Привет, $myName!
Это многострочный текст с поддержкой переменных.
EOD;
echo $heredoc . "\n";

// 2. Доступ к символам
echo "<h2>2. Доступ к символам</h2>";
echo "Первый символ \$text1 через [0]: '" . $text1[0] . "' (пробел)\n";
echo "Первый символ через mb_substr: '" . mb_substr($text1, 0, 1) . "'\n";

// Замена первого символа на заглавный (учёт UTF-8)
$firstChar = mb_strtoupper(mb_substr($slugSource, 0, 1));
$rest = mb_substr($slugSource, 1);
$slugCapitalized = $firstChar . $rest;
echo "Исходный slug: $slugSource\n";
echo "С заглавной: $slugCapitalized\n";

// 3. Операции со строками
echo "<h2>3. Операции со строками</h2>";
$concat = "Имя: " . $myName;
echo $concat . "\n";
echo "Сравнение '123' и 123: == " . (('123' == 123) ? 'true' : 'false') . ", === " . (('123' === 123) ? 'true' : 'false') . "\n";

// 4. Длина строки
echo "<h2>4. Длина строки</h2>";
echo "Длина \$text1 в байтах (strlen): " . strlen($text1) . "\n";
echo "Длина \$text1 в символах (mb_strlen): " . mb_strlen($text1) . "\n";

// Поиск и позиция
echo "<h2>Поиск подстроки</h2>";
$pos = strpos($text2, "PHP");
echo "Первое вхождение 'PHP' в \$text2 на позиции: $pos\n";
echo "Содержит 'JavaScript'? " . (str_contains($text2, 'JavaScript') ? 'да' : 'нет') . "\n";
$count = substr_count(mb_strtoupper($text2), "PHP");
echo "Количество вхождений 'PHP' (без учёта регистра): $count\n";

// 5. Извлечение части строки
echo "<h2>5. Извлечение подстроки</h2>";
$start = strpos($text1, "скриптовый");
$end = strpos($text1, "общего");
$extracted = substr($text1, $start, $end - $start);
echo "Вырезано: '$extracted'\n";
echo "Последние 10 символов: '" . mb_substr($text1, -10) . "'\n";

// 6. Замена части строки
echo "<h2>6. Замена</h2>";
$replaced = str_replace("PHP", "РНР", $text2);
echo "Замена PHP на РНР: $replaced\n";
$noDots = str_replace(".", "", $text1);
echo "Удалены точки: $noDots\n";

// 7. Удаление пробелов
echo "<h2>7. Удаление пробелов</h2>";
$trimmed = trim($price);
echo "Очищенная цена: '$trimmed'\n";
$cleanNumber = str_replace([" руб.", " ", ","], ["", "", "."], $trimmed);
$floatPrice = (float)$cleanNumber;
echo "Число float: $floatPrice\n";

// 8. Изменение регистра
echo "<h2>8. Изменение регистра</h2>";
echo "Нижний регистр: " . mb_strtolower($slugSource) . "\n";
echo "Верхний регистр: " . mb_strtoupper($slugSource) . "\n";
echo "Заглавные буквы слов: " . mb_convert_case($slugSource, MB_CASE_TITLE) . "\n";

// 9. Разбиение и объединение
echo "<h2>9. Разбиение и объединение</h2>";
$csvArr = explode(";", $csvLine);
echo "Фамилия: {$csvArr[0]}, Имя: {$csvArr[1]}, Email: {$csvArr[2]}\n";
$rejoined = implode("|", $csvArr);
echo "Склеено через |: $rejoined\n";
$chars = mb_str_split($slugSource);
echo "Символы slug: " . implode(", ", $chars) . "\n";

// 10. Безопасный вывод
echo "<h2>10. Безопасный вывод</h2>";
echo "htmlspecialchars: " . htmlspecialchars($userComment) . "\n";
echo "strip_tags: " . strip_tags($userComment) . "\n";
echo "Почему htmlspecialchars? — Предотвращает XSS-атаки, превращая теги в безопасные сущности.\n";

// 11. Форматирование (sprintf, number_format)
echo "<h2>11. Форматирование (sprintf, number_format)</h2>";
// Используем второго студента (Иван) с индексом 1
if (isset($students[1])) {
    $studentData = $students[1];
    $formattedStr = sprintf("Студент %s, возраст %d, оценка %.1f", $studentData['name'], $studentData['age'], $studentData['grade']);
    echo $formattedStr . "\n";
} else {
    echo "Студент не найден\n";
}
echo "number_format: " . number_format(12345.6789, 2, ',', ' ') . "\n";

echo "</pre></body></html>";
?>