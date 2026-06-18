-- install.sql - Создание базы данных и таблиц
CREATE DATABASE IF NOT EXISTS car_musc_db;
USE car_musc_db;

-- Таблица услуг (каталог)
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица новостей/контента
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица заказов
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_email VARCHAR(255),
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('new', 'processing', 'completed', 'cancelled') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица элементов заказа
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    service_id INT NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Вставка услуг
INSERT INTO services (name, category, description, price, image) VALUES
('Защитная пленка (полный кузов)', 'Оклейка', 'Полная оклейка кузова защитной пленкой. Защита от сколов и царапин.', 150000.00, 'Image/Car1.jpg'),
('Защитная пленка (капот+крылья)', 'Оклейка', 'Защита передней части автомобиля от камней и насекомых.', 45000.00, 'Image/Car2.jpg'),
('Цветная пленка (матовый черный)', 'Оклейка', 'Полная оклейка цветной пленкой с матовым эффектом.', 120000.00, 'Image/Car3.jpg'),
('Цветная пленка (глянцевый)', 'Оклейка', 'Полная оклейка глянцевой цветной пленкой.', 110000.00, 'Image/Car4.png'),
('Антигравийная пленка (полная)', 'Защита', 'Максимальная защита кузова антигравийной пленкой.', 180000.00, 'Image/Car5.png'),
('Оклейка салона', 'Детейлинг', 'Защита элементов салона пленкой.', 35000.00, 'Image/w4.png'),
('Полировка кузова', 'Детейлинг', 'Восстановление блеска и удаление царапин.', 25000.00, 'Image/w1.png'),
('Керамическое покрытие', 'Детейлинг', 'Гидрофобное покрытие для защиты ЛКП.', 40000.00, 'Image/w2.png'),
('Химчистка салона', 'Детейлинг', 'Глубокая чистка салона автомобиля.', 12000.00, 'Image/w3.png'),
('Оклейка крыши', 'Оклейка', 'Оклейка крыши черной пленкой.', 25000.00, 'Image/w5.png'),
('Оклейка фар', 'Оклейка', 'Защита фар бронепленкой.', 8000.00, 'Image/w6.png'),
('Бронирование стекол', 'Защита', 'Защита стекол от сколов.', 30000.00, 'Image/Car1.jpg');

-- Вставка новостей
INSERT INTO news (title, content, image) VALUES
('Новая антигравийная пленка!', 'Мы запустили новую линейку антигравийных пленок с улучшенными характеристиками. Теперь защита вашего авто еще надежнее!', 'Image/Car1.jpg'),
('Скидка 20% на комплексную оклейку', 'При заказе оклейки всего кузова защитной пленкой - скидка 20%. Акция действует до конца месяца.', 'Image/Car2.jpg'),
('Открытие нового центра детейлинга', 'Мы открыли современный центр детейлинга с профессиональным оборудованием. Записывайтесь уже сегодня!', 'Image/Car3.jpg'),
('Как правильно ухаживать за пленкой?', 'Полезные советы по уходу за защитной пленкой, чтобы она служила вам долго.', 'Image/Car4.png');