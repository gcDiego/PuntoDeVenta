<?php

require_once __DIR__ . '/../src/Database.php';

$pageTitle = 'Reporte de Ventas Diarias';
$pdo = Database::getConnection();

try {
    $stmt = $pdo->query('SELECT * FROM vista_ventas_diarias ORDER BY fecha DESC');
    $sales_summary = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener el reporte de ventas: " . $e->getMessage());
}

$content = __DIR__ . '/../views/sales_summary_list.php';
include __DIR__ . '/../views/layout.php';
