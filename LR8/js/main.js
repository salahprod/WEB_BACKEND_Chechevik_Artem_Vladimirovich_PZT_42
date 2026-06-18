// js/main.js - JavaScript для главной страницы

// Функция для экранирования HTML
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Показ уведомления
function showToast(message, isError = false) {
    var existing = document.querySelector('.toast-message');
    if (existing) existing.remove();
    
    var toast = document.createElement('div');
    toast.className = 'toast-message';
    toast.style.background = isError ? '#ff2a44' : '#00c853';
    toast.innerHTML = message;
    document.body.appendChild(toast);
    
    setTimeout(function() {
        if (toast) toast.remove();
    }, 2500);
}

// Обновление счетчика корзины
function updateCartCount() {
    fetch('api_cart_handler.php?action=get')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                var badge = document.getElementById('cartCount');
                if (badge) badge.innerText = data.count;
            }
        })
        .catch(function(err) {
            console.log('Ошибка загрузки корзины:', err);
        });
}

// Добавление в корзину
function addToCart(serviceId) {
    fetch('api_cart_handler.php?action=add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ service_id: serviceId, quantity: 1 })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            updateCartCount();
            showToast('✓ Добавлено в корзину!');
        } else {
            showToast('✗ Ошибка при добавлении', true);
        }
    })
    .catch(function() {
        showToast('✗ Ошибка соединения', true);
    });
}

// Загрузка новостей
function loadNews() {
    var container = document.getElementById('newsContainer');
    if (!container) return;
    
    container.innerHTML = '<div class="loading-spinner">Загрузка новостей...</div>';
    
    fetch('api_news.php')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success && data.news && data.news.length > 0) {
                var html = '';
                for (var i = 0; i < data.news.length; i++) {
                    var news = data.news[i];
                    var date = '';
                    if (news.created_at) {
                        var d = new Date(news.created_at);
                        date = d.toLocaleDateString('ru-RU');
                    }
                    html += `
                        <div class="news-card">
                            <img src="${news.image || 'Image/Car1.jpg'}" alt="${escapeHtml(news.title)}">
                            <div class="news-content">
                                <div class="news-date">${date}</div>
                                <h3 class="news-title">${escapeHtml(news.title)}</h3>
                                <p class="news-text">${escapeHtml(news.content.substring(0, 120))}...</p>
                            </div>
                        </div>
                    `;
                }
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="loading-spinner">Новостей пока нет</div>';
            }
        })
        .catch(function(err) {
            console.error('Ошибка загрузки новостей:', err);
            container.innerHTML = '<div class="loading-spinner">Ошибка загрузки новостей</div>';
        });
}

// Загрузка превью услуг
function loadPreviewServices() {
    var container = document.getElementById('previewServices');
    if (!container) return;
    
    container.innerHTML = '<div class="loading-spinner">Загрузка услуг...</div>';
    
    fetch('api_services.php?page=1&limit=4')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success && data.services && data.services.length > 0) {
                var html = '';
                for (var i = 0; i < data.services.length; i++) {
                    var service = data.services[i];
                    html += `
                        <div class="service-preview-card">
                            <img src="${service.image || 'Image/Car1.jpg'}" alt="${escapeHtml(service.name)}">
                            <div class="service-preview-info">
                                <div class="service-preview-title">${escapeHtml(service.name)}</div>
                                <div class="service-preview-category">${escapeHtml(service.category)}</div>
                                <div class="service-preview-price">${service.price_formatted}</div>
                                <button class="service-preview-btn" onclick="addToCart(${service.id})">В КОРЗИНУ</button>
                            </div>
                        </div>
                    `;
                }
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="loading-spinner">Услуги временно недоступны</div>';
            }
        })
        .catch(function(err) {
            console.error('Ошибка загрузки услуг:', err);
            container.innerHTML = '<div class="loading-spinner">Ошибка загрузки услуг</div>';
        });
}

// Запуск при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    loadNews();
    loadPreviewServices();
});