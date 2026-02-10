<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 tonka-logo">Panel de Administración</h1>

    <!-- Alertas -->
    <?php displayAlert(); ?>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Total Productos</div>
                <div class="stat-value text-primary"><?php echo $stats['total_productos']; ?></div>
                <div class="stat-desc">En catálogo</div>
            </div>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Total Usuarios</div>
                <div class="stat-value text-secondary"><?php echo $stats['total_usuarios']; ?></div>
                <div class="stat-desc">Registrados</div>
            </div>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Total Pedidos</div>
                <div class="stat-value text-accent"><?php echo $stats['total_pedidos']; ?></div>
                <div class="stat-desc">Realizados</div>
            </div>
        </div>
    </div>

    <!-- Gestión de Productos -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title text-2xl">Gestión de Productos</h2>
                <a href="/admin/crear" class="btn btn-primary">Añadir Producto</a>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $prod): ?>
                            <tr>
                                <td><?php echo $prod['id']; ?></td>
                                <td class="font-semibold"><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($prod['marca']); ?></td>
                                <td class="text-primary font-bold"><?php echo formatPrice($prod['precio']); ?></td>
                                <td>
                                    <span class="badge <?php echo $prod['stock'] > 0 ? 'badge-success' : 'badge-error'; ?>">
                                        <?php echo $prod['stock']; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($prod['categoria_nombre']); ?></td>
                                <td>
                                    <div class="flex gap-2">
                                        <button class="btn btn-sm btn-ghost btn-editar" value="<?php echo $prod['id']; ?>">
                                            Editar
                                        </button>
                                        <form method="POST" action="/admin/eliminar/<?php echo $prod['id']; ?>" style="display:inline;" 
                                              onsubmit="return confirm('¿Seguro que quieres eliminar <?php echo addslashes(htmlspecialchars($prod['nombre'])); ?>?');">
                                            <button type="submit" class="btn btn-sm btn-error">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Evento para todos los botones de editar
document.addEventListener('DOMContentLoaded', function() {
    const botonesEditar = document.querySelectorAll('.btn-editar');
    
    botonesEditar.forEach(boton => {
        boton.addEventListener('click', function(event) {
            const productoId = event.target.value;
            window.location.href = '/admin/editar/' + productoId;
        });
    });
});
</script>
