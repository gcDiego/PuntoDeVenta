<?php

require_once __DIR__ . '/../src/Database.php';

$pageTitle = 'Lista de Productos';
$content = __DIR__ . '/../views/products_list.php';

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query('SELECT * FROM vista_stock_actual ORDER BY nombre');
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener los productos: " . $e->getMessage());
}

include __DIR__ . '/../views/layout.php';
