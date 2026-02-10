<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex gap-6">
        <!-- Sidebar Filtros -->
        <div class="w-64 hidden lg:block">
            <div class="card bg-base-100 shadow-lg sticky top-24">
                <div class="card-body">
                    <h3 class="font-bold text-lg mb-4">Categorías</h3>
                    <ul class="menu p-0">
                        <li><a href="/productos" class="<?php echo !isset($categoria_id) || !$categoria_id ? 'active' : ''; ?>">Todos</a></li>
                        <?php foreach($categorias as $cat): ?>
                            <li>
                                <a href="/productos?categoria=<?php echo $cat['id']; ?>" class="<?php echo isset($categoria_id) && $categoria_id == $cat['id'] ? 'active' : ''; ?>">
                                    <?php echo $cat['icono']; ?> <?php echo $cat['nombre']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="flex-1">
            <div class="mb-6">
                <h1 class="text-3xl font-bold tonka-logo"><?php echo $categoria_actual; ?></h1>
                <p class="text-base-content/60 mt-2"><?php echo $total_productos; ?> productos encontrados</p>
            </div>

            <?php if (count($productos) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php foreach($productos as $prod): ?>
                        <a href="/producto/<?php echo $prod['id']; ?>" class="block">
                            <div class="card bg-base-100 shadow-xl product-card">
                                <figure class="px-6 pt-6 h-48 bg-base-200">
                                    <img src="<?php echo getProductImage($prod['imagen'] ?? '', $prod['categoria_nombre'] ?? ''); ?>" 
                                         alt="<?php echo htmlspecialchars($prod['nombre']); ?>" 
                                         class="h-full w-full object-contain" />
                                </figure>
                                <div class="card-body">
                                    <h2 class="card-title text-lg"><?php echo htmlspecialchars($prod['nombre']); ?></h2>
                                    <p class="text-sm text-base-content/60"><?php echo htmlspecialchars($prod['marca']); ?></p>
                                    <p class="text-xs text-base-content/50 mt-2 line-clamp-2"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                    <div class="flex items-center justify-between mt-4">
                                        <span class="price-tag text-2xl font-bold"><?php echo formatPrice($prod['precio']); ?></span>
                                        <?php if($prod['stock'] > 0): ?>
                                            <span class="badge badge-success">Stock: <?php echo $prod['stock']; ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-error">Agotado</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-actions mt-4">
                                        <button onclick="event.preventDefault(); event.stopPropagation(); agregarAlCarrito(<?php echo $prod['id']; ?>)" class="btn btn-primary btn-sm w-full" <?php echo $prod['stock'] <= 0 ? 'disabled' : ''; ?>>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                            </svg>
                                            Añadir al Carrito
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Paginación -->
                <?php if ($total_paginas > 1): ?>
                    <div class="flex justify-center mt-8">
                        <div class="join">
                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <a href="/productos?page=<?php echo $i; ?><?php echo isset($categoria_id) && $categoria_id ? '&categoria=' . $categoria_id : ''; ?><?php echo isset($busqueda) && $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>" 
                                   class="join-item btn <?php echo $i == $page ? 'btn-active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-20">
                    <p class="text-2xl text-base-content/50">No se encontraron productos</p>
                    <a href="/productos" class="btn btn-primary mt-4">Ver todos los productos</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function agregarAlCarrito(productoId) {
        fetch('/api/carrito/agregar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ producto_id: productoId })
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
