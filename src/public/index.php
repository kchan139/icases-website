<?php
session_start();
require_once __DIR__ . "/../configs/database.php";
require_once __DIR__ . "/../app/models/Product.php";

$db = new Database();
$conn = $db->getConnection();

// Get the requested path
$path = $_GET["path"] ?? "";
$path = trim($path, "/");

// Parse the path
$parts = explode("/", $path);
$page = $parts[0] ?: "home";

$VIEWS = __DIR__ . "/../app/views";

switch ($page) {
    case "home":
    case "":
        $productModel = new Product($conn);
        $featuredProducts = $productModel->getFeatured(8);
        $viewFile = "$VIEWS/home.php";
        break;

    case "products":
        $productModel = new Product($conn);
        $pageNum = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
        $sort = $_GET["sort"] ?? "newest";
        $category = $_GET["category"] ?? null;
        $model = $_GET["model"] ?? null;

        $perPage = 12;
        $offset = ($pageNum - 1) * $perPage;

        $products = $productModel->getAll(
            $sort,
            $category,
            $model,
            $perPage,
            $offset,
        );
        $totalProducts = $productModel->getTotalCount($category, $model);
        $totalPages = ceil($totalProducts / $perPage);
        $categories = $productModel->getCategories();
        $iphoneModels = $productModel->getIphoneModels();

        $viewFile = "$VIEWS/products.php";
        break;

    case "stores":
        $query = "SELECT * FROM stores WHERE is_active = 1 ORDER BY city, name";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stores = $stmt->fetchAll();
        $viewFile = "$VIEWS/stores.php";
        break;

    case "login":
        require_once __DIR__ . "/../app/controllers/AuthController.php";
        $authController = new AuthController($conn);
        extract($authController->login());
        $viewFile = "$VIEWS/login.php";
        break;

    case "register":
        require_once __DIR__ . "/../app/controllers/AuthController.php";
        $authController = new AuthController($conn);
        extract($authController->register());
        $viewFile = "$VIEWS/register.php";
        break;

    case "logout":
        session_destroy();
        header("Location: /");
        exit();

    case "forgot-password":
        require_once __DIR__ . "/../app/controllers/AuthController.php";
        $authController = new AuthController($conn);
        extract($authController->forgotPassword());
        $viewFile = "$VIEWS/forgot-password.php";
        break;

    case "reset-password":
        require_once __DIR__ . "/../app/controllers/AuthController.php";
        $authController = new AuthController($conn);
        $token = $_GET["token"] ?? "";
        extract($authController->resetPassword($token));
        $viewFile = "$VIEWS/reset-password.php";
        break;

    case "product":
        if (isset($parts[1])) {
            $productModel = new Product($conn);
            $product = $productModel->getBySlug($parts[1]);

            if ($product) {
                $stores = $productModel->getStoreAvailability($product["id"]);
                $viewFile = "$VIEWS/product-detail.php";
            } else {
                http_response_code(404);
                $viewFile = "$VIEWS/404.php";
            }
        } else {
            http_response_code(404);
            $viewFile = "$VIEWS/404.php";
        }
        break;

    default:
        http_response_code(404);
        $viewFile = "$VIEWS/404.php";
        break;
}

require_once __DIR__ . "/../app/views/layout.php";
