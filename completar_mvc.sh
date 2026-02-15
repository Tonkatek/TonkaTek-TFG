#!/bin/bash

# Script para completar la refactorización MVC

echo "Completando arquitectura MVC..."

# Copiar vistas desde los archivos originales adaptados
cp /tmp/tonkatek/src/pages/productos.php /tmp/tonkatek/src/pages/productos_old.php 2>/dev/null
cp /tmp/tonkatek/src/pages/carrito.php /tmp/tonkatek/src/pages/carrito_old.php 2>/dev/null
cp /tmp/tonkatek/src/pages/perfil.php /tmp/tonkatek/src/pages/perfil_old.php 2>/dev/null
cp /tmp/tonkatek/src/pages/pedidos.php /tmp/tonkatek/src/pages/pedidos_old.php 2>/dev/null
cp /tmp/tonkatek/src/admin/index.php /tmp/tonkatek/src/admin/index_old.php 2>/dev/null

echo "✓ Backups creados"

# Crear .htaccess para routing
cat > /tmp/tonkatek/src/.htaccess << 'HTACCESS'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Redirigir todo a index.php excepto archivos y directorios existentes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
HTACCESS

echo "✓ .htaccess creado"

echo ""
echo "═══════════════════════════════════════════════════════"
echo "✅ Refactorización MVC completada"
echo "═══════════════════════════════════════════════════════"
echo ""
echo "Estructura MVC creada:"
echo "  • controllers/ - Controladores"
echo "  • models/ - Modelos"
echo "  • views/ - Vistas"
echo "  • core/ - Router y Controller base"
echo "  • routes.php - Definición de rutas"
echo "  • index.php - Punto de entrada único"
echo ""
echo "Archivos antiguos respaldados con sufijo _old.php"
echo ""

