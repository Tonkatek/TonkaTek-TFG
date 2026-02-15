<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 tonka-logo">Mis Pedidos</h1>

    <?php displayAlert(); ?>

    <?php if (count($pedidos) > 0): ?>
        <div class="space-y-6">
            <?php foreach ($pedidos as $pedido): 
                // Determinar color del badge según estado
                $estado_color = [
                    'pendiente' => 'badge-warning',
                    'procesando' => 'badge-info',
                    'enviado' => 'badge-primary',
                    'entregado' => 'badge-success',
                    'cancelado' => 'badge-error'
                ];
                $color = $estado_color[$pedido['estado']] ?? 'badge-neutral';
            ?>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <h2 class="card-title">
                                    Pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?>
                                    <span class="badge <?php echo $color; ?> ml-2">
                                        <?php echo ucfirst($pedido['estado']); ?>
                                    </span>
                                </h2>
                                <div class="text-sm text-base-content/60 mt-2 space-y-1">
                                    <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                                    <p><strong>Total:</strong> <span class="price-tag"><?php echo formatPrice($pedido['total']); ?></span></p>
                                    <p><strong>Productos:</strong> <?php echo $pedido['total_items']; ?> artículo<?php echo $pedido['total_items'] != 1 ? 's' : ''; ?></p>
                                    <p><strong>Dirección de envío:</strong> <?php echo htmlspecialchars($pedido['direccion_envio']); ?></p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <a href="/pedido/<?php echo $pedido['id']; ?>" class="btn btn-primary btn-sm">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-20">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h2 class="text-2xl font-bold mb-2">No tienes pedidos</h2>
            <p class="text-gray-600 mb-6">Cuando realices tu primera compra, aparecerá aquí</p>
            <a href="/productos" class="btn btn-primary">Ir a Productos</a>
        </div>
    <?php endif; ?>
</div>
