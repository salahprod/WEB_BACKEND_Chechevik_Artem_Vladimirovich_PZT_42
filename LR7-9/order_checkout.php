<?php
// order_checkout.php - Оформление заказа
require_once 'db_config.php';

$pdo = getDB();

// Получение корзины
$cart_items = [];
$total = 0;
foreach ($_SESSION['shop_cart'] as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $cart_items[] = $item;
}

// Если корзина пуста
if (empty($cart_items)) {
    header('Location: shop_catalog.php');
    exit;
}

$order_success = false;
$order_number = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['customer_phone'] ?? '');
    $email = trim($_POST['customer_email'] ?? '');
    
    if (empty($name) || empty($phone)) {
        $error_msg = 'Заполните имя и телефон';
    } else {
        try {
            $pdo->beginTransaction();
            
            $order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $stmt = $pdo->prepare("INSERT INTO orders (order_number, customer_name, customer_phone, customer_email, total_amount, status) VALUES (?, ?, ?, ?, ?, 'new')");
            $stmt->execute([$order_number, $name, $phone, $email, $total]);
            $order_id = $pdo->lastInsertId();
            
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, service_id, service_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
            
            foreach ($cart_items as $item) {
                $stmt->execute([$order_id, $item['id'], $item['name'], $item['quantity'], $item['price']]);
            }
            
            $pdo->commit();
            
            $_SESSION['shop_cart'] = [];
            $order_success = true;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error_msg = 'Ошибка: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа - Car Musc</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .checkout-page { padding: 120px 0 80px; }
        .checkout-wrapper {
            max-width: 1100px;
            margin: 0 auto;
        }
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        .order-summary {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #2a2a2a;
        }
        .order-summary h3 {
            color: #ff2a44;
            margin-bottom: 20px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #2a2a2a;
        }
        .order-total {
            margin-top: 20px;
            padding-top: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: right;
            border-top: 2px solid #ff2a44;
        }
        .checkout-form h3 {
            color: #ff2a44;
            margin-bottom: 20px;
        }
        .checkout-form input {
            width: 100%;
            padding: 16px;
            margin-bottom: 20px;
            background: #1a1a1a;
            border: 1px solid #333;
            color: white;
            border-radius: 8px;
            font-family: inherit;
        }
        .checkout-form input:focus {
            border-color: #ff2a44;
            outline: none;
        }
        .submit-order {
            background: linear-gradient(to right, #ff2a44 50%, #1a1a1a 50%);
            background-size: 200% 100%;
            background-position: left center;
            color: white;
            border: none;
            padding: 16px 40px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            border-radius: 8px;
        }
        .submit-order:hover {
            background-position: right center;
            transform: translateY(-3px);
        }
        .success-block {
            text-align: center;
            padding: 60px;
            background: #1a1a1a;
            border-radius: 15px;
            border: 1px solid #00c853;
        }
        .success-block h2 {
            color: #00c853;
            margin-bottom: 20px;
        }
        .order-number-large {
            font-size: 32px;
            color: #ff2a44;
            margin: 20px 0;
        }
        .error-message {
            background: rgba(255, 0, 0, 0.2);
            border: 1px solid #ff0000;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: #ff6666;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #ff2a44;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .checkout-grid { grid-template-columns: 1fr; gap: 30px; }
            .checkout-page { padding: 100px 20px 50px; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <input type="checkbox" id="burger-toggle" class="burger-toggle">
            <label for="burger-toggle" class="burger-btn"><span></span><span></span><span></span></label>
            <nav class="nav">
                <a href="index.html" class="nav__link">Главная</a>
                <a href="shop_catalog.php" class="nav__link">Услуги</a>
                <a href="shopping_cart.php" class="nav__link">Корзина</a>
                <a href="#contacts" class="nav__link">Контакты</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="checkout-page">
            <div class="container checkout-wrapper">
                <?php if ($order_success): ?>
                    <div class="success-block">
                        <h2>✓ ЗАКАЗ УСПЕШНО ОФОРМЛЕН!</h2>
                        <p>Номер вашего заказа:</p>
                        <div class="order-number-large"><?php echo htmlspecialchars($order_number); ?></div>
                        <p>Наш менеджер свяжется с вами в ближайшее время.</p>
                        <a href="shop_catalog.php" class="back-link">← Вернуться в каталог</a>
                    </div>
                <?php else: ?>
                    <h1 style="text-align: center; margin-bottom: 40px; color: #ff2a44;">ОФОРМЛЕНИЕ ЗАКАЗА</h1>
                    
                    <?php if ($error_msg): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error_msg); ?></div>
                    <?php endif; ?>
                    
                    <div class="checkout-grid">
                        <div class="order-summary">
                            <h3>ВАШ ЗАКАЗ</h3>
                            <?php foreach ($cart_items as $item): ?>
                                <div class="order-item">
                                    <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                                    <span><?php echo number_format($item['price'] * $item['quantity'], 0, '.', ' '); ?> ₽</span>
                                </div>
                            <?php endforeach; ?>
                            <div class="order-total">
                                ИТОГО: <?php echo number_format($total, 0, '.', ' '); ?> ₽
                            </div>
                        </div>
                        
                        <div class="checkout-form">
                            <h3>КОНТАКТНЫЕ ДАННЫЕ</h3>
                            <form method="POST">
                                <input type="text" name="customer_name" placeholder="ВАШЕ ИМЯ *" required>
                                <input type="tel" name="customer_phone" placeholder="ТЕЛЕФОН *" required>
                                <input type="email" name="customer_email" placeholder="EMAIL (необязательно)">
                                <button type="submit" class="submit-order">ПОДТВЕРДИТЬ ЗАКАЗ</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container footer__wrapper">
            <p class="footer__copy">2026 © CAR MUSC - Профессиональная оклейка автомобилей</p>
        </div>
    </footer>
</body>
</html>