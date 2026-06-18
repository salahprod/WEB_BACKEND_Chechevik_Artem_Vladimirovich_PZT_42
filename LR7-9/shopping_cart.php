<?php
// shopping_cart.php - Страница корзины
require_once 'db_config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина - Car Musc</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .cart-page { padding: 120px 0 80px; }
        .cart-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .cart-table th, .cart-table td {
            padding: 18px;
            text-align: left;
            border-bottom: 1px solid #2a2a2a;
        }
        .cart-table th {
            color: #ff2a44;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .cart-item-name {
            font-weight: bold;
        }
        .cart-item-price, .cart-item-total {
            color: #ff2a44;
            font-weight: bold;
        }
        .quantity-input {
            width: 60px;
            padding: 8px;
            background: #1a1a1a;
            border: 1px solid #333;
            color: white;
            border-radius: 5px;
            text-align: center;
        }
        .remove-btn {
            background: none;
            border: none;
            color: #ff2a44;
            cursor: pointer;
            font-size: 20px;
            transition: transform 0.3s ease;
        }
        .remove-btn:hover { transform: scale(1.2); }
        .cart-summary {
            background: #1a1a1a;
            padding: 25px;
            border-radius: 15px;
            text-align: right;
            margin-top: 20px;
        }
        .cart-total-label {
            font-size: 18px;
            color: #aaa;
        }
        .cart-total-amount {
            font-size: 32px;
            font-weight: bold;
            color: #ff2a44;
            margin-left: 20px;
        }
        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 20px;
            flex-wrap: wrap;
        }
        .btn-clear, .btn-checkout {
            padding: 15px 35px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-clear {
            background: transparent;
            border: 1px solid #ff2a44;
            color: white;
        }
        .btn-clear:hover {
            background: #ff2a44;
        }
        .btn-checkout {
            background: linear-gradient(to right, #ff2a44 50%, #1a1a1a 50%);
            background-size: 200% 100%;
            background-position: left center;
            color: white;
        }
        .btn-checkout:hover {
            background-position: right center;
            transform: translateY(-3px);
        }
        .empty-cart {
            text-align: center;
            padding: 60px;
            background: #1a1a1a;
            border-radius: 15px;
        }
        .empty-cart h3 { margin-bottom: 20px; color: #aaa; }
        .empty-cart a {
            color: #ff2a44;
            text-decoration: none;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .cart-table th, .cart-table td { padding: 10px; font-size: 12px; }
            .cart-total-amount { font-size: 24px; }
            .cart-actions { flex-direction: column; }
            .btn-clear, .btn-checkout { width: 100%; text-align: center; }
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
                <a href="shopping_cart.php" class="nav__link active">Корзина</a>
                <a href="#contacts" class="nav__link">Контакты</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="cart-page">
            <div class="container cart-container">
                <h1 style="text-align: center; margin-bottom: 40px; color: #ff2a44;">КОРЗИНА</h1>
                
                <div id="cartContent">
                    <div class="loading-spinner" style="text-align:center; padding:50px;">Загрузка...</div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container footer__wrapper">
            <p class="footer__copy">2026 © CAR MUSC - Профессиональная оклейка автомобилей</p>
        </div>
    </footer>

    <script>
        function loadCart() {
            fetch('api_cart_handler.php?action=get')
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.items.length > 0) {
                        renderCart(data);
                    } else {
                        renderEmptyCart();
                    }
                });
        }
        
        function renderCart(data) {
            const container = document.getElementById('cartContent');
            let itemsHtml = `
                <table class="cart-table">
                    <thead>
                        <tr><th>Услуга</th><th>Цена</th><th>Кол-во</th><th>Сумма</th><th></th></tr>
                    </thead>
                    <tbody>
            `;
            
            data.items.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td class="cart-item-name">${escapeHtml(item.name)}</td>
                        <td class="cart-item-price">${item.price_formatted}</td>
                        <td><input type="number" class="quantity-input" value="${item.quantity}" min="1" onchange="updateQuantity(${item.id}, this.value)"></td>
                        <td class="cart-item-total">${item.subtotal_formatted}</td>
                        <td><button class="remove-btn" onclick="removeFromCart(${item.id})">✕</button></td>
                    </tr>
                `;
            });
            
            itemsHtml += `
                    </tbody>
                </table>
                <div class="cart-summary">
                    <span class="cart-total-label">ИТОГО:</span>
                    <span class="cart-total-amount">${data.total_formatted}</span>
                </div>
                <div class="cart-actions">
                    <button class="btn-clear" onclick="clearCart()">ОЧИСТИТЬ КОРЗИНУ</button>
                    <a href="order_checkout.php" class="btn-checkout">ОФОРМИТЬ ЗАКАЗ →</a>
                </div>
            `;
            
            container.innerHTML = itemsHtml;
        }
        
        function renderEmptyCart() {
            const container = document.getElementById('cartContent');
            container.innerHTML = `
                <div class="empty-cart">
                    <h3>🛒 ВАША КОРЗИНА ПУСТА</h3>
                    <p><a href="shop_catalog.php">Перейти в каталог услуг</a></p>
                </div>
            `;
        }
        
        function escapeHtml(str) {
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
        
        function updateQuantity(serviceId, quantity) {
            fetch('api_cart_handler.php?action=update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ service_id: serviceId, quantity: parseInt(quantity) })
            })
            .then(res => res.json())
            .then(() => loadCart());
        }
        
        function removeFromCart(serviceId) {
            fetch('api_cart_handler.php?action=remove', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ service_id: serviceId })
            })
            .then(res => res.json())
            .then(() => loadCart());
        }
        
        function clearCart() {
            if (confirm('Очистить всю корзину?')) {
                fetch('api_cart_handler.php?action=clear', { method: 'DELETE' })
                    .then(res => res.json())
                    .then(() => loadCart());
            }
        }
        
        loadCart();
    </script>
</body>
</html>