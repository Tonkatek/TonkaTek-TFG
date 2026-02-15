<?php
/**
 * Helper para manejo de imágenes de productos
 */

/**
 * Obtiene la URL de la imagen del producto
 * Si no existe, devuelve un placeholder
 */
function getProductImage($imagen, $categoria = '') {
    if (empty($imagen)) {
        return getPlaceholderImage($categoria);
    }
    
    // Ruta de la imagen en assets/images
    $imagePath = '/assets/images/' . $imagen;
    $fullPath = __DIR__ . '/../assets/images/' . $imagen;
    
    // Si la imagen existe, devolverla
    if (file_exists($fullPath)) {
        return $imagePath;
    }
    
    // Si no existe, devolver placeholder
    return getPlaceholderImage($categoria);
}

/**
 * Genera URL de placeholder según categoría
 */
function getPlaceholderImage($categoria = '') {
    // Mapeo de categorías a iconos
    $categoriaIcons = [
        'procesadores' => '💻',
        'gpus' => '🎮',
        'ram' => '🧠',
        'placas' => '⚡',
        'discos' => '💾',
        'fuentes' => '🔌'
    ];
    
    $categoria_lower = strtolower($categoria);
    
    // Buscar icono para la categoría
    foreach ($categoriaIcons as $key => $icon) {
        if (strpos($categoria_lower, $key) !== false) {
            return createSVGPlaceholder($icon);
        }
    }
    
    // Icono por defecto
    return createSVGPlaceholder('🖥️');
}

/**
 * Crea un SVG placeholder con el icono
 */
function createSVGPlaceholder($icon = '🖥️') {
    $svg = '<svg width="400" height="400" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
            </linearGradient>
        </defs>
        <rect width="400" height="400" fill="url(#grad)"/>
        <text x="50%" y="50%" font-size="120" text-anchor="middle" dy=".3em">' . $icon . '</text>
    </svg>';
    
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

/**
 * Obtiene imagen para categoría específica
 */
function getCategoryImage($nombre_categoria) {
    $iconMap = [
        'Procesadores' => '💻',
        'GPUs' => '🎮',
        'RAM' => '🧠',
        'Placas Base' => '⚡',
        'Discos' => '💾',
        'Fuentes' => '🔌'
    ];
    
    $icon = $iconMap[$nombre_categoria] ?? '🖥️';
    return createSVGPlaceholder($icon);
}
?>
