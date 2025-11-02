<?php
session_start();
require_once __DIR__ . '/../configs/database.php';
require_once __DIR__ . '/../app/models/Product.php';

$db = new Database();
$conn = $db->getConnection();

// Get the requested path
$path = $_GET['path'] ?? '';
$path = trim($path, '/');

// Parse the path
$parts = explode('/', $path);
$page = $parts[0] ?: 'home';

$VIEWS = __DIR__ . '/../app/views';

switch ($page) {
    case 'home':
    case '':
        $productModel = new Product($conn);
        $featuredProducts = $productModel->getFeatured(8);
        $viewFile = $VIEWS . '/home.php';
        break;

    case 'products':
        $productModel = new Product($conn);
        $pageNum = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $sort = $_GET['sort'] ?? 'newest';
        $category = $_GET['category'] ?? null;
        $model = $_GET['model'] ?? null;
        
        $perPage = 12;
        $offset = ($pageNum - 1) * $perPage;
        
        $products = $productModel->getAll($sort, $category, $model, $perPage, $offset);
        $totalProducts = $productModel->getTotalCount($category, $model);
        $totalPages = ceil($totalProducts / $perPage);
        $categories = $productModel->getCategories();
        $iphoneModels = $productModel->getIphoneModels();
        
        $viewFile = $VIEWS . '/products.php';
        break;

    case 'login':
        $viewFile = 'views/login.php';
        $viewFile = $VIEWS . '/login.php';
        break;

    case 'logout':
        $viewFile = 'views/logout.php';
        $viewFile = $VIEWS . '/logout.php';
        break;

    case 'stores':
        $viewFile = $VIEWS . '/stores.php';
        break;

    default:
        http_response_code(404);
        $viewFile = $VIEWS . '/404.php';
        break;
}

require_once __DIR__ . '/../app/views/layout.php';
