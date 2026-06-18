<?php
// api_services.php - API для получения данных каталога
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

require_once 'db_config.php';

$pdo = getDB();

try {
    // Параметры запроса
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 9;
    $offset = ($page - 1) * $limit;
    
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
    $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 999999;
    
    // Базовый запрос
    $sql = "SELECT * FROM services WHERE 1=1";
    $countSql = "SELECT COUNT(*) as total FROM services WHERE 1=1";
    $params = [];
    
    // Поиск
    if (!empty($search)) {
        $sql .= " AND (name LIKE :search OR description LIKE :search)";
        $countSql .= " AND (name LIKE :search OR description LIKE :search)";
        $params[':search'] = "%{$search}%";
    }
    
    // Фильтр по категории
    if ($category !== 'all' && !empty($category)) {
        $sql .= " AND category = :category";
        $countSql .= " AND category = :category";
        $params[':category'] = $category;
    }
    
    // Фильтр по цене
    if ($min_price > 0) {
        $sql .= " AND price >= :min_price";
        $countSql .= " AND price >= :min_price";
        $params[':min_price'] = $min_price;
    }
    if ($max_price < 999999) {
        $sql .= " AND price <= :max_price";
        $countSql .= " AND price <= :max_price";
        $params[':max_price'] = $max_price;
    }
    
    // Сортировка
    switch ($sort) {
        case 'name_asc':
            $sql .= " ORDER BY name ASC";
            break;
        case 'name_desc':
            $sql .= " ORDER BY name DESC";
            break;
        case 'price_asc':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY price DESC";
            break;
        default:
            $sql .= " ORDER BY id ASC";
    }
    
    // Пагинация
    $sql .= " LIMIT :limit OFFSET :offset";
    $params[':limit'] = $limit;
    $params[':offset'] = $offset;
    
    // Выполнение запроса
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        if ($key == ':limit' || $key == ':offset') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    $stmt->execute();
    $services = $stmt->fetchAll();
    
    // Получение общего количества
    $countStmt = $pdo->prepare($countSql);
    foreach ($params as $key => $value) {
        if ($key != ':limit' && $key != ':offset') {
            $countStmt->bindValue($key, $value);
        }
    }
    $countStmt->execute();
    $total = $countStmt->fetch()['total'];
    
    // Получение категорий
    $catStmt = $pdo->query("SELECT DISTINCT category FROM services ORDER BY category");
    $categories = $catStmt->fetchAll();
    
    // Форматирование
    foreach ($services as &$service) {
        $service['price_formatted'] = number_format($service['price'], 0, '.', ' ') . ' ₽';
    }
    
    echo json_encode([
        'success' => true,
        'services' => $services,
        'total' => (int)$total,
        'page' => $page,
        'limit' => $limit,
        'total_pages' => ceil($total / $limit),
        'categories' => $categories
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'services' => [],
        'total' => 0,
        'total_pages' => 0,
        'categories' => []
    ]);
}
?>