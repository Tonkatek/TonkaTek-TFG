<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/classes/Producto.php';

$producto = new Producto();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
$busqueda = isset($_GET['busqueda']) ? sanitizeInput($_GET['busqueda']) : null;

$productos = $producto->obtenerTodos($page, ITEMS_PER_PAGE, $categoria_id, $busqueda);
$total_productos = $producto->contarTotal($categoria_id, $busqueda);
$total_paginas = ceil($total_productos / ITEMS_PER_PAGE);

// Obtener categorías
$database = new Database();
$conn = $database->getConnection();
$categorias_query = "SELECT * FROM categorias ORDER BY nombre";
$categorias_stmt = $conn->prepare($categorias_query);
$categorias_stmt->execute();
$categorias = $categorias_stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener nombre de categoría actual
$categoria_actual = 'Todos los Productos';
if ($categoria_id) {
    $cat_query = "SELECT nombre FROM categorias WHERE id = :id";
    $cat_stmt = $conn->prepare($cat_query);
    $cat_stmt->bindParam(':id', $categoria_id);
    $cat_stmt->execute();
    $cat_row = $cat_stmt->fetch(PDO::FETCH_ASSOC);
    if ($cat_row) {
        $categoria_actual = $cat_row['nombre'];
    }
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Exo 2', sans-serif; }
        .tonka-logo { font-family: 'Orbitron', sans-serif; font-weight: 900; }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .price-tag { font-family: 'Orbitron', sans-serif; color: #FF6B35; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <div class="navbar bg-white shadow-lg sticky top-0 z-50">
        <div class="flex-1">
            <a href="../index.php" class="tonka-logo text-3xl px-4 bg-gradient-to-r from-orange-500 to-blue-600 bg-clip-text text-transparent">TONKATEK</a>
        </div>
        <div class="flex-none gap-2">
            <form method="GET" class="form-control">
                <input type="text" name="busqueda" value="<?php echo htmlspecialchars($busqueda ?? ''); ?>" placeholder="Buscar..." class="input input-bordered w-64" />
            </form>
            <a href="carrito.php" class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span class="badge badge-sm badge-primary indicator-item">0</span>
                </div>
            </a>
            <?php if(isLoggedIn()): ?>
                <div class="dropdown dropdown-end">
                    <div tabindex="0" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full bg-primary text-white flex items-center justify-center">
                            <span><?php echo strtoupper(substr($_SESSION['nombre'], 0, 1)); ?></span>
                        </div>
                    </div>
                    <ul tabindex="0" class="menu dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52 z-50">
                        <li><a href="perfil.php">Mi Perfil</a></li>
                        <li><a href="pedidos.php">Mis Pedidos</a></li>
                        <?php if(isAdmin()): ?>
                            <li><a href="../admin/index.php">Panel Admin</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Cerrar Sesión</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="flex gap-6">
            <!-- Sidebar Filtros -->
            <div class="w-64 hidden lg:block">
                <div class="card bg-white shadow-lg sticky top-24">
                    <div class="card-body">
                        <h3 class="font-bold text-lg mb-4">Categorías</h3>
                        <ul class="menu p-0">
                            <li><a href="productos.php" class="<?php echo !$categoria_id ? 'active' : ''; ?>">Todos</a></li>
                            <?php foreach($categorias as $cat): ?>
                                <li>
                                    <a href="productos.php?categoria=<?php echo $cat['id']; ?>" class="<?php echo $categoria_id == $cat['id'] ? 'active' : ''; ?>">
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
                    <p class="text-gray-600 mt-2"><?php echo $total_productos; ?> productos encontrados</p>
                </div>

                <?php if (count($productos) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php foreach($productos as $prod): ?>
                            <div class="card bg-white shadow-xl product-card">
                                <figure class="px-6 pt-6 h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-6xl opacity-50">🖥️</span>
                                    </div>
                                </figure>
                                <div class="card-body">
                                    <h2 class="card-title text-lg"><?php echo htmlspecialchars($prod['nombre']); ?></h2>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($prod['marca']); ?></p>
                                    <p class="text-xs text-gray-500 mt-2 line-clamp-2"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
                                    <div class="flex items-center justify-between mt-4">
                                        <span class="price-tag text-2xl font-bold"><?php echo formatPrice($prod['precio']); ?></span>
                                        <?php if($prod['stock'] > 0): ?>
                                            <span class="badge badge-success">Stock: <?php echo $prod['stock']; ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-error">Agotado</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-actions mt-4">
                                        <button onclick="agregarAlCarrito(<?php echo $prod['id']; ?>)" class="btn btn-primary btn-sm w-full" <?php echo $prod['stock'] <= 0 ? 'disabled' : ''; ?>>
                                            Añadir al Carrito
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Paginación -->
                    <?php if ($total_paginas > 1): ?>
                        <div class="flex justify-center mt-8">
                            <div class="join">
                                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                    <a href="?page=<?php echo $i; ?><?php echo $categoria_id ? '&categoria=' . $categoria_id : ''; ?><?php echo $busqueda ? '&busqueda=' . urlencode($busqueda) : ''; ?>" 
                                       class="join-item btn <?php echo $i == $page ? 'btn-active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-20">
                        <p class="text-2xl text-gray-500">No se encontraron productos</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function agregarAlCarrito(productoId) {
            fetch('../api/carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'agregar', producto_id: productoId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto añadido al carrito');
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>
