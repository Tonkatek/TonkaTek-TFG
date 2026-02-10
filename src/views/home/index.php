<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: moveGrid 20s linear infinite;
    }
    @keyframes moveGrid {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }
    .category-badge {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: oklch(var(--b1));
        border: 2px solid oklch(var(--bc) / 0.2);
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        color: oklch(var(--bc));
    }
    .category-badge:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        background: linear-gradient(135deg, var(--tonka-primary), var(--tonka-secondary));
        color: white;
        border-color: transparent;
    }
</style>

<!-- Hero Section -->
<div class="hero-section min-h-[60vh] flex items-center justify-center text-white relative">
    <div class="text-center z-10">
        <h1 class="text-6xl md:text-8xl font-black mb-6 tonka-logo text-white" style="-webkit-text-fill-color: white;">
            TONKATEK
        </h1>
        <p class="text-2xl md:text-4xl mb-8 font-light">
            <?php echo SITE_SLOGAN; ?>
        </p>
        <p class="text-lg md:text-xl mb-12 opacity-90">
            Los mejores componentes para tu PC al mejor precio
        </p>
        <a href="/productos" class="btn btn-lg btn-primary text-lg px-8">
            Explorar Productos
        </a>
    </div>
</div>

<!-- Categories -->
<div class="container mx-auto px-4 py-16">
    <h2 class="text-4xl font-bold text-center mb-12 tonka-logo">Categorías</h2>
    <div class="flex flex-wrap justify-center gap-4">
        <?php foreach($categorias as $cat): ?>
            <a href="/productos?categoria=<?php echo $cat['id']; ?>" class="category-badge">
                <span class="text-2xl mr-2"><?php echo $cat['icono']; ?></span>
                <span class="font-semibold"><?php echo $cat['nombre']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Featured Products -->
<div id="productos" class="container mx-auto px-4 py-16">
    <h2 class="text-4xl font-bold text-center mb-12 tonka-logo">Productos Destacados</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach($productos_destacados as $prod): ?>
            <a href="/producto/<?php echo $prod['id']; ?>" class="block">
                <div class="card bg-base-100 shadow-xl product-card">
                    <figure class="px-6 pt-6 h-48 bg-base-200">
                        <img src="<?php echo getProductImage($prod['imagen'] ?? '', $prod['categoria_nombre'] ?? ''); ?>" 
                             alt="<?php echo htmlspecialchars($prod['nombre']); ?>" 
                             class="h-full w-full object-contain" />
                    </figure>
                    <div class="card-body">
                        <h3 class="card-title text-lg font-bold"><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                        <p class="text-sm text-base-content/60"><?php echo htmlspecialchars($prod['marca']); ?></p>
                        <div class="flex items-center justify-between mt-4">
                            <span class="price-tag text-2xl"><?php echo formatPrice($prod['precio']); ?></span>
                            <?php if($prod['stock'] > 0): ?>
                                <span class="badge badge-success">Disponible</span>
                            <?php else: ?>
                                <span class="badge badge-error">Agotado</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions justify-end mt-4">
                            <button onclick="event.preventDefault(); event.stopPropagation(); agregarAlCarrito(<?php echo $prod['id']; ?>)" class="btn btn-primary btn-sm w-full">
                                Añadir al Carrito
                            </button>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-12">
        <a href="/productos" class="btn btn-lg btn-outline btn-primary">Ver Todos los Productos</a>
    </div>
</div>

<!-- Footer -->
<footer class="footer footer-center p-10 bg-gradient-to-r from-gray-900 to-gray-800 text-white">
    <aside>
        <p class="tonka-logo text-white text-4xl" style="-webkit-text-fill-color: white;">TONKATEK</p>
        <p class="font-semibold"><?php echo SITE_SLOGAN; ?></p>
        <p>© 2026 TonkaTek. Todos los derechos reservados.</p>
    </aside>
</footer>

<script>
    function agregarAlCarrito(productoId) {
        fetch('/api/carrito/agregar', {
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
