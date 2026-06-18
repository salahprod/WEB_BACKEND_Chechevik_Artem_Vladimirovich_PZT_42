<?php
// Лабораторная работа №2
// Вариант 1
// Задание 1: Синтаксис PHP

mb_internal_encoding('UTF-8');
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><title>ЛР2 - Синтаксис PHP</title></head><body>";
echo "<h1>Задание 1: Синтаксис PHP</h1>";

// Базовые переменные
$integerVar = 42;
$floatVar = 3.14159;
$stringVar = "100 рублей";
$boolVar = true;
$nullVar = null;
$name = "Иван Петров";
$age = 22;
$city = "Гродно";
$price = 1499.99;
$discountPercent = 15;
$counter = 5;
$fruits = ["яблоко", "банан", "апельсин"];
$user = ["login" => "john", "role" => "admin", "active" => true];

// Константы
define("TAX_RATE", 0.2);
const COMPANY = "ООО «Ромашка»";

$dateString = "2025-04-10";

echo "<pre>";

// 1. Переменные, константы и типы данных
echo "<h2>1. Переменные, константы и типы данных</h2>";

echo "<h3>Переменные:</h3>";
echo "\$integerVar = $integerVar\n";
echo "\$floatVar = $floatVar\n";
echo "\$stringVar = $stringVar\n";
echo "\$boolVar = " . ($boolVar ? 'true' : 'false') . "\n";
echo "\$nullVar = " . var_export($nullVar, true) . "\n";

echo "<h3>Типы переменных (gettype):</h3>";
echo "\$integerVar: " . gettype($integerVar) . "\n";
echo "\$floatVar: " . gettype($floatVar) . "\n";
echo "\$stringVar: " . gettype($stringVar) . "\n";
echo "\$boolVar: " . gettype($boolVar) . "\n";
echo "\$nullVar: " . gettype($nullVar) . "\n";

echo "<h3>Проверка isset:</h3>";
echo "\$undefinedVar существует? " . (isset($undefinedVar) ? 'да' : 'нет') . " (переменная не определена)\n";

unset($floatVar);
echo "\$floatVar после unset(): " . (isset($floatVar) ? 'существует' : 'НЕ существует') . "\n";

echo "<h3>Константы:</h3>";
echo "TAX_RATE = " . TAX_RATE . "\n";
echo "COMPANY = " . COMPANY . "\n";
echo "<i>Попытка переопределить TAX_RATE вызывает предупреждение</i>\n";
echo "TAX_RATE определена? " . (defined('TAX_RATE') ? 'да' : 'нет') . "\n";
echo "__LINE__ = " . __LINE__ . "\n";
echo "__FILE__ = " . __FILE__ . "\n";

echo "<h3>Приведение типов:</h3>";
$intCast = (int)$stringVar;
$intImplicit = $stringVar + 0;
echo "(int) '$stringVar' = $intCast, тип: " . gettype($intCast) . "\n";
echo "'$stringVar' + 0 = $intImplicit, тип: " . gettype($intImplicit) . "\n";

$floatToStr = (string)$floatVar;
echo "(string) \$floatVar = '$floatToStr', тип: " . gettype($floatToStr) . "\n";

echo "0 == false -> " . ((0 == false) ? 'true' : 'false') . "\n";
echo "0 === false -> " . ((0 === false) ? 'true' : 'false') . "\n";

$strAdd = "10 лет" + 5;
echo "'10 лет' + 5 = $strAdd\n";

// 2. Операции
echo "<h2>2. Операции и приоритет</h2>";

echo "<h3>Арифметика:</h3>";
echo "\$integerVar + \$floatVar = " . ($integerVar + $floatVar) . "\n";
echo "\$integerVar % 7 = " . ($integerVar % 7) . "\n";
echo "\$integerVar ** 3 = " . ($integerVar ** 3) . "\n";

$counter = 5;
echo "Постфиксный: " . $counter++ . " (после: $counter)\n";
echo "Префиксный: " . ++$counter . " (после: $counter)\n";

$i = 5;
$a = $i++;
echo "\$i = 5; \$a = \$i++; a=$a, i=$i\n";
$i = 5;
$b = ++$i;
echo "\$i = 5; \$b = ++\$i; b=$b, i=$i\n";

echo "<h3>Строковые операции:</h3>";
$info = "Имя: " . $name . ", возраст: " . $age . ", город: " . $city;
echo $info . "\n";
$info .= " (студент)";
echo $info . "\n";

echo "<h3>Сравнения:</h3>";
echo "42 == '100 рублей'? " . (($integerVar == $stringVar) ? 'true' : 'false') . "\n";
echo "42 === '100 рублей'? " . (($integerVar === $stringVar) ? 'true' : 'false') . "\n";
echo "42 <=> 25 -> " . (42 <=> 25) . "\n";
echo "(\$age > 18 && \$city == 'Гродно')? " . (($age > 18 && $city == 'Гродно') ? 'true' : 'false') . "\n";
echo "\$user['role'] == 'admin' || \$user['active']? " . (($user['role'] == 'admin' || $user['active']) ? 'true' : 'false') . "\n";

