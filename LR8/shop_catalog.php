<?php
// shop_catalog.php - Каталог услуг с поиском, фильтрацией и пагинацией
require_once 'db_config.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог услуг - Car Musc</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .catalog-page { padding: 120px 0 80px; }
        .filters-panel {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 40px;
            padding: 25px;
            background: #1a1a1a;
            border-radius: 15px;
            justify-content: space-between;
            align-items: flex-end;
        }
        .filter-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .filter-field label {
            font-size: 11px;
            color: #ff2a44;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .filter-field select, .filter-field input {
            padding: 12px 18px;
            background: #121212;
            border: 1px solid #333;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
        }
        .filter-field select:focus, .filter-field input:focus {
            border-color: #ff2a44;
            outline: none;
        }
        .search-box { 
            flex-grow: 1; 
            min-width: 250px;
        }
        .search-box input { 
            width: 100%; 
        }
        .search-button {
            background: #ff2a44;
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            transition: all 0.3s ease;
            margin-top: 24px;
        }
        .search-button:hover {
            background: #d61f37;
            transform: scale(1.02);
        }
        .price-range-box { display: flex; gap: 10px; }
        .price-range-box input { width: 110px; }
        .catalog-items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        .product-card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-8px);
            border-color: #ff2a44;
            box-shadow: 0 15px 35px rgba(255, 42, 68, 0.15);
        }
        .product-card img {
            width: 100%;
            height: 210px;
            object-fit: cover;
        }
        .product-info {
            padding: 22px;
        }
        .product-title {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 8px;
            color: white;
        }
        .product-category {
            font-size: 10px;
            color: #ff2a44;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .product-desc {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 18px;
            line-height: 1.5;
        }
        .product-price {
            font-size: 24px;
            font-weight: bold;
            color: #ff2a44;
            margin-bottom: 18px;
        }
        .product-actions {
            display: flex;
            gap: 12px;
        }
        .btn-add-cart {
            flex: 1;
            padding: 12px;
            text-align: center;
            background: linear-gradient(to right, #ff2a44 50%, #252525 50%);
            background-size: 200% 100%;
            background-position: left center;
            color: white;
            border: none;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        .btn-add-cart:hover {
            background-position: right center;
            transform: scale(1.02);
        }
        .btn-details {
            padding: 12px 18px;
            background: transparent;
            border: 1px solid #ff2a44;
            color: white;
            text-decoration: none;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-align: center;
        }
        .btn-details:hover {
            background: #ff2a44;
        }
        .pagination-links {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .pagination-links a, .pagination-links span {
            padding: 10px 16px;
            background: #1a1a1a;
            border: 1px solid #333;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .pagination-links a:hover, .pagination-links .current-page {
            background: #ff2a44;
            border-color: #ff2a44;
        }
        .cart-fixed {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #ff2a44;
            color: white;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            z-index: 1000;
            box-shadow: 0 5px 25px rgba(255, 42, 68, 0.5);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 16px;
        }
        .cart-fixed:hover { transform: scale(1.08); color: white; }
        .cart-badge {
            background: white;
            color: #ff2a44;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: bold;
        }
        .loading-spinner {
            text-align: center;
            padding: 50px;
            color: #aaa;
            grid-column: 1 / -1;
        }
        .search-results-info {
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #1a1a1a;
            border-radius: 10px;
            display: inline-block;
        }
        .search-results-info span {
            color: #ff2a44;
            font-weight: bold;
        }
        .reset-search {
            background: transparent;
            border: 1px solid #ff2a44;
            color: #ff2a44;
            padding: 5px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 11px;
            margin-left: 10px;
            transition: all 0.3s ease;
        }
        .reset-search:hover {
            background: #ff2a44;
            color: white;
        }
        @media (max-width: 768px) {
            .filters-panel { flex-direction: column; align-items: stretch; }
            .price-range-box { flex-wrap: wrap; }
            .price-range-box input { width: 100%; }
            .catalog-items-grid { grid-template-columns: 1fr; }
            .cart-fixed { padding: 10px 20px; font-size: 14px; }
            .search-button { margin-top: 0; width: 100%; }
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
                <a href="shop_catalog.php" class="nav__link active">Каталог услуг</a>
                <a href="shopping_cart.php" class="nav__link">Корзина</a>
                <a href="#contacts" class="nav__link">Контакты</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="catalog-page">
            <div class="container">
                <h1 style="text-align: center; margin-bottom: 40px; color: #ff2a44;">КАТАЛОГ УСЛУГ</h1>
                
                <div class="filters-panel">
                    <div class="filter-field search-box">
                        <label>🔍 ПОИСК ПО НАЗВАНИЮ</label>
                        <input type="text" id="searchInput" placeholder="Например: защитная пленка, полировка...">
                    </div>
                    
                    <div class="filter-field">
                        <label>📂 КАТЕГОРИЯ</label>
                        <select id="categorySelect">
                            <option value="all">Все категории</option>
                        </select>
                    </div>
                    
                    <div class="filter-field">
                        <label>🔄 СОРТИРОВКА</label>
                        <select id="sortSelect">
                            <option value="name_asc">По названию (А-Я)</option>
                            <option value="name_desc">По названию (Я-А)</option>
                            <option value="price_asc">По цене (сначала дешевле)</option>
                            <option value="price_desc">По цене (сначала дороже)</option>
                        </select>
                    </div>
                    
                    <div class="filter-field">
                        <label>💰 ЦЕНА</label>
                        <div class="price-range-box">
                            <input type="number" id="minPrice" placeholder="от" value="0">
                            <input type="number" id="maxPrice" placeholder="до" value="999999">
                        </div>
                    </div>
                    
                    <button id="searchBtn" class="search-button">🔍 НАЙТИ</button>
                </div>
                
                <div id="searchResultsInfo" class="search-results-info" style="display: none;"></div>
                
                <div id="servicesContainer" class="catalog-items-grid">
                    <div class="loading-spinner">Загрузка услуг...</div>
                </div>
                
                <div id="paginationContainer" class="pagination-links"></div>
            </div>
        </div>
        
        <a href="shopping_cart.php" class="cart-fixed">
            🛒 КОРЗИНА <span id="cartCount" class="cart-badge">0</span>
        </a>
    </main>

    <footer class="footer">
        <div class="container footer__wrapper">
            <p class="footer__copy">2026 © CAR MUSC - Профессиональная оклейка автомобилей</p>
        </div>
    </footer>

    <script>
        let currentPage = 1;
        let totalPages = 1;
        let currentSearch = '';
        let currentCategory = 'all';
        let currentSort = 'name_asc';
        let currentMinPrice = 0;
        let currentMaxPrice = 999999;
        
        function showToast(message, isError = false) {
            const existing = document.querySelector('.toast-message');
            if (existing) existing.remove();
            const toast = document.createElement('div');
            toast.className = 'toast-message';
            toast.style.background = isError ? '#ff2a44' : '#00c853';
            toast.innerHTML = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2500);
        }
        
        function updateCartCount() {
            fetch('api_cart_handler.php?action=get')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cartCount').innerText = data.count;
                    }
                })
                .catch(err => console.log('Ошибка:', err));
        }
        
        function addToCart(serviceId) {
            fetch('api_cart_handler.php?action=add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ service_id: serviceId, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCartCount();
                    showToast('✓ Добавлено в корзину!');
                } else {
                    showToast('✗ Ошибка при добавлении', true);
                }
            })
            .catch(() => showToast('✗ Ошибка соединения', true));
        }
        
        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }
        
        function loadServices() {
            const search = document.getElementById('searchInput').value;
            const category = document.getElementById('categorySelect').value;
            const sort = document.getElementById('sortSelect').value;
            const min_price = document.getElementById('minPrice').value || 0;
            const max_price = document.getElementById('maxPrice').value || 999999;
            
            currentSearch = search;
            currentCategory = category;
            currentSort = sort;
            currentMinPrice = min_price;
            currentMaxPrice = max_price;
            
            const container = document.getElementById('servicesContainer');
            container.innerHTML = '<div class="loading-spinner">Загрузка услуг...</div>';
            
            fetch(`api_services.php?page=${currentPage}&limit=9&search=${encodeURIComponent(search)}&category=${category}&sort=${sort}&min_price=${min_price}&max_price=${max_price}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        totalPages = data.total_pages;
                        
                        // Показать информацию о поиске
                        const infoDiv = document.getElementById('searchResultsInfo');
                        if (search && data.total > 0) {
                            infoDiv.style.display = 'inline-block';
                            infoDiv.innerHTML = `🔍 Результаты поиска "<span>${escapeHtml(search)}</span>": найдено ${data.total} услуг 
                                <button class="reset-search" onclick="resetSearch()">✕ Очистить</button>`;
                        } else if (search && data.total === 0) {
                            infoDiv.style.display = 'inline-block';
                            infoDiv.innerHTML = `🔍 По запросу "<span>${escapeHtml(search)}</span>" ничего не найдено 
                                <button class="reset-search" onclick="resetSearch()">✕ Очистить</button>`;
                        } else {
                            infoDiv.style.display = 'none';
                            infoDiv.innerHTML = '';
                        }
                        
                        renderServices(data.services);
                        renderPagination();
                    } else {
                        container.innerHTML = '<div class="loading-spinner">Ошибка загрузки данных</div>';
                    }
                })
                .catch(err => {
                    console.error('Ошибка:', err);
                    container.innerHTML = '<div class="loading-spinner">Ошибка загрузки услуг</div>';
                });
        }
        
        function resetSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('categorySelect').value = 'all';
            document.getElementById('sortSelect').value = 'name_asc';
            document.getElementById('minPrice').value = 0;
            document.getElementById('maxPrice').value = 999999;
            currentPage = 1;
            loadServices();
        }
        
        function renderServices(services) {
            const container = document.getElementById('servicesContainer');
            if (!services.length) {
                container.innerHTML = '<div class="loading-spinner">Ничего не найдено</div>';
                return;
            }
            
            container.innerHTML = services.map(service => `
                <div class="product-card">
                    <img src="${service.image || 'Image/Car1.jpg'}" alt="${escapeHtml(service.name)}">
                    <div class="product-info">
                        <div class="product-title">${escapeHtml(service.name)}</div>
                        <div class="product-category">${escapeHtml(service.category)}</div>
                        <div class="product-desc">${escapeHtml(service.description.substring(0, 100))}...</div>
                        <div class="product-price">${service.price_formatted}</div>
                        <div class="product-actions">
                            <button class="btn-add-cart" onclick="addToCart(${service.id})">В КОРЗИНУ</button>
                            <a href="#" class="btn-details">ПОДРОБНЕЕ</a>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        function renderPagination() {
            const container = document.getElementById('paginationContainer');
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }
            
            let html = '';
            if (currentPage > 1) {
                html += `<a href="#" onclick="goToPage(${currentPage - 1}); return false;">← Назад</a>`;
            }
            
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            
            if (startPage > 1) {
                html += `<a href="#" onclick="goToPage(1); return false;">1</a>`;
                if (startPage > 2) html += `<span>...</span>`;
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `<a href="#" onclick="goToPage(${i}); return false;" class="${i === currentPage ? 'current-page' : ''}">${i}</a>`;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += `<span>...</span>`;
                html += `<a href="#" onclick="goToPage(${totalPages}); return false;">${totalPages}</a>`;
            }
            
            if (currentPage < totalPages) {
                html += `<a href="#" onclick="goToPage(${currentPage + 1}); return false;">Вперед →</a>`;
            }
            
            container.innerHTML = html;
        }
        
        function goToPage(page) {
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            loadServices();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Загрузка категорий
        function loadCategories() {
            fetch('api_services.php?page=1&limit=1')
                .then(res => res.json())
                .then(data => {
                    if (data.categories && data.categories.length) {
                        const select = document.getElementById('categorySelect');
                        data.categories.forEach(cat => {
                            const option = document.createElement('option');
                            option.value = cat.category;
                            option.textContent = cat.category;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(err => console.log('Ошибка загрузки категорий:', err));
        }
        
        // Обработчики событий
        document.getElementById('searchBtn').addEventListener('click', function() {
            currentPage = 1;
            loadServices();
        });
        
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentPage = 1;
                loadServices();
            }
        });
        
        document.getElementById('categorySelect').addEventListener('change', function() {
            currentPage = 1;
            loadServices();
        });
        
        document.getElementById('sortSelect').addEventListener('change', function() {
            currentPage = 1;
            loadServices();
        });
        
        document.getElementById('minPrice').addEventListener('change', function() {
            currentPage = 1;
            loadServices();
        });
        
        document.getElementById('maxPrice').addEventListener('change', function() {
            currentPage = 1;
            loadServices();
        });
        
        // Инициализация
        updateCartCount();
        loadCategories();
        loadServices();
    </script>
</body>
</html>