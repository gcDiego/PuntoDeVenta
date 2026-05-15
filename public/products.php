<?php

require_once __DIR__ . '/../src/Database.php';

$pageTitle = 'Products';
$content = __DIR__ . '/../views/products_list.php';

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query('
        SELECT p.*, c.nombre as categoria_nombre
        FROM productos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        ORDER BY p.nombre
    ');
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener los productos: " . $e->getMessage());
}

include __DIR__ . '/../views/layout.php';
