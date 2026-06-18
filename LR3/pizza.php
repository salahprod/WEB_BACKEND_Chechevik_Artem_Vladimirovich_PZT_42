<?php
/**
 * Задание 4: Форма заказа пиццы
 * Обработка радиокнопок, чекбоксов, выпадающего списка и textarea
 */
// Цены на пиццу в зависимости от размера
$pizzaPrices = [
    'small' => 250,
    'medium' => 350,
    'large' => 450
];

// Доступные топпинги
$availableToppings = [
    'cheese' => 'Сыр',
    'mushrooms' => 'Грибы',
    'sausage' => 'Колбаса',
    'olives' => 'Оливки'
];

$orderProcessed = false;
$orderData = [];

// Обработка отправленной формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderProcessed = true;
    
    // Получаем размер пиццы (безопасно выводим позже)
    $orderData['size'] = $_POST['size'] ?? '';
    $orderData['sizePrice'] = $pizzaPrices[$orderData['size']] ?? 0;
    
    // Получаем массив выбранных топпингов
    $orderData['toppings'] = $_POST['toppings'] ?? [];
    
    // Получаем комментарий
    $orderData['comment'] = trim($_POST['comment'] ?? '');
    
    // Получаем способ доставки
    $orderData['delivery'] = $_POST['delivery'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказ пиццы</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        form { width: 400px; }
        input, select, textarea, button { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        .radio-group, .checkbox-group { margin: 5px 0; }
        .radio-group label, .checkbox-group label { display: inline-block; width: auto; margin-right: 15px; }
        .radio-group input, .checkbox-group input { width: auto; margin-right: 5px; }
        button { background: orange; color: white; border: none; cursor: pointer; font-size: 16px; }
        .order-summary { background: #f0f0f0; padding: 15px; border-radius: 5px; margin-top: 20px; }
        hr { margin: 20px 0; }
    </style>
</head>
<body>
    <h2>🍕 Заказ пиццы</h2>
    
    <form method="POST" action="">
        <h3>Выберите размер пиццы:</h3>
        <div class="radio-group">
            <label><input type="radio" name="size" value="small" <?php echo (!isset($_POST['size']) || $_POST['size'] == 'small') ? 'checked' : ''; ?>> Маленькая (250 руб.)</label>
            <label><input type="radio" name="size" value="medium" <?php echo (isset($_POST['size']) && $_POST['size'] == 'medium') ? 'checked' : ''; ?>> Средняя (350 руб.)</label>
            <label><input type="radio" name="size" value="large" <?php echo (isset($_POST['size']) && $_POST['size'] == 'large') ? 'checked' : ''; ?>> Большая (450 руб.)</label>
        </div>
        
        <h3>Добавьте топпинги:</h3>
        <div class="checkbox-group">
            <?php foreach ($availableToppings as $value => $label): ?>
                <label>
                    <input type="checkbox" name="toppings[]" value="<?php echo $value; ?>"
                        <?php echo (isset($_POST['toppings']) && in_array($value, $_POST['toppings'])) ? 'checked' : ''; ?>>
                    <?php echo $label; ?>
                </label>
            <?php endforeach; ?>
        </div>
        
        <h3>Комментарий к заказу:</h3>
        <textarea name="comment" rows="3" placeholder="Например: без лука, побольше сыра..."><?php echo htmlspecialchars($_POST['comment'] ?? '', ENT_QUOTES); ?></textarea>
        
        <h3>Способ доставки:</h3>
        <select name="delivery">
            <option value="pickup" <?php echo (isset($_POST['delivery']) && $_POST['delivery'] == 'pickup') ? 'selected' : ''; ?>>Самовывоз</option>
            <option value="courier" <?php echo (isset($_POST['delivery']) && $_POST['delivery'] == 'courier') ? 'selected' : ''; ?>>Курьером</option>
        </select>
        
        <button type="submit">Оформить заказ</button>
    </form>
    
    <?php if ($orderProcessed): ?>
        <div class="order-summary">
            <h3>📋 Ваш заказ:</h3>
            
            <?php if (empty($orderData['size'])): ?>
                <p style="color:red;">Ошибка: Размер пиццы не выбран</p>
            <?php else: ?>
                <p><strong>Размер пиццы:</strong> 
                    <?php 
                    $sizeNames = ['small' => 'Маленькая', 'medium' => 'Средняя', 'large' => 'Большая'];
                    echo htmlspecialchars($sizeNames[$orderData['size']]); 
                    ?> (<?php echo $orderData['sizePrice']; ?> руб.)
                </p>
                
                <p><strong>Топпинги:</strong>
                    <?php if (empty($orderData['toppings'])): ?>
                        <em>Не выбраны</em>
                    <?php else: ?>
                        <?php 
                        $selectedToppings = [];
                        foreach ($orderData['toppings'] as $topping) {
                            if (isset($availableToppings[$topping])) {
                                $selectedToppings[] = htmlspecialchars($availableToppings[$topping]);
                            }
                        }
                        echo implode(', ', $selectedToppings);
                        ?>
                    <?php endif; ?>
                </p>
                
                <p><strong>Комментарий:</strong> 
                    <?php echo empty($orderData['comment']) ? '<em>Нет</em>' : htmlspecialchars($orderData['comment']); ?>
                </p>
                
                <p><strong>Способ доставки:</strong>
                    <?php echo $orderData['delivery'] == 'pickup' ? 'Самовывоз' : 'Курьером'; ?>
                </p>
                
                <hr>
                <p><strong>Итого к оплате:</strong> <?php echo $orderData['sizePrice']; ?> руб.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>