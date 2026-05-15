<?php

require_once __DIR__ . '/../src/Database.php';

$pageTitle = 'Ver Todas las Ventas';
$pdo = Database::getConnection();

try {
    $stmt = $pdo->query('
        SELECT
            v.id,
            v.folio,
            v.total,
            v.creado_en,
            u.nombre as usuario_nombre
        FROM ventas v
        JOIN usuarios u ON v.usuario_id = u.id
        WHERE v.estado = \'completada\'
        ORDER BY v.creado_en DESC
    ');
    $sales = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener las ventas: " . $e->getMessage());
}

$content = __DIR__ . '/../views/sales_list.php';
include __DIR__ . '/../views/layout.php';
