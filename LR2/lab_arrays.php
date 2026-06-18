<?php
// Задание 2: Работа с массивами
mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><title>ЛР2 - Массивы</title></head><body>";
echo "<h1>Задание 2: Работа с массивами</h1><pre>";

// Исходные данные
$students = [
    ['name' => 'Анна', 'age' => 20, 'grade' => 4.5, 'city' => 'Минск'],
    ['name' => 'Иван', 'age' => 22, 'grade' => 3.8, 'city' => 'Гродно'],
    ['name' => 'Мария', 'age' => 19, 'grade' => 4.9, 'city' => 'Брест'],
    ['name' => 'Петр', 'age' => 21, 'grade' => 4.1, 'city' => 'Гродно'],
    ['name' => 'Елена', 'age' => 20, 'grade' => 4.7, 'city' => 'Минск'],
    ['name' => 'Алексей', 'age' => 23, 'grade' => 3.5, 'city' => 'Витебск']
];

$colors = ['red', 'green', 'blue', 'yellow', 'black', 'white'];
$capitals = [
    'Россия' => 'Москва',
    'Беларусь' => 'Минск',
    'Польша' => 'Варшава',
    'Германия' => 'Берлин'
];

//1. Инициализация и доступ
echo "<h2>1. Инициализация и доступ</h2>";
echo "Третий студент: " . $students[2]['name'] . "\n";
echo "Возраст первого: " . $students[0]['age'] . "\n";
echo "Оценка последнего: " . $students[5]['grade'] . "\n";

array_push($colors, 'purple');
$removed = array_shift($colors);
echo "Удалён первый элемент: $removed\n";

$capitals['Франция'] = 'Париж';
$capitals['Польша'] = 'Белосток (ошибочно)';
$capitals['Польша'] = 'Варшава';
echo "Столицы после изменений: " . print_r($capitals, true);

// 2. Перебор и foreach 
echo "<h2>2. Перебор и foreach</h2>";
echo "Имена студентов: ";
foreach ($students as $student) {
    echo $student['name'] . " ";
}
echo "\n";

foreach ($capitals as $country => $capital) {
    echo "Столица $country — $capital\n";
}

foreach ($colors as &$color) {
    $color = strtoupper($color);
}
unset($color);
echo "Цвета в верхнем регистре: " . implode(", ", $colors) . "\n";

//3. Сортировка
echo "<h2>3. Сортировка</h2>";
$sortedColors = $colors;
sort($sortedColors);
echo "Цвета по алфавиту: " . implode(", ", $sortedColors) . "\n";

$ages = array_column($students, 'age');
arsort($ages);
echo "Массив возрастов по убыванию (сохранение ключей): " . implode(", ", $ages) . "\n";

ksort($capitals);
echo "Страны по алфавиту: " . print_r($capitals, true);

// 4. Функции поиска 
echo "<h2>4. Поиск и проверка</h2>";
if (!in_array('orange', $colors)) {
    $colors[] = 'orange';
    echo "Добавлен цвет 'orange'\n";
}
echo "Есть ли ключ 'grade' у первого студента? " . (array_key_exists('grade', $students[0]) ? 'да' : 'нет') . "\n";
$yellowKey = array_search('YELLOW', $colors);
echo "Индекс 'yellow': " . ($yellowKey !== false ? $yellowKey : 'не найден') . "\n";

// 5. Работа с частью массива 
echo "<h2>5. array_slice / array_splice</h2>";
$firstThree = array_slice($colors, 0, 3);
echo "Первые 3 цвета: " . implode(", ", $firstThree) . "\n";

$removedStudent = array_splice($students, 1, 1);
echo "Удалённый студент: " . print_r($removedStudent, true);
$merged = array_merge($colors, ['pink', 'brown']);
echo "Объединённый массив: " . implode(", ", $merged) . "\n";

//6. Преобразование
echo "<h2>6. Преобразование массивов</h2>";
$allAges = array_column($students, 'age');
echo "Возраста студентов: " . implode(", ", $allAges) . "\n";

$colorLengths = array_combine($colors, array_map('strlen', $colors));
echo "Цвет => длина: " . print_r($colorLengths, true);

echo "Ключи capitals: " . implode(", ", array_keys($capitals)) . "\n";
echo "Значения capitals: " . implode(", ", array_values($capitals)) . "\n";

// 7. Функции высшего порядка 
echo "<h2>7. array_filter, array_map, array_reduce</h2>";
$adultStudents = array_filter($students, fn($s) => $s['age'] >= 21);
echo "Студенты >=21 лет: " . print_r(array_column($adultStudents, 'name'), true);

$formatted = array_map(fn($s) => "{$s['name']} ({$s['age']} лет)", $students);
echo "Формат: " . implode(", ", $formatted) . "\n";

$avgGrade = array_reduce($students, fn($carry, $s) => $carry + $s['grade'], 0) / count($students);
echo "Средний балл: " . round($avgGrade, 2) . "\n";

//  Случайные элементы 
echo "<h2>Случайные элементы</h2>";
$randomKeys = array_rand($colors, 2);
echo "2 случайных цвета: " . $colors[$randomKeys[0]] . ", " . $colors[$randomKeys[1]] . "\n";
shuffle($colors);
echo "Перемешанный массив цветов: " . implode(", ", $colors) . "\n";

echo "</pre></body></html>";
?>