<?php
require_once '../config/config.php';
require_once '../includes/classes/Carrito.php';

$carrito = new Carrito();
$items = $carrito->obtenerItems();
$total = $carrito->calcularTotal();
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@900&family=Exo+2:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Exo 2', sans-serif; }
        .tonka-logo { font-family: 'Orbitron', sans-serif; }
        .price-tag { color: #FF6B35; font-weight: bold; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="navbar bg-white shadow-lg">
        <div class="flex-1">
            <a href="../index.php" class="tonka-logo text-3xl px-4 bg-gradient-to-r from-orange-500 to-blue-600 bg-clip-text text-transparent">TONKATEK</a>
        </div>
        <div class="flex-none">
            <a href="productos.php" class="btn btn-ghost">Seguir Comprando</a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 tonka-logo">Mi Carrito</h1>

        <?php if (count($items) > 0): ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <?php foreach ($items as $item): ?>
                            <div class="flex items-center gap-4 py-4 border-b" id="item-<?php echo $item['id']; ?>">
                                <div class="w-24 h-24 bg-gray-100 rounded flex items-center justify-center">
                                    <span class="text-4xl">🖥️</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold"><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                    <p class="text-sm text-gray-600"><?php echo formatPrice($item['precio']); ?> c/u</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button onclick="cambiarCantidad(<?php echo $item['id']; ?>, -1)" class="btn btn-sm btn-circle">-</button>
                                    <input type="number" value="<?php echo $item['cantidad']; ?>" class="input input-bordered w-16 text-center" id="cantidad-<?php echo $item['id']; ?>" readonly />
                                    <button onclick="cambiarCantidad(<?php echo $item['id']; ?>, 1)" class="btn btn-sm btn-circle">+</button>
                                </div>
                                <div class="text-right">
                                    <p class="price-tag text-xl"><?php echo formatPrice($item['subtotal']); ?></p>
                                    <button onclick="eliminarItem(<?php echo $item['id']; ?>)" class="btn btn-error btn-xs mt-2">Eliminar</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                        <h2 class="text-2xl font-bold mb-4">Resumen</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span class="font-semibold"><?php echo formatPrice($total); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Envío:</span>
                                <span class="text-green-600">GRATIS</span>
                            </div>
                            <div class="divider"></div>
                            <div class="flex justify-between text-xl font-bold">
                                <span>Total:</span>
                                <span class="price-tag"><?php echo formatPrice($total); ?></span>
                            </div>
                        </div>
                        
                        <?php if (isLoggedIn()): ?>
                            <button class="btn btn-primary btn-block mt-6">Proceder al Pago</button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-block mt-6">Iniciar Sesión para Comprar</a>
                        <?php endif; ?>
                        
                        <button onclick="vaciarCarrito()" class="btn btn-outline btn-error btn-block mt-2">Vaciar Carrito</button>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <h2 class="text-2xl font-bold mb-2">Tu carrito está vacío</h2>
                <p class="text-gray-600 mb-6">Añade productos para comenzar tu compra</p>
                <a href="productos.php" class="btn btn-primary">Ir a Productos</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function cambiarCantidad(itemId, cambio) {
            const input = document.getElementById('cantidad-' + itemId);
            const nuevaCantidad = parseInt(input.value) + cambio;
            
            if (nuevaCantidad <= 0) {
                eliminarItem(itemId);
                return;
            }
            
            fetch('../api/carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'actualizar', carrito_id: itemId, cantidad: nuevaCantidad })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function eliminarItem(itemId) {
            if (confirm('¿Eliminar este producto del carrito?')) {
                fetch('../api/carrito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'eliminar', carrito_id: itemId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        function vaciarCarrito() {
            if (confirm('¿Vaciar todo el carrito?')) {
                fetch('../api/carrito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'vaciar' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }
    </script>
</body>
</html>
