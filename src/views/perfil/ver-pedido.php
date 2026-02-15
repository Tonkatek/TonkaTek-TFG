<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?> - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --tonka-primary: #FF6B35;
            --tonka-secondary: #004E89;
            --tonka-accent: #1A1A2E;
        }
        body { font-family: 'Exo 2', sans-serif; }
        .tonka-logo {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            background: linear-gradient(135deg, var(--tonka-primary), var(--tonka-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .price-tag { font-family: 'Orbitron', sans-serif; color: var(--tonka-primary); font-weight: 700; }
    </style>
</head>
<body>
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
                        $estado_texto = [
                            'pendiente' => 'Pendiente',
                            'procesando' => 'En Curso',
                            'enviado' => 'Enviado',
                            'entregado' => 'Entregado',
                            'cancelado' => 'Cancelado'
                        ];
                        $color = $estado_color[$pedido['estado']] ?? 'badge-neutral';
                        $texto = $estado_texto[$pedido['estado']] ?? ucfirst($pedido['estado']);
                    ?>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge <?php echo $color; ?> badge-lg">
                            <?php echo $texto; ?>
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

        <!-- Panel de Control de Estado (solo para administradores) -->
        <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin'): ?>
        <div class="card bg-gradient-to-br from-primary to-secondary text-white shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title text-xl mb-4">🔧 Panel de Administrador - Gestionar Estado</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <?php if ($pedido['estado'] === 'pendiente'): ?>
                        <!-- Desde Pendiente -->
                        <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                            <input type="hidden" name="estado" value="procesando">
                            <button type="submit" class="btn btn-info w-full">
                                ▶️ Marcar como En Curso
                            </button>
                        </form>
                        <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado" 
                              onsubmit="return confirm('¿Seguro que deseas cancelar este pedido?');">
                            <input type="hidden" name="estado" value="cancelado">
                            <button type="submit" class="btn btn-error w-full">
                                ❌ Cancelar Pedido
                            </button>
                        </form>
                        
                    <?php elseif ($pedido['estado'] === 'procesando'): ?>
                        <!-- Desde En Curso -->
                        <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                            <input type="hidden" name="estado" value="enviado">
                            <button type="submit" class="btn btn-success w-full">
                                📦 Marcar como Enviado
                            </button>
                        </form>
                        <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                            <input type="hidden" name="estado" value="pendiente">
                            <button type="submit" class="btn btn-warning w-full">
                                ⬅️ Volver a Pendiente
                            </button>
                        </form>
                        <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado" 
                              onsubmit="return confirm('¿Seguro que deseas cancelar este pedido? El stock será devuelto.');">
                            <input type="hidden" name="estado" value="cancelado">
                            <button type="submit" class="btn btn-error w-full">
                                ❌ Cancelar Pedido
                            </button>
                        </form>
                        
                    <?php elseif ($pedido['estado'] === 'enviado'): ?>
                        <!-- Desde Enviado -->
                        <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                            <input type="hidden" name="estado" value="entregado">
                            <button type="submit" class="btn btn-accent w-full">
                                ✅ Marcar como Entregado
                            </button>
                        </form>
                        <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                            <input type="hidden" name="estado" value="procesando">
                            <button type="submit" class="btn btn-info w-full">
                                ⬅️ Volver a En Curso
                            </button>
                        </form>
                        <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado" 
                              onsubmit="return confirm('¿Seguro que deseas cancelar este pedido? El stock será devuelto.');">
                            <input type="hidden" name="estado" value="cancelado">
                            <button type="submit" class="btn btn-error w-full">
                                ❌ Cancelar Pedido
                            </button>
                        </form>
                        
                    <?php elseif ($pedido['estado'] === 'entregado'): ?>
                        <!-- Pedido Entregado -->
                        <div class="col-span-3">
                            <div class="alert alert-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Pedido completado exitosamente</span>
                            </div>
                        </div>
                        
                    <?php elseif ($pedido['estado'] === 'cancelado'): ?>
                        <!-- Pedido Cancelado -->
                        <div class="col-span-3">
                            <div class="alert alert-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Este pedido fue cancelado</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="divider"></div>
                <p class="text-sm opacity-80">
                    💡 <strong>Nota:</strong> Al cambiar a "En Curso" se descuenta el stock. Al cancelar un pedido en curso, se restaura el stock.
                </p>
            </div>
        </div>
        <?php endif; ?>

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

<script>
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}
</script>
</body>
</html>
