<?php

require_once __DIR__ . '/../src/Database.php';

$pageTitle = 'Add Product';
$pdo = Database::getConnection();

// Obtener categorias activas
$stmt = $pdo->query('SELECT id, nombre FROM categorias WHERE activo = TRUE ORDER BY nombre');
$categories = $stmt->fetchAll();

// Envio del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $precio_venta = $_POST['precio_venta'] ?? 0;
    $stock = $_POST['stock'] ?? 0;
    $categoria_id = $_POST['categoria_id'] ?? null;

    // Validacion basica
    if (!empty($nombre) && $precio_venta > 0 && $categoria_id) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO productos (nombre, precio_venta, stock, categoria_id) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$nombre, $precio_venta, $stock, $categoria_id]);

            header('Location: products.php');
            exit;
        } catch (PDOException $e) {
            die("Error al añadir producto: " . $e->getMessage());
        }
    } else {
        $error = "Por favor rellena todos los campos.";
    }
}

$content = __DIR__ . '/../views/products_form.php';
include __DIR__ . '/../views/layout.php';
