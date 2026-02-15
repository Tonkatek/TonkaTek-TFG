-- ============================================================
-- BASE DE DATOS TONKATEK - CONFIGURACIÓN UTF-8mb4 CORRECTA
-- ============================================================

-- PASO 1: Establecer configuración UTF-8mb4 ANTES de crear nada
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET COLLATION_CONNECTION = utf8mb4_unicode_ci;

-- PASO 2: Crear base de datos CON charset correcto
CREATE DATABASE IF NOT EXISTS tonkatek_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- PASO 3: Usar la base de datos
USE tonkatek_db;

-- PASO 4: Si existen tablas antiguas, eliminarlas
DROP TABLE IF EXISTS pedidos_detalle;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS carrito;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;

-- ========================================
-- TABLA DE USUARIOS
-- ========================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    rol ENUM('admin', 'cliente') DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLA DE CATEGORÍAS
-- ========================================
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(50),
    slug VARCHAR(100) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLA DE PRODUCTOS
-- ========================================
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    categoria_id INT,
    marca VARCHAR(100),
    modelo VARCHAR(100),
    imagen VARCHAR(255),
    especificaciones JSON,
    destacado BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLA DE PEDIDOS
-- ========================================
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    estado ENUM('pendiente', 'procesando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    direccion_envio TEXT NOT NULL,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLA DE DETALLES DE PEDIDOS
-- ========================================
CREATE TABLE pedidos_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABLA DE CARRITO (TEMPORAL)
-- ========================================
CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    session_id VARCHAR(100),
    producto_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- INSERTAR CATEGORÍAS
-- ========================================
INSERT INTO categorias (nombre, descripcion, icono, slug) VALUES
('Procesadores', 'CPUs de AMD e Intel para tu equipo', '🔧', 'procesadores'),
('Tarjetas Gráficas', 'GPUs para gaming y trabajo profesional', '🎮', 'tarjetas-graficas'),
('Memorias RAM', 'Memoria RAM DDR4 y DDR5', '💾', 'memorias-ram'),
('Placas Base', 'Placas base para Intel y AMD', '🔌', 'placas-base'),
('Discos Duros', 'Almacenamiento HDD y SSD', '💿', 'discos-duros'),
('Fuentes de Alimentación', 'PSU certificadas para tu PC', '⚡', 'fuentes-alimentacion');

-- ========================================
-- INSERTAR USUARIOS
-- ========================================
-- Password: admin123 (hash generado con password_hash)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@tonkatek.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'admin'),
('Cliente Demo', 'cliente@demo.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'cliente');

-- ========================================
-- PROCESADORES
-- ========================================
INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, marca, modelo, imagen, especificaciones, destacado) VALUES
('AMD Ryzen 5 5600X', 'Procesador de 6 núcleos para gaming y productividad con excelente relación calidad-precio', 199.99, 25, 1, 'AMD', '5600X', 'ryzen5-5600x.jpg', 
'{"nucleos": 6, "hilos": 12, "frecuencia_base": "3.7 GHz", "frecuencia_turbo": "4.6 GHz", "cache": "35MB", "socket": "AM4", "tdp": "65W"}', TRUE),

('AMD Ryzen 7 5800X', 'Procesador de 8 núcleos de alto rendimiento para gaming y creación de contenido', 299.99, 18, 1, 'AMD', '5800X', 'ryzen7-5800x.jpg',
'{"nucleos": 8, "hilos": 16, "frecuencia_base": "3.8 GHz", "frecuencia_turbo": "4.7 GHz", "cache": "36MB", "socket": "AM4", "tdp": "105W"}', TRUE),

('AMD Ryzen 9 5900X', 'Procesador de 12 núcleos de última generación para máximo rendimiento', 429.99, 12, 1, 'AMD', '5900X', 'ryzen9-5900x.jpg',
'{"nucleos": 12, "hilos": 24, "frecuencia_base": "3.7 GHz", "frecuencia_turbo": "4.8 GHz", "cache": "70MB", "socket": "AM4", "tdp": "105W"}', TRUE),

('Intel Core i5-12400F', 'Procesador de 12ª generación con 6 núcleos para gaming económico', 159.99, 30, 1, 'Intel', 'i5-12400F', 'i5-12400f.jpg',
'{"nucleos": 6, "hilos": 12, "frecuencia_base": "2.5 GHz", "frecuencia_turbo": "4.4 GHz", "cache": "18MB", "socket": "LGA1700", "tdp": "65W"}', FALSE),

('Intel Core i7-12700K', 'Procesador de 12ª generación con 12 núcleos para gaming y multitarea', 379.99, 15, 1, 'Intel', 'i7-12700K', 'i7-12700k.jpg',
'{"nucleos": 12, "hilos": 20, "frecuencia_base": "3.6 GHz", "frecuencia_turbo": "5.0 GHz", "cache": "25MB", "socket": "LGA1700", "tdp": "125W"}', TRUE);

