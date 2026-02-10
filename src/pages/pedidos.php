<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos - TonkaTek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50">
    <div class="navbar bg-white shadow">
        <div class="flex-1">
            <a href="../index.php" class="btn btn-ghost text-xl">TONKATEK</a>
        </div>
        <div class="flex-none">
            <a href="perfil.php" class="btn btn-ghost">Mi Perfil</a>
            <a href="logout.php" class="btn btn-ghost">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Mis Pedidos</h1>
        <div class="text-center py-20">
            <p class="text-xl text-gray-500">No tienes pedidos aún</p>
            <a href="productos.php" class="btn btn-primary mt-4">Explorar Productos</a>
        </div>
    </div>
</body>
</html>
