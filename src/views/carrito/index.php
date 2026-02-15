<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 tonka-logo">Mi Carrito</h1>

    <?php if (count($items) > 0): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <?php foreach ($items as $item): ?>
                        <div class="flex items-center gap-4 py-4 border-b border-base-300" id="item-<?php echo $item['id']; ?>">
                            <img src="<?php echo getProductImage($item['imagen'] ?? '', $item['categoria_nombre'] ?? ''); ?>" 
                                 alt="<?php echo htmlspecialchars($item['nombre']); ?>" 
                                 class="w-24 h-24 object-contain rounded bg-base-200 p-2" />
                            <div class="flex-1">
                                <h3 class="font-bold"><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                <p class="text-sm text-base-content/60"><?php echo formatPrice($item['precio']); ?> c/u</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="cambiarCantidad(<?php echo $item['id']; ?>, -1)" class="btn btn-sm btn-circle">-</button>
                                <input type="number" value="<?php echo $item['cantidad']; ?>" class="input input-bordered w-16 text-center" readonly />
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
                    
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <div class="mt-6">
                            <label class="label">
                                <span class="label-text font-semibold">Dirección de Envío *</span>
                            </label>
                            <textarea id="direccion_envio" 
                                      class="textarea textarea-bordered w-full h-24" 
                                      placeholder="Calle, número, piso, ciudad, código postal..."
                                      required></textarea>
                        </div>
                        <button onclick="realizarPedido()" class="btn btn-primary btn-block mt-4">
                            Realizar Pedido
                        </button>
                    <?php else: ?>
                        <a href="/login" class="btn btn-primary btn-block mt-6">Iniciar Sesión para Comprar</a>
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
            <a href="/productos" class="btn btn-primary">Ir a Productos</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function cambiarCantidad(itemId, cambio) {
        const input = document.querySelector(`#item-${itemId} input`);
        const nuevaCantidad = parseInt(input.value) + cambio;
        
        if (nuevaCantidad <= 0) {
            eliminarItem(itemId);
            return;
        }
        
        fetch('/api/carrito/actualizar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ carrito_id: itemId, cantidad: nuevaCantidad })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
        });
    }

    function eliminarItem(itemId) {
        if (confirm('¿Eliminar este producto del carrito?')) {
            fetch('/api/carrito/eliminar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ carrito_id: itemId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) location.reload();
            });
        }
    }

    function vaciarCarrito() {
        if (confirm('¿Vaciar todo el carrito?')) {
            fetch('/api/carrito/vaciar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) location.reload();
            });
        }
    }
    
    function realizarPedido() {
        const direccion = document.getElementById('direccion_envio').value.trim();
        
        if (!direccion) {
            alert('Por favor, ingresa una dirección de envío');
            return;
        }
        
        if (!confirm('¿Confirmar pedido?\n\nSe procesará tu orden con la dirección proporcionada.')) {
            return;
        }
        
        // Deshabilitar botón para evitar doble click
        const boton = event.target;
        boton.disabled = true;
        boton.textContent = 'Procesando...';
        
        fetch('/api/carrito/realizar-pedido', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ direccion_envio: direccion })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('¡Pedido realizado con éxito!\n\nPuedes ver tus pedidos en tu perfil.');
                window.location.href = '/pedidos';
            } else {
                alert('Error: ' + (data.message || 'No se pudo procesar el pedido'));
                boton.disabled = false;
                boton.textContent = 'Realizar Pedido';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar el pedido. Por favor, inténtalo de nuevo.');
            boton.disabled = false;
            boton.textContent = 'Realizar Pedido';
        });
    }
</script>
