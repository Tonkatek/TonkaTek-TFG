<?php
/**
 * Script de verificación y generación de hash para admin123
 * Ejecutar en el contenedor web: docker exec tonkatek_web php /var/www/html/verificar_hash.php
 */

echo "===========================================\n";
echo "VERIFICACIÓN DE HASH PARA 'admin123'\n";
echo "===========================================\n\n";

$password_correcta = 'admin123';

// Hash actual en la base de datos
$hash_actual = '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy';

echo "1. VERIFICANDO HASH ACTUAL:\n";
echo "   Hash en BD: " . substr($hash_actual, 0, 40) . "...\n";
echo "   Contraseña: admin123\n";
echo "   Resultado: ";
if (password_verify($password_correcta, $hash_actual)) {
    echo "✅ CORRECTO - El hash verifica\n\n";
} else {
    echo "❌ INCORRECTO - El hash NO verifica\n\n";
}

echo "2. GENERANDO NUEVO HASH:\n";
$nuevo_hash = password_hash($password_correcta, PASSWORD_DEFAULT);
echo "   Nuevo hash generado:\n";
echo "   $nuevo_hash\n\n";

echo "3. VERIFICANDO NUEVO HASH:\n";
echo "   Resultado: ";
if (password_verify($password_correcta, $nuevo_hash)) {
    echo "✅ CORRECTO - El nuevo hash verifica\n\n";
} else {
    echo "❌ ERROR - El nuevo hash NO verifica\n\n";
}

echo "4. VERIFICANDO EN BASE DE DATOS:\n";
try {
    require_once __DIR__ . '/config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "   ✅ Conexión a BD exitosa\n";
        
        // Obtener usuario admin
        $query = "SELECT email, password FROM usuarios WHERE email = 'admin@tonkatek.com'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "   ✅ Usuario encontrado: " . $user['email'] . "\n";
            echo "   Hash en BD: " . substr($user['password'], 0, 40) . "...\n";
            echo "   Verificando con 'admin123': ";
            
            if (password_verify($password_correcta, $user['password'])) {
                echo "✅ FUNCIONA\n\n";
            } else {
                echo "❌ NO FUNCIONA\n";
                echo "\n   🔧 SOLUCIÓN: Ejecuta este SQL en phpMyAdmin:\n";
                echo "   UPDATE usuarios SET password = '$nuevo_hash' WHERE email = 'admin@tonkatek.com';\n\n";
            }
        } else {
            echo "   ❌ Usuario NO encontrado\n\n";
        }
    } else {
        echo "   ❌ Error de conexión a BD\n\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
}

echo "===========================================\n";
echo "FIN DE LA VERIFICACIÓN\n";
echo "===========================================\n";
?>
