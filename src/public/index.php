<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
session_start();

require_once __DIR__ . "/../configs/database.php";
require_once __DIR__ . "/../app/models/Product.php";

$db = new Database();
$conn = $db->getConnection();
$GLOBALS['db_conn'] = $conn;

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

    case "cart":
        require_once __DIR__ . "/../app/models/Cart.php";
        $cartModel = new Cart($conn);
        
        if (isset($parts[1])) {
            switch ($parts[1]) {
                case "add":
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $productId = $_POST['product_id'] ?? 0;
                        $action = $_POST['action'] ?? 'add';
                        $redirect = $_POST['redirect'] ?? '/cart';
                        
                        if ($productId) {
                            $cartModel->addItem($productId, 1);
                            
                            if ($action === 'buy') {
                                header("Location: /checkout");
                            } else {
                                header("Location: $redirect");
                            }
                            exit();
                        }
                    }
                    break;
                    
                case "update":
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $cartItemId = $_POST['cart_item_id'] ?? 0;
                        $quantity = $_POST['quantity'] ?? 1;
                        $cartModel->updateQuantity($cartItemId, $quantity);
                    }
                    header("Location: /cart");
                    exit();
                    
                case "remove":
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $cartItemId = $_POST['cart_item_id'] ?? 0;
                        $cartModel->removeItem($cartItemId);
                    }
                    header("Location: /cart");
                    exit();
            }
        }
        
        $cartItems = $cartModel->getItems();
        $viewFile = "$VIEWS/cart.php";
        break;

    case "checkout":
        require_once __DIR__ . "/../app/models/Cart.php";
        $cartModel = new Cart($conn);
        $cartItems = $cartModel->getItems();
        
        if (empty($cartItems)) {
            header("Location: /cart");
            exit();
        }
        
        if (isset($parts[1]) && $parts[1] === "process") {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Here you would normally process the order
                // For now, just clear cart and show success
                $cartModel->clearCart();
                header("Location: /order/success");
                exit();
            }
        }
        
        $viewFile = "$VIEWS/checkout.php";
        break;

    case "order":
        if (isset($parts[1]) && $parts[1] === "success") {
            $viewFile = "$VIEWS/order-success.php";
        }
        break;

    default:
        http_response_code(404);
        $viewFile = "$VIEWS/404.php";
        break;
}

require_once __DIR__ . "/../app/views/layout.php";
