<?php
session_start();
require_once 'configs/database.php';
require_once 'models/Product.php';

$db = new Database();
$conn = $db->getConnection();

// Get the requested path
$path = $_GET['path'] ?? '';
$path = trim($path, '/');

// Parse the path
$parts = explode('/', $path);
$page = $parts[0] ?: 'home';

switch ($page) {
    case 'home':
    case '':
        $productModel = new Product($conn);
        $featuredProducts = $productModel->getFeatured(8);
        $viewFile = 'views/home.php';
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
        
        $viewFile = 'views/products.php';
        break;

    case 'login':
        $viewFile = 'views/login.php';
        break;

    case 'logout':
        $viewFile = 'views/logout.php';
        break;

    case 'search':
        $viewFile = 'views/search.php';
        break;

    case 'stores':
        $viewFile = 'views/stores.php';
        break;

    default:
        http_response_code(404);
        $viewFile = 'views/404.php';
        break;
}

require_once 'views/layout.php';
