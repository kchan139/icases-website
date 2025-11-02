<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../configs/database.php';
require_once __DIR__ . '/../../app/models/Product.php';

$db = new Database();
$conn = $db->getConnection();
$productModel = new Product($conn);

$query = $_GET['q'] ?? '';
$products = $productModel->search($query);

echo json_encode(['products' => $products]);
