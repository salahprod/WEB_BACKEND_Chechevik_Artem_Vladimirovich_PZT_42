<?php
// api_cart_handler.php - API для работы с корзиной
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');

require_once 'db_config.php';

$pdo = getDB();
$action = isset($_GET['action']) ? $_GET['action'] : '';
$method = $_SERVER['REQUEST_METHOD'];

// Для OPTIONS запроса
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

switch ($action) {
    case 'add':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $service_id = (int)$data['service_id'];
            $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;
            
            $stmt = $pdo->prepare("SELECT id, name, price FROM services WHERE id = ?");
            $stmt->execute([$service_id]);
            $service = $stmt->fetch();
            
            if ($service) {
                if (isset($_SESSION['shop_cart'][$service_id])) {
                    $_SESSION['shop_cart'][$service_id]['quantity'] += $quantity;
                } else {
                    $_SESSION['shop_cart'][$service_id] = [
                        'id' => $service['id'],
                        'name' => $service['name'],
                        'price' => (float)$service['price'],
                        'quantity' => $quantity
                    ];
                }
                echo json_encode(['success' => true, 'cart_count' => getCartCount()]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Услуга не найдена']);
            }
        }
        break;
        
    case 'remove':
        if ($method === 'DELETE') {
            $data = json_decode(file_get_contents('php://input'), true);
            $service_id = (int)$data['service_id'];
            if (isset($_SESSION['shop_cart'][$service_id])) {
                unset($_SESSION['shop_cart'][$service_id]);
            }
            echo json_encode(['success' => true, 'cart_count' => getCartCount()]);
        }
        break;
        
    case 'update':
        if ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $service_id = (int)$data['service_id'];
            $quantity = max(1, (int)$data['quantity']);
            if (isset($_SESSION['shop_cart'][$service_id])) {
                $_SESSION['shop_cart'][$service_id]['quantity'] = $quantity;
            }
            echo json_encode(['success' => true, 'cart_count' => getCartCount()]);
        }
        break;
        
    case 'clear':
        if ($method === 'DELETE') {
            $_SESSION['shop_cart'] = [];
            echo json_encode(['success' => true, 'cart_count' => 0]);
        }
        break;
        
    case 'get':
        if ($method === 'GET') {
            $cart_items = [];
            $total = 0;
            foreach ($_SESSION['shop_cart'] as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                $cart_items[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'price_formatted' => number_format($item['price'], 0, '.', ' ') . ' ₽',
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                    'subtotal_formatted' => number_format($subtotal, 0, '.', ' ') . ' ₽'
                ];
            }
            echo json_encode([
                'success' => true,
                'items' => $cart_items,
                'total' => $total,
                'total_formatted' => number_format($total, 0, '.', ' ') . ' ₽',
                'count' => getCartCount()
            ]);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
}
?>