-- ========================================
-- TARJETAS GRÁFICAS
-- ========================================
INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, marca, modelo, imagen, especificaciones, destacado) VALUES
('AMD Radeon RX 6600', 'Tarjeta gráfica ideal para gaming en 1080p con excelente eficiencia', 249.99, 20, 2, 'AMD', 'RX 6600', 'rx6600.jpg',
'{"memoria": "8GB GDDR6", "frecuencia": "2491 MHz", "conectores": "1x HDMI 2.1, 3x DisplayPort 1.4a", "consumo": "132W", "longitud": "240mm"}', FALSE),

('AMD Radeon RX 6700 XT', 'Tarjeta gráfica de alta gama para gaming en 1440p', 449.99, 14, 2, 'AMD', 'RX 6700 XT', 'rx6700xt.jpg',
'{"memoria": "12GB GDDR6", "frecuencia": "2581 MHz", "conectores": "1x HDMI 2.1, 3x DisplayPort 1.4a", "consumo": "230W", "longitud": "267mm"}', TRUE),

('NVIDIA RTX 3060', 'Tarjeta gráfica versátil para gaming y creación de contenido', 329.99, 25, 2, 'NVIDIA', 'RTX 3060', 'rtx3060.jpg',
'{"memoria": "12GB GDDR6", "frecuencia": "1777 MHz", "conectores": "1x HDMI 2.1, 3x DisplayPort 1.4a", "consumo": "170W", "longitud": "242mm"}', TRUE),

('NVIDIA RTX 4070', 'Tarjeta gráfica de última generación para gaming en alta resolución', 599.99, 16, 2, 'NVIDIA', 'RTX 4070', 'rtx4070.jpg',
'{"memoria": "12GB GDDR6X", "frecuencia": "2475 MHz", "conectores": "1x HDMI 2.1, 3x DisplayPort 1.4a", "consumo": "200W", "longitud": "280mm"}', TRUE),

('NVIDIA RTX 4090', 'La tarjeta gráfica más potente para gaming y creación profesional en 4K y 8K', 1899.99, 5, 2, 'NVIDIA', 'RTX 4090', 'rtx4090.jpg',
'{"memoria": "24GB GDDR6X", "frecuencia": "2520 MHz", "conectores": "1x HDMI 2.1, 3x DisplayPort 1.4a", "consumo": "450W", "longitud": "336mm"}', TRUE);

-- ========================================
-- MEMORIAS RAM
-- ========================================
INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, marca, modelo, imagen, especificaciones, destacado) VALUES
('Corsair Vengeance LPX 16GB', 'Memoria DDR4 de perfil bajo ideal para cualquier build', 54.99, 45, 3, 'Corsair', 'Vengeance LPX', 'vengeance-lpx16.jpg',
'{"capacidad": "16GB (2x8GB)", "tipo": "DDR4", "frecuencia": "3200 MHz", "latencia": "CL16", "rgb": "No", "perfil": "XMP"}', FALSE),

('G.Skill Ripjaws V 32GB', 'Kit de memoria DDR4 de alto rendimiento para gaming y multitarea', 109.99, 28, 3, 'G.Skill', 'Ripjaws V', 'ripjaws-v32.jpg',
'{"capacidad": "32GB (2x16GB)", "tipo": "DDR4", "frecuencia": "3600 MHz", "latencia": "CL16", "rgb": "No", "perfil": "XMP"}', TRUE);

-- ========================================
-- PLACAS BASE
-- ========================================
INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, marca, modelo, imagen, especificaciones, destacado) VALUES
('MSI MAG B660M', 'Placa base Micro-ATX para Intel 12ª y 13ª generación', 149.99, 22, 4, 'MSI', 'MAG B660M', 'mag-b660m.jpg',
'{"socket": "LGA1700", "chipset": "B660", "formato": "Micro-ATX", "ram_max": "128GB DDR4", "slots_m2": "2", "wifi": "No", "ethernet": "2.5Gb"}', FALSE),

('Gigabyte Z690 AORUS', 'Placa base premium para overclocking con Intel 12ª y 13ª Gen', 329.99, 10, 4, 'Gigabyte', 'Z690 AORUS', 'z690-aorus.jpg',
'{"socket": "LGA1700", "chipset": "Z690", "formato": "ATX", "ram_max": "128GB DDR5", "slots_m2": "5", "wifi": "WiFi 6E", "ethernet": "2.5Gb"}', TRUE),

('ASRock A520M', 'Placa base económica para procesadores AMD Ryzen', 79.99, 35, 4, 'ASRock', 'A520M', 'a520m.jpg',
'{"socket": "AM4", "chipset": "A520", "formato": "Micro-ATX", "ram_max": "64GB DDR4", "slots_m2": "1", "wifi": "No", "ethernet": "1Gb"}', FALSE),

