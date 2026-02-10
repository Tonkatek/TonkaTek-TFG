# TonkaTek - Tienda Online de Componentes PC

**TFG - Desarrollo de Aplicaciones Web (DAW)**

> *Tus tonki precios de confianza* 

## Descripción

TonkaTek es una tienda online completa para la venta de componentes de PC, desarrollada como Trabajo Final de Grado. Incluye un sistema completo de gestión de productos, usuarios, carrito de compras y panel de administración.

## Tecnologías Utilizadas

- **Backend**: PHP 8.2
- **Base de Datos**: MySQL 8.0
- **Frontend**: 
  - TailwindCSS
  - DaisyUI
  - JavaScript vanilla
- **Contenedorización**: Docker & Docker Compose
- **Gestión BD**: phpMyAdmin

##  Estructura del Proyecto

```
tonkatek/
├── database/
│   └── init.sql              # Script de inicialización de BD
├── src/
│   ├── config/
│   │   ├── database.php      # Configuración de conexión BD
│   │   └── config.php        # Configuración general
│   ├── includes/
│   │   └── classes/
│   │       ├── Usuario.php   # Gestión de usuarios
│   │       ├── Producto.php  # Gestión de productos
│   │       └── Carrito.php   # Gestión del carrito
│   ├── pages/
│   │   ├── login.php         # Login y registro
│   │   ├── productos.php     # Catálogo de productos
│   │   ├── carrito.php       # Carrito de compras
│   │   ├── perfil.php        # Perfil de usuario
│   │   └── pedidos.php       # Historial de pedidos
│   ├── admin/
│   │   └── index.php         # Panel de administración
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/           # Imágenes de productos
│   └── index.php             # Página principal
├── docker-compose.yml        # Configuración Docker Compose
├── Dockerfile                # Configuración Docker
└── README.md
```

## Instalación y Uso con Docker

### Prerrequisitos

- Docker
- Docker Compose

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone <tu-repositorio>
cd tonkatek
```

2. **Construir y levantar los contenedores**
```bash
docker-compose up -d --build
```

3. **Acceder a la aplicación**
- **Web principal**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
  - Usuario: `tonkatek_user`
  - Contraseña: `tonkatek_pass`

### Detener los contenedores
```bash
docker-compose down
```

### Ver logs
```bash
docker-compose logs -f
```

## Usuarios de Prueba

### Administrador
- **Email**: admin@tonkatek.com
- **Contraseña**: admin123

### Cliente
- **Email**: cliente@demo.com
- **Contraseña**: admin123

## Funcionalidades

### Para Clientes
- ✅ Registro y autenticación de usuarios
- ✅ Navegación por categorías de productos
- ✅ Búsqueda y filtros avanzados
- ✅ Carrito de compras con gestión de cantidades
- ✅ Historial de pedidos
- ✅ Gestión de perfil personal

### Para Administradores
- ✅ Panel de administración completo
- ✅ CRUD de productos
- ✅ Gestión de categorías
- ✅ Visualización de pedidos
- ✅ Gestión de usuarios

## Categorías de Productos

1. **Procesadores** 
2. **Tarjetas Gráficas** 
3. **Memorias RAM** 
4. **Placas Base** 
5. **Discos Duros** 
6. **Fuentes de Alimentación** 

## Base de Datos

La base de datos incluye las siguientes tablas:

- `usuarios` - Gestión de usuarios y roles
- `categorias` - Categorías de productos
- `productos` - Catálogo de productos
- `pedidos` - Pedidos realizados
- `pedidos_detalle` - Detalle de cada pedido
- `carrito` - Carrito de compras temporal

## Diseño

El diseño utiliza:
- Tipografía: **Orbitron** (logos y títulos) + **Exo 2** (cuerpo)
- Paleta de colores personalizada
- Animaciones CSS modernas
- Diseño responsive con Mobile-First
- Componentes de DaisyUI

## Configuración

### Variables de Entorno (docker-compose.yml)

```yaml
DB_HOST=db
DB_NAME=tonkatek_db
DB_USER=tonkatek_user
DB_PASSWORD=tonkatek_pass
```

### Personalización

Puedes modificar las variables en `src/config/config.php`:
```php
define('SITE_NAME', 'TonkaTek');
define('SITE_SLOGAN', 'Tus tonki precios de confianza');
define('ITEMS_PER_PAGE', 12);
```

## Notas de Desarrollo

- PHP 8.2 con extensiones PDO y MySQL
- Arquitectura MVC simplificada
- Separación de lógica de negocio en clases
- Seguridad: password_hash para contraseñas
- Sesiones seguras con httponly cookies
- Validación y sanitización de datos
- Prepared statements para prevenir SQL injection

## Próximas Mejoras

- [ ] Sistema de pagos (Stripe/PayPal)
- [ ] Notificaciones por email
- [ ] Sistema de valoraciones y comentarios
- [ ] Comparador de productos
- [ ] Lista de deseos
- [ ] Panel de estadísticas avanzadas

##  Autor

Proyecto desarrollado por Pablo Gómez Sánchez y Alejandro Nuñez Bonaque como TFG del Grado Superior de DAW

---

**TonkaTek** - *Tus tonki precios de confianza* 
