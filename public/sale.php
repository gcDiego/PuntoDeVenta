<?php

require_once __DIR__ . '/../src/Database.php';

$pageTitle = 'New Sale';
$pdo = Database::getConnection();

// Obtener productos
$stmt = $pdo->query('SELECT id, nombre, precio_venta FROM productos WHERE activo = TRUE AND stock > 0 ORDER BY nombre');
$products = $stmt->fetchAll();

// Envio del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $items = [];
    if (isset($_POST['products'])) {
        foreach ($_POST['products'] as $productId => $data) {
            if (!empty($data['quantity']) && $data['quantity'] > 0) {
                $items[] = [
                    'producto_id' => $productId,
                    'cantidad' => (int)$data['quantity'],
                ];
            }
        }
    }

    if (!empty($items)) {
        try {
            // Esto debe ser dinamico
            $userId = 1; // Asumir que el ID 1 existe
            $paymentMethodId = 1; // Asumir que es 'Efectivo'

            // Sumatoria del total para el pago
            $total = 0;
            foreach ($items as $item) {
                $stmt = $pdo->prepare('SELECT precio_venta FROM productos WHERE id = ?');
                $stmt->execute([$item['producto_id']]);
                $product = $stmt->fetch();
                $total += $product['precio_venta'] * $item['cantidad'];
            }

            $pagos = [
                [
                    'metodo_pago_id' => $paymentMethodId,
                    'monto' => $total,
                ]
            ];

            $stmt = $pdo->prepare("SELECT procesar_venta(?, ?, ?)");
            $stmt->execute([$userId, json_encode($items), json_encode($pagos)]);
            $saleId = $stmt->fetchColumn();

            $message = "Venta completada de manera exitosa Sale ID: " . $saleId;

        } catch (PDOException $e) {
            $error = "Error al procesar la venta: " . $e->getMessage();
        }
    } else {
        $error = "Selecciona al menos un producto para la venta.";
    }
}

$content = __DIR__ . '/../views/sale_form.php';
include __DIR__ . '/../views/layout.php';
