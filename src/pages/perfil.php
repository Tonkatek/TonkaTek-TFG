<?php
require_once '../config/config.php';
require_once '../includes/classes/Usuario.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$usuario = new Usuario();
$datos = $usuario->obtenerPorId($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - TonkaTek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50">
    <div class="navbar bg-white shadow">
        <div class="flex-1">
            <a href="../index.php" class="btn btn-ghost text-xl">TONKATEK</a>
        </div>
        <div class="flex-none">
            <a href="logout.php" class="btn btn-ghost">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Mi Perfil</h1>
        <div class="card bg-white shadow-xl max-w-2xl">
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nombre</p>
                        <p class="font-semibold"><?php echo htmlspecialchars($datos['nombre']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold"><?php echo htmlspecialchars($datos['email']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Rol</p>
                        <span class="badge badge-primary"><?php echo $datos['rol']; ?></span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Miembro desde</p>
                        <p class="font-semibold"><?php echo date('d/m/Y', strtotime($datos['fecha_registro'])); ?></p>
                    </div>
                </div>
                <div class="card-actions justify-end mt-6">
                    <a href="pedidos.php" class="btn btn-primary">Ver Pedidos</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
