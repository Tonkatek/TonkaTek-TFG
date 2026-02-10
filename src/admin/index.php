<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/classes/Producto.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../pages/login.php');
}

$producto = new Producto();
$productos = $producto->obtenerTodos(1, 100);

$database = new Database();
$conn = $database->getConnection();

// Estadísticas básicas
$stats = [];
$stats['total_productos'] = $conn->query("SELECT COUNT(*) FROM productos")->fetchColumn();
$stats['total_usuarios'] = $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$stats['total_pedidos'] = $conn->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Admin - TonkaTek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@900&family=Exo+2:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Exo 2', sans-serif; }
        .tonka-logo { font-family: 'Orbitron', sans-serif; }
    </style>
    <script type="module" src="./admin.js"></script>
</head>
<body class="bg-gray-50">
    <div class="navbar bg-gradient-to-r from-purple-600 to-blue-600 text-white">
        <div class="flex-1">
            <span class="tonka-logo text-2xl px-4">TONKATEK ADMIN</span>
        </div>
        <div class="flex-none gap-2">
            <a href="/" class="btn btn-ghost text-white">Ver Tienda</a>
            <a href="/logout" class="btn btn-ghost text-white">Salir</a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">Panel de Administración</h1>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats shadow">
                <div class="stat">
                    <div class="stat-title">Total Productos</div>
                    <div class="stat-value text-primary"><?php echo $stats['total_productos']; ?></div>
                    <div class="stat-desc">En catálogo</div>
                </div>
            </div>
            <div class="stats shadow">
                <div class="stat">
                    <div class="stat-title">Total Usuarios</div>
                    <div class="stat-value text-secondary"><?php echo $stats['total_usuarios']; ?></div>
                    <div class="stat-desc">Registrados</div>
                </div>
            </div>
            <div class="stats shadow">
                <div class="stat">
                    <div class="stat-title">Total Pedidos</div>
                    <div class="stat-value text-accent"><?php echo $stats['total_pedidos']; ?></div>
                    <div class="stat-desc">Realizados</div>
                </div>
            </div>
        </div>

        <!-- Gestión de Productos -->
        <div class="card bg-white shadow-xl">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title text-2xl">Gestión de Productos</h2>
                    <a href="/admin/crear" class="btn bg-blue-500 text-white p-2">Añadir Producto</a>
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
                                        <div class="join">
                                            <button class="btn btn-sm join-item btn-edit" value="<?php echo $prod["id"]; ?>">Editar</button>
                                            <button class="btn btn-sm btn-error join-item btn-delete" value="<?php echo $prod["id"]; ?>">Eliminar</button>
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
</body>
</html>
