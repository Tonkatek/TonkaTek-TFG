<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 tonka-logo">Mi Perfil</h1>
    <div class="card bg-white shadow-xl max-w-2xl">
        <div class="card-body">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nombre</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($usuario['email']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Rol</p>
                    <span class="badge badge-primary"><?php echo $usuario['rol']; ?></span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Miembro desde</p>
                    <p class="font-semibold"><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></p>
                </div>
                <?php if(!empty($usuario['telefono'])): ?>
                <div>
                    <p class="text-sm text-gray-600">Teléfono</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($usuario['telefono']); ?></p>
                </div>
                <?php endif; ?>
                <?php if(!empty($usuario['direccion'])): ?>
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">Dirección</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($usuario['direccion']); ?></p>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-actions justify-end mt-6">
                <a href="/pedidos" class="btn btn-primary">Ver Pedidos</a>
                <a href="/" class="btn btn-ghost">Volver al Inicio</a>
            </div>
        </div>
    </div>
</div>
