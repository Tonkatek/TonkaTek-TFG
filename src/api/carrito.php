<?php
require_once '../config/config.php';
require_once '../includes/classes/Carrito.php';

header('Content-Type: application/json');

$carrito = new Carrito();
$data = json_decode(file_get_contents('php://input'), true);

$response = ['success' => false];

if (isset($data['action'])) {
    switch ($data['action']) {
        case 'agregar':
            if (isset($data['producto_id'])) {
                $cantidad = isset($data['cantidad']) ? (int)$data['cantidad'] : 1;
                if ($carrito->agregar($data['producto_id'], $cantidad)) {
                    $response['success'] = true;
                    $response['total_items'] = $carrito->contarItems();
                }
            }
            break;
        
        case 'actualizar':
            if (isset($data['carrito_id']) && isset($data['cantidad'])) {
                if ($carrito->actualizarCantidad($data['carrito_id'], $data['cantidad'])) {
                    $response['success'] = true;
                    $response['total'] = $carrito->calcularTotal();
                }
            }
            break;
        
        case 'eliminar':
            if (isset($data['carrito_id'])) {
                if ($carrito->eliminar($data['carrito_id'])) {
                    $response['success'] = true;
                }
            }
            break;
        
        case 'vaciar':
            if ($carrito->vaciar()) {
                $response['success'] = true;
            }
            break;
        
        case 'obtener':
            $response['success'] = true;
            $response['items'] = $carrito->obtenerItems();
            $response['total'] = $carrito->calcularTotal();
            $response['total_items'] = $carrito->contarItems();
            break;
    }
}

echo json_encode($response);
