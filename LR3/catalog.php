<?php
/**
 * Задание 5: Фильтрация товаров через GET-параметры
 * Реализована фильтрация по цене и категории
 */

// Массив товаров (минимум 7 товаров)
$products = [
    ['name' => 'Ноутбук', 'category' => 'электроника', 'price' => 55000],
    ['name' => 'Книга PHP', 'category' => 'книги', 'price' => 1200],
    ['name' => 'Мышь', 'category' => 'электроника', 'price' => 1500],
    ['name' => 'Книга JavaScript', 'category' => 'книги', 'price' => 1100],
    ['name' => 'Клавиатура', 'category' => 'электроника', 'price' => 2500],
    ['name' => 'Монитор', 'category' => 'электроника', 'price' => 18000],
    ['name' => 'Книга Python', 'category' => 'книги', 'price' => 1350],
    ['name' => 'Наушники', 'category' => 'электроника', 'price' => 3200],
    ['name' => 'Книга SQL', 'category' => 'книги', 'price' => 950]
];

// Получаем уникальные категории для выпадающего списка
$categories = array_unique(array_column($products, 'category'));
sort($categories);

// Функция для фильтрации товаров
function filterProducts($products, $minPrice, $maxPrice, $category) {
    $filtered = [];
    
    foreach ($products as $product) {
        // Фильтр по цене
        if ($minPrice !== null && $product['price'] < $minPrice) continue;
        if ($maxPrice !== null && $product['price'] > $maxPrice) continue;
        
        // Фильтр по категории
        if (!empty($category) && $product['category'] !== $category) continue;
        
        $filtered[] = $product;
    }
    
    return $filtered;
}

// Получаем параметры фильтрации из GET-запроса
$minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
$category = $_GET['category'] ?? '';

// Применяем фильтрацию
$filteredProducts = filterProducts($products, $minPrice, $maxPrice, $category);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог товаров</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        .filter-form { background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .filter-form input, .filter-form select, .filter-form button { padding: 8px; margin: 5px; }
        .filter-form button { background: blue; color: white; border: none; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
        .quick-links { margin-top: 20px; }
        .quick-links a { margin-right: 15px; }
    </style>
</head>
<body>
    <h2>🛒 Каталог товаров</h2>
    
    <!-- Форма фильтрации с GET-методом -->
    <div class="filter-form">
        <h3>Фильтр товаров</h3>
        <form method="GET" action="">
            <label>Цена от:</label>
            <input type="number" name="min_price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>" step="100">
            
            <label>до:</label>
            <input type="number" name="max_price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>" step="100">
            
            <label>Категория:</label>
            <select name="category">
                <option value="">Все категории</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" 
                        <?php echo ($category == $cat) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit">Применить фильтр</button>
        </form>
    </div>
    
    <!-- Быстрые ссылки для предустановленных фильтров -->
    <div class="quick-links">
        <strong>Быстрые фильтры:</strong>
        <a href="?min_price=1000">💰 Товары дороже 1000 руб.</a>
        <a href="?category=книги">📚 Книги</a>
        <a href="?category=электроника">💻 Электроника</a>
        <a href="?">Сбросить фильтр</a>
    </div>
    
    <!-- Таблица товаров -->
    <h3>Товары (<?php echo count($filteredProducts); ?> найденных)</h3>
    
    <?php if (empty($filteredProducts)): ?>
        <p style="color:red;">Товаров, соответствующих критериям, не найдено.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Цена (руб.)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredProducts as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo number_format($product['price'], 0, ',', ' '); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>