#!/bin/bash
set -e

# Script de inicialización del contenedor
echo "TonkaTek: Iniciando contenedor web..."

# Esperar a que MySQL esté listo
echo "TonkaTek: Esperando a que MySQL esté disponible..."
sleep 30


echo "TonkaTek: Primera inicialización - actualizando contraseñas..."
    
    # Ejecutar script PHP para actualizar contraseñas
php << 'EOPHP'
<?php
try {
    require_once '/var/www/html/config/database.php';
    
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        // Generar hash correcto para admin123
        $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Actualizar admin
        $stmt = $conn->prepare("UPDATE usuarios SET password = :password WHERE email = 'admin@tonkatek.com'");
        $stmt->bindParam(':password', $password_hash);
        $stmt->execute();
        
        // Actualizar cliente
        $stmt = $conn->prepare("UPDATE usuarios SET password = :password WHERE email = 'cliente@demo.com'");
        $stmt->bindParam(':password', $password_hash);
        $stmt->execute();
        
        echo "TonkaTek: ✅ Contraseñas actualizadas correctamente\n";
    } else {
        echo "TonkaTek: ❌ Error de conexión a la base de datos\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "TonkaTek: ❌ Error: " . $e->getMessage() . "\n";
}
?>
EOPHP
    
    # Crear archivo de marca
echo "TonkaTek: Inicialización completada"

echo "TonkaTek: Las contraseñas ya están inicializadas"


# Iniciar Apache
echo "TonkaTek: Iniciando Apache..."
apache2-foreground
