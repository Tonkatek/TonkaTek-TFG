<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold tonka-logo">Gestión de Pedidos</h1>
        <a href="/admin" class="btn btn-ghost">← Volver al Panel</a>
    </div>

    <!-- Alertas -->
    <?php displayAlert(); ?>

    <!-- Tabla de Pedidos -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-4">Todos los Pedidos</h2>

            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Total</th>
                            <th>Items</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidos)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-500">
                                    No hay pedidos registrados
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td class="font-bold">#<?php echo $pedido['id']; ?></td>
                                    <td><?php echo htmlspecialchars($pedido['usuario_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['usuario_email']); ?></td>
                                    <td class="text-primary font-bold"><?php echo formatPrice($pedido['total']); ?></td>
                                    <td>
                                        <span class="badge badge-neutral"><?php echo $pedido['total_items']; ?> items</span>
                                    </td>
                                    <td>
                                        <?php
                                        $estadoClasses = [
                                            'pendiente' => 'badge-warning',
                                            'procesando' => 'badge-info',
                                            'enviado' => 'badge-primary',
                                            'entregado' => 'badge-success',
                                            'cancelado' => 'badge-error'
                                        ];
                                        $estadoTextos = [
                                            'pendiente' => 'Pendiente',
                                            'procesando' => 'En Curso',
                                            'enviado' => 'Enviado',
                                            'entregado' => 'Entregado',
                                            'cancelado' => 'Cancelado'
                                        ];
                                        $clase = $estadoClasses[$pedido['estado']] ?? 'badge-neutral';
                                        $texto = $estadoTextos[$pedido['estado']] ?? ucfirst($pedido['estado']);
                                        ?>
                                        <span class="badge <?php echo $clase; ?>"><?php echo $texto; ?></span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></td>
                                    <td>
                                        <a href="/admin/pedido/<?php echo $pedido['id']; ?>" class="btn btn-sm btn-primary">
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
