<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #<?php echo $pedido['id']; ?> - <?php echo SITE_NAME; ?></title>
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
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold tonka-logo">Pedido #<?php echo $pedido['id']; ?></h1>
        <a href="/admin/pedidos" class="btn btn-ghost">← Volver a Pedidos</a>
    </div>

    <!-- Alertas -->
    <?php displayAlert(); ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Pedido -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-xl mb-6">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4">Detalles del Pedido</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Fecha del Pedido</p>
                            <p class="font-semibold"><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Estado Actual</p>
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
                            <p><span class="badge <?php echo $clase; ?> badge-lg"><?php echo $texto; ?></span></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Dirección de Envío</p>
                            <p class="font-semibold"><?php echo nl2br(htmlspecialchars($pedido['direccion_envio'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos del Pedido -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4">Productos</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio Unit.</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detalles as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <?php if ($item['imagen']): ?>
                                                    <div class="avatar">

                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="font-bold"><?php echo htmlspecialchars($item['nombre']); ?></div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo htmlspecialchars($item['marca'] . ' ' . $item['modelo']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-semibold"><?php echo formatPrice($item['precio_unitario']); ?></td>
                                        <td>
                                            <span class="badge badge-neutral"><?php echo $item['cantidad']; ?></span>
                                        </td>
                                        <td class="text-primary font-bold"><?php echo formatPrice($item['subtotal']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="font-bold text-lg">
                                    <td colspan="3" class="text-right">TOTAL:</td>
                                    <td class="text-primary"><?php echo formatPrice($pedido['total']); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Control de Estado -->
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-xl sticky top-4">
                <div class="card-body">
                    <h2 class="card-title text-xl mb-4">Gestionar Estado</h2>
                    
                    <div class="space-y-3">
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
                                <button type="submit" class="btn btn-error btn-outline w-full">
                                    ❌ Cancelar Pedido
                                </button>
                            </form>
                            
                        <?php elseif ($pedido['estado'] === 'procesando'): ?>
                            <!-- Desde En Curso -->
                            <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                                <input type="hidden" name="estado" value="enviado">
                                <button type="submit" class="btn btn-primary w-full">
                                    📦 Marcar como Enviado
                                </button>
                            </form>
                            <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                                <input type="hidden" name="estado" value="pendiente">
                                <button type="submit" class="btn btn-warning btn-outline w-full">
                                    ⬅️ Volver a Pendiente
                                </button>
                            </form>
                            <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado"
                                  onsubmit="return confirm('¿Seguro que deseas cancelar este pedido? El stock será devuelto.');">
                                <input type="hidden" name="estado" value="cancelado">
                                <button type="submit" class="btn btn-error btn-outline w-full">
                                    ❌ Cancelar Pedido
                                </button>
                            </form>
                            
                        <?php elseif ($pedido['estado'] === 'enviado'): ?>
                            <!-- Desde Enviado -->
                            <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                                <input type="hidden" name="estado" value="entregado">
                                <button type="submit" class="btn btn-success w-full">
                                    ✅ Marcar como Entregado
                                </button>
                            </form>
                            <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado">
                                <input type="hidden" name="estado" value="procesando">
                                <button type="submit" class="btn btn-info btn-outline w-full">
                                    ⬅️ Volver a En Curso
                                </button>
                            </form>
                            <form method="POST" action="/admin/pedido/<?php echo $pedido['id']; ?>/estado"
                                  onsubmit="return confirm('¿Seguro que deseas cancelar este pedido? El stock será devuelto.');">
                                <input type="hidden" name="estado" value="cancelado">
                                <button type="submit" class="btn btn-error btn-outline w-full">
                                    ❌ Cancelar Pedido
                                </button>
                            </form>
                            
                        <?php elseif ($pedido['estado'] === 'entregado'): ?>
                            <!-- Pedido Entregado -->
                            <div class="alert alert-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Pedido completado exitosamente</span>
                            </div>
                            
                        <?php elseif ($pedido['estado'] === 'cancelado'): ?>
                            <!-- Pedido Cancelado -->
                            <div class="alert alert-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Este pedido fue cancelado</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Información del Cliente -->
                    <div class="divider">Cliente</div>
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm text-gray-500">Usuario ID</p>
                            <p class="font-semibold">#<?php echo $pedido['usuario_id']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
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
