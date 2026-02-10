<?php
define('SITE_NAME', 'TonkaTek');
define('SITE_SLOGAN', 'Tus tonki precios de confianza');
define('BASE_URL', 'http://localhost:8080');
define('ITEMS_PER_PAGE', 12);

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir helper de imágenes
require_once __DIR__ . '/image_helper.php';

function redirect($url) { header("Location: " . $url); exit(); }
function isLoggedIn() { return isset($_SESSION['usuario_id']); }
function isAdmin() { return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'; }
function formatPrice($price) { return number_format($price, 2, ',', '.') . ' €'; }
function sanitizeInput($data) { return htmlspecialchars(trim(stripslashes($data))); }
function showAlert($message, $type = 'info') { $_SESSION['alert'] = ['message' => $message, 'type' => $type]; }
function displayAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        $colors = ['success' => 'alert-success', 'error' => 'alert-error', 'warning' => 'alert-warning', 'info' => 'alert-info'];
        $class = $colors[$alert['type']] ?? 'alert-info';
        echo "<div class='alert {$class} shadow-lg mb-4'><span>{$alert['message']}</span></div>";
        unset($_SESSION['alert']);
    }
}
?>