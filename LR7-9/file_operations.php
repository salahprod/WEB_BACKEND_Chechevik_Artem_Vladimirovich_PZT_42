<?php
// ============================================================================
// ЗАДАНИЕ №1: Типовые действия с файлами
// ============================================================================

$message = '';
$message_type = '';
$file_content = '';
$current_file = 'test_data.txt';
$file_info = null;

function createAndWriteFile($filename, $data) {
    $file = fopen($filename, 'w');
    if ($file) {
        fwrite($file, $data);
        fclose($file);
        return true;
    }
    return false;
}

function readFileLineByLine($filename) {
    $content = '';
    if (file_exists($filename)) {
        $file = fopen($filename, 'r');
        if ($file) {
            $line_num = 1;
            while (($line = fgets($file)) !== false) {
                $content .= "<span style='color:#888;'>[" . $line_num . "]</span> " . htmlspecialchars($line) . "<br>";
                $line_num++;
            }
            fclose($file);
        }
    } else {
        $content = "<span style='color:#dc3545;'>Файл не существует! Сначала создайте файл.</span>";
    }
    return $content;
}

function readFileFull($filename) {
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        return nl2br(htmlspecialchars($content));
    }
    return "<span style='color:#dc3545;'>Файл не существует! Сначала создайте файл.</span>";
}

function appendToFile($filename, $data) {
    $file = fopen($filename, 'a');
    if ($file) {
        fwrite($file, $data . PHP_EOL);
        fclose($file);
        return true;
    }
    return false;
}

function copyFile($source, $destination) {
    if (file_exists($source)) {
        return copy($source, $destination);
    }
    return false;
}

function renameFile($oldname, $newname) {
    if (file_exists($oldname)) {
        return rename($oldname, $newname);
    }
    return false;
}

function deleteFile($filename) {
    if (file_exists($filename)) {
        return unlink($filename);
    }
    return false;
}

function getFileInfo($filename) {
    if (file_exists($filename)) {
        $perms = fileperms($filename);
        return [
            'size' => filesize($filename),
            'size_kb' => round(filesize($filename) / 1024, 2),
            'modified' => date("d.m.Y H:i:s", filemtime($filename)),
            'created' => date("d.m.Y H:i:s", filectime($filename)),
            'type' => filetype($filename),
            'permissions' => substr(sprintf('%o', $perms), -4),
            'is_readable' => is_readable($filename) ? 'Да' : 'Нет',
            'is_writable' => is_writable($filename) ? 'Да' : 'Нет'
        ];
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $data = "=== Файл создан: " . date('d.m.Y H:i:s') . " ===\n";
                $data .= "Первая строка данных\n";
                $data .= "Вторая строка данных\n";
                $data .= "Третья строка с важной информацией\n";
                if (createAndWriteFile($current_file, $data)) {
                    $message = "✅ Файл '{$current_file}' успешно создан и данные записаны!";
                    $message_type = 'success';
                } else {
                    $message = "❌ Ошибка при создании файла!";
                    $message_type = 'error';
                }
                break;
                
            case 'append':
                $new_data = "➕ Добавлена запись: " . date('d.m.Y H:i:s') . " - Новая информация";
                if (appendToFile($current_file, $new_data)) {
                    $message = "✅ Данные успешно добавлены в конец файла!";
                    $message_type = 'success';
                } else {
                    $message = "❌ Ошибка при добавлении данных!";
                    $message_type = 'error';
                }
                break;
                
            case 'read_line':
                $file_content = readFileLineByLine($current_file);
                $message = "📖 Чтение файла выполнено (ПОСТРОЧНО)";
                $message_type = 'info';
                break;
                
            case 'read_full':
                $file_content = readFileFull($current_file);
                $message = "📖 Чтение файла выполнено (ЦЕЛИКОМ)";
                $message_type = 'info';
                break;
                
            case 'copy':
                $backup_file = 'backup_' . date('Ymd_His') . '_' . basename($current_file);
                if (copyFile($current_file, $backup_file)) {
                    $message = "✅ Файл скопирован в '{$backup_file}'!";
                    $message_type = 'success';
                } else {
                    $message = "❌ Ошибка при копировании файла!";
                    $message_type = 'error';
                }
                break;
                
            case 'rename':
                $new_name = 'renamed_' . date('Ymd_His') . '.txt';
                if (renameFile($current_file, $new_name)) {
                    $message = "✅ Файл переименован из '{$current_file}' в '{$new_name}'!";
                    $message_type = 'success';
                    $current_file = $new_name;
                } else {
                    $message = "❌ Ошибка при переименовании!";
                    $message_type = 'error';
                }
                break;
                
            case 'info':
                $file_info = getFileInfo($current_file);
                if ($file_info) {
                    $message_type = 'info';
                } else {
                    $message = "❌ Файл '{$current_file}' не найден!";
                    $message_type = 'error';
                }
                break;
                
            case 'delete':
                if (deleteFile($current_file)) {
                    $message = "✅ Файл '{$current_file}' успешно удален!";
                    $message_type = 'success';
                    $file_content = '';
                    $file_info = null;
                } else {
                    $message = "❌ Ошибка при удалении файла!";
                    $message_type = 'error';
                }
                break;
        }
    }
}