('ASUS ROG Strix B550-F', 'Placa base gaming premium para AMD Ryzen con diseño ROG', 179.99, 16, 4, 'ASUS', 'ROG Strix B550-F', 'rog-b550f.jpg',
'{"socket": "AM4", "chipset": "B550", "formato": "ATX", "ram_max": "128GB DDR4", "slots_m2": "2", "wifi": "No", "ethernet": "2.5Gb"}', TRUE);

-- ========================================
-- DISCOS DUROS Y SSD
-- ========================================
INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, marca, modelo, imagen, especificaciones, destacado) VALUES
('Samsung 870 EVO 500GB', 'SSD SATA de alto rendimiento para mejora de sistemas', 59.99, 40, 5, 'Samsung', '870 EVO', '870evo-500gb.jpg',
'{"capacidad": "500GB", "tipo": "SSD SATA", "interfaz": "SATA 6Gb/s", "lectura": "560 MB/s", "escritura": "530 MB/s", "formato": "2.5 pulgadas"}', FALSE),

('Crucial MX500 1TB', 'SSD SATA fiable para almacenamiento rápido y eficiente', 79.99, 35, 5, 'Crucial', 'MX500', 'mx500-1tb.jpg',
'{"capacidad": "1TB", "tipo": "SSD SATA", "interfaz": "SATA 6Gb/s", "lectura": "560 MB/s", "escritura": "510 MB/s", "formato": "2.5 pulgadas"}', TRUE),

('WD Blue SN550 1TB', 'SSD NVMe económico con excelente relación calidad-precio', 69.99, 32, 5, 'Western Digital', 'SN550', 'sn550-1tb.jpg',
'{"capacidad": "1TB", "tipo": "SSD NVMe", "interfaz": "PCIe 3.0 x4", "lectura": "2400 MB/s", "escritura": "1950 MB/s", "formato": "M.2 2280"}', TRUE),

('Seagate Barracuda 2TB', 'Disco duro de alta capacidad para almacenamiento masivo', 54.99, 50, 5, 'Seagate', 'Barracuda', 'barracuda-2tb.jpg',
'{"capacidad": "2TB", "tipo": "HDD", "rpm": "7200", "cache": "256MB", "interfaz": "SATA 6Gb/s", "formato": "3.5 pulgadas"}', FALSE),

('Samsung 990 PRO 2TB', 'SSD NVMe Gen 4 de máximo rendimiento para profesionales', 189.99, 18, 5, 'Samsung', '990 PRO', '990pro-2tb.jpg',
'{"capacidad": "2TB", "tipo": "SSD NVMe", "interfaz": "PCIe 4.0 x4", "lectura": "7450 MB/s", "escritura": "6900 MB/s", "formato": "M.2 2280"}', TRUE);

-- ========================================
-- FUENTES DE ALIMENTACIÓN
-- ========================================
INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, marca, modelo, imagen, especificaciones, destacado) VALUES
('Seasonic Focus GX-650', 'Fuente modular 80+ Gold de alta eficiencia y bajo ruido', 99.99, 28, 6, 'Seasonic', 'Focus GX-650', 'focus-gx650.jpg',
'{"potencia": "650W", "certificacion": "80+ Gold", "modular": "Totalmente modular", "ventilador": "120mm", "garantia": "10 años", "protecciones": "OVP/UVP/OPP/OTP/SCP"}', TRUE),

('Corsair RM750x', 'PSU modular premium con excelente calidad de componentes', 119.99, 24, 6, 'Corsair', 'RM750x', 'rm750x.jpg',
'{"potencia": "750W", "certificacion": "80+ Gold", "modular": "Totalmente modular", "ventilador": "135mm", "garantia": "10 años", "protecciones": "OVP/UVP/OCP/OTP/SCP"}', TRUE),

('EVGA 850 GQ', 'Fuente semi-modular 80+ Gold con gran potencia', 109.99, 20, 6, 'EVGA', '850 GQ', 'evga850gq.jpg',
'{"potencia": "850W", "certificacion": "80+ Gold", "modular": "Semi-modular", "ventilador": "135mm", "garantia": "5 años", "protecciones": "OVP/UVP/OPP/SCP"}', FALSE),

('be quiet! Pure Power 11', 'PSU silenciosa con certificación 80+ Gold', 89.99, 26, 6, 'be quiet!', 'Pure Power 11', 'purepower11.jpg',
'{"potencia": "600W", "certificacion": "80+ Gold", "modular": "No modular", "ventilador": "120mm", "garantia": "5 años", "protecciones": "OVP/UVP/OCP/OTP/SCP"}', FALSE);
