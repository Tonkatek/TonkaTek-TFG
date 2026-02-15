#!/bin/bash

echo "🔍 VERIFICACIÓN DEL PANEL DE ADMINISTRACIÓN - TONKATEK"
echo "======================================================"
echo ""

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para verificar archivos
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✅${NC} $2"
        return 0
    else
        echo -e "${RED}❌${NC} $2 - FALTA: $1"
        return 1
    fi
}

# Función para verificar contenido
check_content() {
    if grep -q "$2" "$1" 2>/dev/null; then
        echo -e "${GREEN}✅${NC} $3"
        return 0
    else
        echo -e "${RED}❌${NC} $3 - No encontrado en $1"
        return 1
    fi
}

echo "📁 Verificando estructura de archivos..."
echo ""

# Verificar archivos principales
check_file "src/controllers/AdminController.php" "Controlador de Admin"
check_file "src/models/Producto.php" "Modelo de Producto"
check_file "src/views/admin/index.php" "Vista principal de Admin"
check_file "src/views/admin/form.php" "Formulario de Admin"
check_file "src/assets/js/admin.js" "JavaScript de Admin"
check_file "src/routes.php" "Archivo de rutas"
check_file "src/.htaccess" "Archivo .htaccess"

echo ""
echo "🔧 Verificando rutas en routes.php..."
echo ""

check_content "src/routes.php" "admin.*AdminController@index" "Ruta GET /admin"
check_content "src/routes.php" "admin/crear.*AdminController@crear" "Ruta POST /admin/crear"
check_content "src/routes.php" "admin/editar.*AdminController@editar" "Ruta POST /admin/editar"
check_content "src/routes.php" "admin/eliminar.*AdminController@eliminar" "Ruta POST /admin/eliminar"

echo ""
echo "🎯 Verificando métodos del controlador..."
echo ""

check_content "src/controllers/AdminController.php" "function index()" "Método index()"
check_content "src/controllers/AdminController.php" "function crear()" "Método crear()"
check_content "src/controllers/AdminController.php" "function editar(" "Método editar()"
check_content "src/controllers/AdminController.php" "function eliminar(" "Método eliminar()"
check_content "src/controllers/AdminController.php" "function mostrarCrear()" "Método mostrarCrear()"
check_content "src/controllers/AdminController.php" "function mostrarEditar(" "Método mostrarEditar()"

echo ""
echo "💾 Verificando métodos del modelo Producto..."
echo ""

check_content "src/models/Producto.php" "function crear(" "Método crear()"
check_content "src/models/Producto.php" "function actualizar(" "Método actualizar()"
check_content "src/models/Producto.php" "function eliminar(" "Método eliminar()"
check_content "src/models/Producto.php" "function obtenerPorId(" "Método obtenerPorId()"
check_content "src/models/Producto.php" "function obtenerTodos(" "Método obtenerTodos()"

echo ""
echo "🎨 Verificando vistas..."
echo ""

check_content "src/views/admin/index.php" "Añadir Producto" "Botón Añadir en vista principal"
check_content "src/views/admin/index.php" "Editar" "Botón Editar en tabla"
check_content "src/views/admin/index.php" "Eliminar" "Botón Eliminar en tabla"
check_content "src/views/admin/index.php" "confirm(" "Confirmación de eliminación"
check_content "src/views/admin/form.php" "form method=\"POST\"" "Formulario POST"

echo ""
echo "⚙️ Verificando configuración..."
echo ""

check_content "src/config/config.php" "function showAlert(" "Función showAlert()"
check_content "src/config/config.php" "function displayAlert(" "Función displayAlert()"
check_content "src/config/config.php" "function isAdmin(" "Función isAdmin()"

echo ""
echo "🐳 Verificando servicios Docker..."
echo ""

if command -v docker-compose &> /dev/null; then
    if docker-compose ps | grep -q "Up"; then
        echo -e "${GREEN}✅${NC} Docker Compose está corriendo"
        
        if docker-compose ps | grep -q "mysql.*Up"; then
            echo -e "${GREEN}✅${NC} MySQL está corriendo"
        else
            echo -e "${RED}❌${NC} MySQL NO está corriendo"
        fi
        
        if docker-compose ps | grep -q "php.*Up"; then
            echo -e "${GREEN}✅${NC} PHP está corriendo"
        else
            echo -e "${RED}❌${NC} PHP NO está corriendo"
        fi
    else
        echo -e "${YELLOW}⚠️${NC}  Docker Compose no está corriendo"
        echo "    Ejecuta: docker-compose up -d"
    fi
else
    echo -e "${YELLOW}⚠️${NC}  Docker Compose no está instalado"
fi

echo ""
echo "======================================================"
echo "📋 RESUMEN DE LA VERIFICACIÓN"
echo "======================================================"
echo ""
echo "Si todos los checks están en verde (✅), el panel admin"
echo "debería funcionar correctamente."
echo ""
echo "Para probar:"
echo "  1. docker-compose up -d"
echo "  2. Ir a http://localhost:8080/login"
echo "  3. Usuario: admin, Contraseña: admin123"
echo "  4. Ir a http://localhost:8080/admin"
echo "  5. Probar botones Añadir/Editar/Eliminar"
echo ""
echo "Ver PRUEBAS_ADMIN.md para guía completa de pruebas."
echo ""