echo "<h3>Приоритет:</h3>";
echo "2 + 3 * 4 - 1 = " . (2 + 3 * 4 - 1) . "\n";
echo "(2 + 3) * (4 - 1) = " . ((2 + 3) * (4 - 1)) . "\n";

// 3. Операторы управления
echo "<h2>3. Операторы управления</h2>";

echo "<h3>Условные операторы:</h3>";
if ($age < 18) {
    $category = "ребёнок";
} elseif ($age <= 35) {
    $category = "молодой";
} elseif ($age <= 60) {
    $category = "взрослый";
} else {
    $category = "пенсионер";
}
echo "Возраст $age: $category\n";

$categoryMatch = match(true) {
    $age < 18 => "ребёнок",
    $age <= 35 => "молодой",
    $age <= 60 => "взрослый",
    default => "пенсионер"
};
echo "Match: $categoryMatch\n";

$access = $boolVar ? "разрешён" : "запрещён";
echo "Доступ: $access\n";

echo "<h3>Циклы:</h3>";
echo "while (1-5): ";
$w = 1;
while ($w <= 5) { echo $w++; } echo "\n";

echo "for (чётные 0-10): ";
for ($j = 0; $j <= 10; $j+=2) { echo "$j "; } echo "\n";

echo "foreach fruits: \n";
foreach ($fruits as $key => $value) {
    echo "  [$key] => $value\n";
}

echo "foreach по ссылке: ";
foreach ($fruits as &$fruit) {
    $fruit .= "!";
}
unset($fruit);
print_r($fruits);

echo "<h3>continue/break:</h3>";
echo "continue (пропуск 5): ";
for ($i = 1; $i <= 10; $i++) {
    if ($i == 5) continue;
    echo "$i ";
}
echo "\nbreak (остановка на 8): ";
for ($i = 1; $i <= 10; $i++) {
    if ($i == 8) break;
    echo "$i ";
}
echo "\n";

echo "<h3>Включение файлов:</h3>";
require_once 'config.php';
echo "DB_HOST = " . DB_HOST . "\n";

// 4. Пользовательские функции
echo "<h2>4. Пользовательские функции</h2>";

function greet($name) {
    return "Привет, $name!";
}
echo greet($name) . "\n";

function calculateDiscount($price, $percent = 10) {
    return $price * (100 - $percent) / 100;
}
echo "Скидка 15%: " . calculateDiscount($price, 15) . "\n";
echo "Скидка 10%: " . calculateDiscount($price) . "\n";

function sumAll(...$numbers) {
    return array_sum($numbers);
}
echo "sumAll(1,2,3,4,5) = " . sumAll(1,2,3,4,5) . "\n";

echo "<h3>Стрелочные функции:</h3>";
$numbersArr = [1,2,3,4,5];
$squares = array_map(fn($n) => $n**2, $numbersArr);
echo "Квадраты: " . implode(", ", $squares) . "\n";

$evenFilter = array_filter($numbersArr, fn($n) => $n % 2 == 0);
echo "Чётные: " . implode(", ", $evenFilter) . "\n";

echo "<h3>Оператор return:</h3>";
function divide($a, $b) {
    if ($b == 0) return null;
    return $a / $b;
}
echo "divide(10,2) = " . divide(10,2) . "\n";
echo "divide(10,0) = " . var_export(divide(10,0), true) . "\n";

function noReturn() {}
echo "Функция без return: " . var_export(noReturn(), true) . "\n";

// 5. Математические функции
echo "<h2>5. Математические функции</h2>";
echo "abs(-15) = " . abs(-15) . "\n";
echo "ceil(4.7) = " . ceil(4.7) . "\n";
echo "floor(4.7) = " . floor(4.7) . "\n";
echo "round(3.14159, 2) = " . round(3.14159, 2) . "\n";
echo "rand(1,100) = " . rand(1,100) . "\n";
echo "max(34,67,12,89,5) = " . max(34,67,12,89,5) . "\n";
echo "min(34,67,12,89,5) = " . min(34,67,12,89,5) . "\n";

// 6. Функции даты и времени
echo "<h2>6. Функции даты и времени</h2>";
date_default_timezone_set("Europe/Moscow");

echo "Текущий timestamp: " . time() . "\n";
echo "Текущая дата: " . date("d.m.Y H:i:s") . "\n";

$timestampNewYear = mktime(0,0,0,1,1,2026);
echo "timestamp для 2026-01-01: " . $timestampNewYear . "\n";

echo "Следующий понедельник: " . date("Y-m-d", strtotime("next monday")) . "\n";

$dt = new DateTime($dateString);
$dt->modify('+2 weeks');
echo "Дата +2 недели: " . $dt->format('Y-m-d') . "\n";

$today = new DateTime();
$futureDate = new DateTime("2026-02-11");
$diff = $today->diff($futureDate);
echo "Дней до 2026-02-11: " . $diff->days . "\n";

echo "</pre></body></html>";
?>