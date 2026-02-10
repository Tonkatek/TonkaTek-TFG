<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado con botón volver -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold tonka-logo">
                Pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?>
            </h1>
            <a href="/pedidos" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver a Mis Pedidos
            </a>
        </div>

        <?php displayAlert(); ?>

        <!-- Información del pedido -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Estado y fecha -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg">Estado del Pedido</h2>
                    <?php 
                        $estado_color = [
                            'pendiente' => 'badge-warning',
                            'procesando' => 'badge-info',
                            'enviado' => 'badge-primary',
                            'entregado' => 'badge-success',
                            'cancelado' => 'badge-error'
                        ];
                        $color = $estado_color[$pedido['estado']] ?? 'badge-neutral';
                    ?>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge <?php echo $color; ?> badge-lg">
                            <?php echo ucfirst($pedido['estado']); ?>
                        </span>
                    </div>
                    <div class="divider"></div>
                    <div class="text-sm space-y-2">
                        <p><strong>Fecha de pedido:</strong><br><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                        <p><strong>Última actualización:</strong><br><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_actualizacion'])); ?></p>
                    </div>
                </div>
            </div>

            <!-- Dirección de envío -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg">Dirección de Envío</h2>
                    <div class="mt-2">
                        <p class="whitespace-pre-line"><?php echo htmlspecialchars($pedido['direccion_envio']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos del pedido -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-xl mb-4">Productos</h2>
                
                <div class="space-y-4">
                    <?php foreach ($detalles as $item): ?>
                        <div class="flex items-center gap-4 p-4 bg-base-200 rounded-lg">
                            <img src="<?php echo getProductImage($item['imagen'] ?? '', $item['categoria_nombre'] ?? ''); ?>" 
                                 alt="<?php echo htmlspecialchars($item['nombre']); ?>" 
                                 class="w-20 h-20 object-contain rounded bg-white p-2" />
                            <div class="flex-1">
                                <h3 class="font-bold"><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                <p class="text-sm text-base-content/60">
                                    <?php if (!empty($item['marca'])): ?>
                                        <?php echo htmlspecialchars($item['marca']); ?>
                                        <?php if (!empty($item['modelo'])): ?>
                                            - <?php echo htmlspecialchars($item['modelo']); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>
                                <p class="text-sm mt-1">
                                    <span class="font-semibold">Cantidad:</span> <?php echo $item['cantidad']; ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-base-content/60">
                                    <?php echo formatPrice($item['precio_unitario']); ?> c/u
                                </p>
                                <p class="price-tag text-xl font-bold mt-1">
                                    <?php echo formatPrice($item['subtotal']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Total -->
                <div class="divider"></div>
                <div class="flex justify-between items-center text-2xl font-bold">
                    <span>Total:</span>
                    <span class="price-tag"><?php echo formatPrice($pedido['total']); ?></span>
                </div>
            </div>
        </div>

        <!-- Botón volver (móvil) -->
        <div class="mt-6 text-center">
            <a href="/pedidos" class="btn btn-outline btn-primary">Volver a Mis Pedidos</a>
        </div>
    </div>
</div>
