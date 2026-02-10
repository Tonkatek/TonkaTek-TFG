<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<!-- Breadcrumbs -->
<div class="bg-base-200 py-4">
    <div class="container mx-auto px-4">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/productos">Productos</a></li>
                <?php if(isset($producto['categoria_nombre'])): ?>
                    <li><a href="/productos?categoria=<?php echo $producto['categoria_id']; ?>"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></a></li>
                <?php endif; ?>
                <li><?php echo htmlspecialchars($producto['nombre']); ?></li>
            </ul>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Imagen del Producto -->
        <div class="bg-base-100 rounded-lg shadow-lg p-8">
            <img src="<?php echo getProductImage($producto['imagen'] ?? '', $producto['categoria_nombre'] ?? ''); ?>" 
                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                 class="w-full aspect-square object-contain rounded-lg" />
        </div>

        <!-- Información del Producto -->
        <div>
            <!-- Título y Rating -->
            <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="flex items-center">
                    <div class="rating rating-sm">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" class="mask mask-star-2 bg-orange-400" disabled <?php echo $i == 4 ? 'checked' : ''; ?> />
                        <?php endfor; ?>
                    </div>
                    <span class="ml-2 text-sm">4.3/5</span>
                </div>
                <span class="text-sm text-gray-600">(<?php echo rand(10, 100); ?> opiniones)</span>
                <?php if(isset($producto['modelo'])): ?>
                    <span class="badge badge-outline"><?php echo htmlspecialchars($producto['modelo']); ?></span>
                <?php endif; ?>
            </div>

            <!-- Marca y Referencia -->
            <div class="flex gap-4 mb-4">
                <?php if(isset($producto['marca'])): ?>
                    <div>
                        <span class="text-sm text-gray-600">Marca:</span>
                        <a href="#" class="font-semibold text-primary hover:underline"><?php echo htmlspecialchars($producto['marca']); ?></a>
                    </div>
                <?php endif; ?>
                <div class="divider divider-horizontal"></div>
                <div>
                    <span class="text-sm text-gray-600">Ref:</span>
                    <span class="font-mono text-sm"><?php echo $producto['id']; ?></span>
                </div>
            </div>

            <!-- Precio -->
            <div class="bg-base-200 rounded-lg p-6 mb-6">
                <div class="flex items-baseline gap-4">
                    <span class="price-tag text-5xl font-bold"><?php echo formatPrice($producto['precio']); ?></span>
                    <?php 
                    $precio_original = $producto['precio'] * 1.20;
                    $descuento = 20;
                    ?>
                    <div class="flex flex-col">
                        <span class="text-gray-500 line-through"><?php echo formatPrice($precio_original); ?></span>
                        <span class="badge badge-error">-<?php echo $descuento; ?>%</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Precio más bajo en los últimos 30 días: <?php echo formatPrice($producto['precio'] * 1.10); ?></p>
            </div>

            <!-- Vendido y Enviado por -->
            <div class="alert mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-bold">Vendido y enviado por TonkaTek</h3>
                    <div class="text-xs">Envío rápido y seguro</div>
                </div>
            </div>

            <!-- Stock -->
            <div class="mb-4">
                <?php if($producto['stock'] > 0): ?>
                    <div class="flex items-center gap-2 text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">En Stock - <?php echo $producto['stock']; ?> unidades disponibles</span>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-2 text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">Agotado</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Envío -->
            <div class="card bg-base-100 shadow-sm mb-6">
                <div class="card-body p-4">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <div>
                            <p class="font-semibold">Envío: <span class="text-success">GRATIS</span></p>
                            <p class="text-sm text-gray-600">Recíbelo entre el <span class="text-success font-semibold">jueves 12 y el lunes 16 de febrero</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cantidad y Añadir al Carrito -->
            <div class="flex gap-4 mb-6">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Cantidad</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <button onclick="cambiarCantidad(-1)" class="btn btn-square btn-sm">-</button>
                        <input type="number" id="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" class="input input-bordered w-20 text-center" />
                        <button onclick="cambiarCantidad(1)" class="btn btn-square btn-sm">+</button>
                    </div>
                </div>
            </div>

            <button onclick="agregarAlCarrito(<?php echo $producto['id']; ?>)" class="btn btn-primary btn-lg w-full mb-4" <?php echo $producto['stock'] <= 0 ? 'disabled' : ''; ?>>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                </svg>
                Añadir al carrito
            </button>

            <!-- Garantía -->
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Garantía de Solución en 24h - <span class="font-semibold text-success">Gratis</span></span>
            </div>

            <!-- Ver disponibilidad en tienda -->
            <button class="btn btn-outline btn-block mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                Ver disponibilidad en tienda
            </button>
        </div>
    </div>

    <!-- Descripción y Especificaciones -->
    <div class="mt-12">
        <div role="tablist" class="tabs tabs-lifted">
            <input type="radio" name="product_tabs" role="tab" class="tab" aria-label="Descripción" checked />
            <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                <h2 class="text-2xl font-bold mb-4">Descripción</h2>
                <p class="text-gray-700 whitespace-pre-line"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
            </div>

            <input type="radio" name="product_tabs" role="tab" class="tab" aria-label="Especificaciones" />
            <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                <h2 class="text-2xl font-bold mb-4">Especificaciones Técnicas</h2>
                
                <?php if(isset($producto['especificaciones']) && !empty($producto['especificaciones'])): ?>
                    <?php 
                    $specs = json_decode($producto['especificaciones'], true);
                    if($specs && is_array($specs)):
                    ?>
                        <div class="overflow-x-auto">
                            <table class="table">
                                <tbody>
                                    <?php foreach($specs as $key => $value): ?>
                                        <tr>
                                            <th class="bg-base-200"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></th>
                                            <td><?php echo htmlspecialchars($value); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-gray-600">No hay especificaciones técnicas disponibles.</p>
                <?php endif; ?>
            </div>

            <input type="radio" name="product_tabs" role="tab" class="tab" aria-label="Opiniones" />
            <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                <h2 class="text-2xl font-bold mb-4">Opiniones de Clientes</h2>
                <div class="text-center py-8">
                    <p class="text-gray-600">Aún no hay opiniones para este producto.</p>
                    <button class="btn btn-primary mt-4">Escribe la primera opinión</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Relacionados -->
    <div class="mt-12">
        <h2 class="text-3xl font-bold mb-6 tonka-logo">Productos Relacionados</h2>
        <p class="text-gray-600 mb-4">Otros productos que te pueden interesar</p>
        
        <div class="flex gap-4 justify-center">
            <a href="/productos?categoria=<?php echo $producto['categoria_id']; ?>" class="btn btn-primary">
                Ver más productos de <?php echo htmlspecialchars($producto['categoria_nombre']); ?>
            </a>
            <a href="/productos" class="btn btn-outline">
                Ver todos los productos
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer footer-center p-10 bg-gradient-to-r from-gray-900 to-gray-800 text-white mt-12">
    <aside>
        <p class="tonka-logo text-white text-4xl" style="-webkit-text-fill-color: white;">TONKATEK</p>
        <p class="font-semibold"><?php echo SITE_SLOGAN; ?></p>
        <p>© 2026 TonkaTek. Todos los derechos reservados.</p>
    </aside>
    <nav>
        <div class="grid grid-flow-col gap-4">
            <a href="#" class="link link-hover">Sobre nosotros</a>
            <a href="#" class="link link-hover">Contacto</a>
            <a href="#" class="link link-hover">Términos y condiciones</a>
            <a href="#" class="link link-hover">Privacidad</a>
        </div>
    </nav>
</footer>

<script>
    function cambiarCantidad(cambio) {
        const input = document.getElementById('cantidad');
        const nuevaCantidad = parseInt(input.value) + cambio;
        const max = parseInt(input.max);
        
        if (nuevaCantidad >= 1 && nuevaCantidad <= max) {
            input.value = nuevaCantidad;
        }
    }

    function agregarAlCarrito(productoId) {
        const cantidad = parseInt(document.getElementById('cantidad').value);
        
        fetch('/api/carrito/agregar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ producto_id: productoId, cantidad: cantidad })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Producto añadido al carrito');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al añadir al carrito');
        });
    }
</script>