$display_file = null;
if (file_exists('test_data.txt')) {
    $display_file = 'test_data.txt';
} else {
    $renamed_files = glob('renamed_*.txt');
    if (!empty($renamed_files)) {
        $display_file = $renamed_files[0];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление файлами | Car Musc</title>
    <link rel="icon" type="image/x-icon" href="Image/favicon.ico">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS_fileman/fileman.css">
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
                <a href="guestbook.php" class="nav__link">Гостевая книга</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="file-manager">
            <div class="file-header">
                <h1 class="section-title">УПРАВЛЕНИЕ ФАЙЛАМИ</h1>
                <div class="dots-divider">
                    <span></span><span></span><span></span><span></span>
                </div>
                <p class="hero__descr" style="margin: 0 auto; max-width: 600px;">
                    Демонстрация всех типовых операций с файлами
                </p>
                <div class="file-stats">
                    📁 Текущий файл: <span><?php echo $display_file ? $display_file : 'Не выбран'; ?></span>
                </div>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="two-columns">
                <div>
                    <div class="operations-panel">
                        <h3>🔧 ОПЕРАЦИИ С ФАЙЛАМИ</h3>
                        <form method="POST">
                            <div class="btn-group">
                                <button type="submit" name="action" value="create" class="btn-file">📄 СОЗДАТЬ</button>
                                <button type="submit" name="action" value="append" class="btn-file">➕ ДОБАВИТЬ</button>
                                <button type="submit" name="action" value="read_line" class="btn-file">📖 ЧИТАТЬ (построчно)</button>
                                <button type="submit" name="action" value="read_full" class="btn-file">📖 ЧИТАТЬ (целиком)</button>
                                <button type="submit" name="action" value="copy" class="btn-file">📋 КОПИРОВАТЬ</button>
                                <button type="submit" name="action" value="rename" class="btn-file">✏️ ПЕРЕИМЕНОВАТЬ</button>
                                <button type="submit" name="action" value="info" class="btn-file info">ℹ️ ИНФОРМАЦИЯ</button>
                                <button type="submit" name="action" value="delete" class="btn-file delete" onclick="return confirm('Удалить файл? Это действие необратимо!')">🗑️ УДАЛИТЬ</button>
                            </div>
                        </form>
                        <div class="current-file">
                            📄 <strong>Текущий файл операций:</strong> test_data.txt
                        </div>
                    </div>
                    
                    <?php if ($file_info): ?>
                    <div class="info-panel">
                        <h3>ℹ️ ИНФОРМАЦИЯ О ФАЙЛЕ</h3>
                        <div class="info-grid">
                            <div class="info-item"><span class="info-label">Имя файла:</span><span class="info-value"><?php echo $display_file; ?></span></div>
                            <div class="info-item"><span class="info-label">Размер:</span><span class="info-value"><?php echo $file_info['size_kb']; ?> KB</span></div>
                            <div class="info-item"><span class="info-label">Создан:</span><span class="info-value"><?php echo $file_info['created']; ?></span></div>
                            <div class="info-item"><span class="info-label">Изменен:</span><span class="info-value"><?php echo $file_info['modified']; ?></span></div>
                            <div class="info-item"><span class="info-label">Права:</span><span class="info-value"><?php echo $file_info['permissions']; ?></span></div>
                            <div class="info-item"><span class="info-label">Чтение:</span><span class="info-value"><?php echo $file_info['is_readable']; ?></span></div>
                            <div class="info-item"><span class="info-label">Запись:</span><span class="info-value"><?php echo $file_info['is_writable']; ?></span></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="content-panel">
                    <h3>📄 СОДЕРЖИМОЕ ФАЙЛА</h3>
                    <div class="content-display">
                        <?php 
                        if ($file_content) {
                            echo $file_content;
                        } elseif ($display_file && file_exists($display_file)) {
                            echo "<span style='color:#888;'>Нажмите 'ЧИТАТЬ', чтобы отобразить содержимое...</span>";
                        } else {
                            echo "<span style='color:#888;'>Файл не существует. Нажмите 'СОЗДАТЬ'.</span>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="operations-panel" style="margin-top: 20px;">
                <h3>📚 ТИПОВЫЕ ОПЕРАЦИИ С ФАЙЛАМИ</h3>
                <table class="function-table">
                    <thead>
                        <tr><th>Операция</th><th>Функция PHP</th><th>Описание</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Создание/запись</td><td style="color:#ff2a44;">fopen() / fwrite()</td><td>Создание файла и запись данных</td></tr>
                        <tr><td>Добавление</td><td style="color:#ff2a44;">fopen($file, 'a')</td><td>Добавление в конец файла</td></tr>
                        <tr><td>Чтение (построчно)</td><td style="color:#ff2a44;">fgets() / feof()</td><td>Построчное чтение файла</td></tr>
                        <tr><td>Чтение (целиком)</td><td style="color:#ff2a44;">file_get_contents()</td><td>Чтение всего файла</td></tr>
                        <tr><td>Копирование</td><td style="color:#ff2a44;">copy()</td><td>Создание копии файла</td></tr>
                        <tr><td>Переименование</td><td style="color:#ff2a44;">rename()</td><td>Изменение имени файла</td></tr>
                        <tr><td>Информация</td><td style="color:#ff2a44;">filesize() / filemtime()</td><td>Метаданные файла</td></tr>
                        <tr><td>Удаление</td><td style="color:#ff2a44;">unlink()</td><td>Полное удаление файла</td></tr>
                    </tbody>
                </table>
            </div>
            
            <a href="index.html" class="back-link">← Вернуться на главную</a>
        </div>
    </main>
    
    <footer class="footer">
        <div class="container footer__wrapper">
            <p class="footer__copy">2026 © EST ET VIVERRA PELLENTESQUE PHARETRA LOREM PROIN IN.</p>
            <nav class="footer__nav">
                <a href="index.html" class="footer__link">Главная</a>
                <a href="services.html" class="footer__link">Наши услуги</a>
                <a href="index.html#works" class="footer__link">Галерея работ</a>
                <a href="index.html#contacts" class="footer__link">Контакты</a>
                <a href="guestbook.php" class="footer__link">Гостевая книга</a>
            </nav>
        </div>
    </footer>
</body>
</html>