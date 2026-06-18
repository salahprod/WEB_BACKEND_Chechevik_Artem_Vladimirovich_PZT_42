<?php
// ============================================================================
// ЗАДАНИЕ №2: ГОСТЕВАЯ КНИГА
// Функциональный элемент с записью и чтением данных из файла
// ============================================================================

$guestbook_file = 'guestbook_data.txt';
$error_message = '';
$success_message = '';

// Функция для сохранения записи в файл (JSON формат)
function saveGuestEntry($entry) {
    global $guestbook_file;
    
    $entries = [];
    if (file_exists($guestbook_file)) {
        $content = file_get_contents($guestbook_file);
        if (!empty($content)) {
            $entries = json_decode($content, true);
            if (!is_array($entries)) {
                $entries = [];
            }
        }
    }
    
    array_unshift($entries, $entry);
    
    if (count($entries) > 50) {
        $entries = array_slice($entries, 0, 50);
    }
    
    return file_put_contents($guestbook_file, json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Функция для получения всех записей
function getGuestEntries() {
    global $guestbook_file;
    
    if (file_exists($guestbook_file)) {
        $content = file_get_contents($guestbook_file);
        if (!empty($content)) {
            $entries = json_decode($content, true);
            if (is_array($entries)) {
                return $entries;
            }
        }
    }
    return [];
}

// Функция для очистки гостевой книги
function clearGuestbook() {
    global $guestbook_file;
    if (file_exists($guestbook_file)) {
        return unlink($guestbook_file);
    }
    return true;
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear_guestbook'])) {
        if (clearGuestbook()) {
            $success_message = "✅ Гостевая книга очищена!";
        } else {
            $error_message = "❌ Ошибка при очистке!";
        }
    } elseif (isset($_POST['submit_entry'])) {
        $name = trim($_POST['name'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $rating = intval($_POST['rating'] ?? 3);
        
        if (empty($name)) {
            $error_message = "❌ Пожалуйста, введите ваше имя!";
        } elseif (empty($message)) {
            $error_message = "❌ Пожалуйста, введите ваш отзыв!";
        } elseif (strlen($message) < 5) {
            $error_message = "❌ Отзыв должен содержать минимум 5 символов!";
        } else {
            $entry = [
                'id' => uniqid(),
                'name' => htmlspecialchars($name),
                'message' => nl2br(htmlspecialchars($message)),
                'rating' => $rating,
                'date' => date('d.m.Y H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];
            
            if (saveGuestEntry($entry)) {
                $success_message = "✅ Спасибо! Ваш отзыв добавлен!";
                $_POST = [];
            } else {
                $error_message = "❌ Ошибка при сохранении отзыва!";
            }
        }
    }
}

$guest_entries = getGuestEntries();
$total_entries = count($guest_entries);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Гостевая книга | Car Musc</title>
    <link rel="icon" type="image/x-icon" href="Image/favicon.ico">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/guestbook.css">
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <input type="checkbox" id="burger-toggle" class="burger-toggle">
            <label for="burger-toggle" class="burger-btn">
                <span></span><span></span><span></span>
            </label>
            <nav class="nav">
                <a href="index.html" class="nav__link">Главная</a>
                <a href="services.html" class="nav__link">Наши услуги</a>
                <a href="index.html#works" class="nav__link">Галерея работ</a>
                <a href="index.html#contacts" class="nav__link">Контакты</a>
                <a href="guestbook.php" class="nav__link active">Гостевая книга</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="guestbook-container">
            <div class="guestbook-header">
                <h1 class="section-title">ГОСТЕВАЯ КНИГА</h1>
                <div class="dots-divider">
                    <span></span><span></span><span></span><span></span>
                </div>
                <p class="hero__descr" style="margin: 0 auto; max-width: 600px;">
                    Оставьте свой отзыв о нашей работе! Нам важно ваше мнение.
                </p>
                <div class="guestbook-stats">
                    📝 Всего отзывов: <span><?php echo $total_entries; ?></span>
                </div>
            </div>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="two-columns">
                <div class="form-section">
                    <h3>✍️ Оставить отзыв</h3>
                    <form method="POST" class="guest-form" id="guestForm">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="ВАШЕ ИМЯ *" required 
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="ВАШ ОТЗЫВ *" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label style="color: #aaa;">Оценка:</label>
                            <div class="rating-stars" id="ratingStars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star-option <?php echo ($i == 3) ? 'selected' : ''; ?>" data-value="<?php echo $i; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <div class="rating-value-display" id="ratingDisplay">Выбрано: 3 звезды</div>
                            <input type="hidden" name="rating" id="ratingValue" value="3">
                        </div>
                        <button type="submit" name="submit_entry" class="btn-submit">📨 Отправить отзыв</button>
                    </form>
                    <form method="POST" onsubmit="return confirm('Вы уверены, что хотите очистить гостевую книгу? Это действие необратимо!');">
                        <button type="submit" name="clear_guestbook" class="btn-clear">🗑️ Очистить гостевую книгу</button>
                    </form>
                </div>
                
                <div class="entries-section">
                    <h3>📖 Отзывы клиентов</h3>
                    <div class="entries-list">
                        <?php if (empty($guest_entries)): ?>
                            <div class="empty-message">
                                <p>😊 Пока нет отзывов. Будьте первым!</p>
                                <p style="font-size: 12px; margin-top: 10px;">Оставьте свой отзыв о нашей работе</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($guest_entries as $entry): ?>
                                <div class="guest-entry">
                                    <div class="entry-header">
                                        <span class="entry-name">👤 <?php echo $entry['name']; ?></span>
                                        <div class="entry-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="entry-star <?php echo ($i <= $entry['rating']) ? 'filled' : ''; ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="entry-date">📅 <?php echo $entry['date']; ?></span>
                                    </div>
                                    <div class="entry-message">
                                        <?php echo $entry['message']; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <a href="index.html" class="back-link">← Вернуться на главную</a>
        </div>
    </main>
    
    <footer class="footer">
        <div class="container footer__wrapper">
            <p class="footer__copy">2026 © EST ET VIVERRA PELLENTESQUE PHARETRA LOREM PROIN IN. VITAE MAGNA AT TEMPUS COMMODO.</p>
            <nav class="footer__nav">
                <a href="index.html" class="footer__link">Главная</a>
                <a href="services.html" class="footer__link">Наши услуги</a>
                <a href="index.html#works" class="footer__link">Галерея работ</a>
                <a href="index.html#contacts" class="footer__link">Контакты</a>
                <a href="guestbook.php" class="footer__link">Гостевая книга</a>
            </nav>
        </div>
    </footer>
    
    <script>
        const stars = document.querySelectorAll('.star-option');
        const ratingInput = document.getElementById('ratingValue');
        const ratingDisplay = document.getElementById('ratingDisplay');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                ratingInput.value = value;
                
                const starText = value === 1 ? 'звезда' : (value <= 4 ? 'звезды' : 'звезд');
                ratingDisplay.textContent = `Выбрано: ${value} ${starText}`;
                
                stars.forEach(s => s.classList.remove('selected'));
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('selected');
                }
            });
            
            star.addEventListener('mouseenter', function() {
                const value = parseInt(this.dataset.value);
                for (let i = 0; i < value; i++) {
                    stars[i].style.color = '#ffc107';
                }
            });
            
            star.addEventListener('mouseleave', function() {
                stars.forEach(s => s.style.color = '');
            });
        });
    </script>
</body>
</html>