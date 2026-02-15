<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController extends Controller {
    
    public function showLogin() {
        // Si ya está logueado, redirigir al home
        if ($this->isLoggedIn()) {
            $this->redirect('/');
            return;
        }
        
        $data = [
            'error' => '',
            'success' => ''
        ];
        
        $this->view('auth/login', $data);
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }
        
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        
        $usuarioModel = new Usuario();
        $user_data = $usuarioModel->login($email, $password);
        
        if ($user_data) {
            $_SESSION['usuario_id'] = $user_data['id'];
            $_SESSION['nombre'] = $user_data['nombre'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['rol'] = $user_data['rol'];
            
            showAlert('¡Bienvenido ' . $user_data['nombre'] . '!', 'success');
            $this->redirect('/');
        } else {
            showAlert('Email o contraseña incorrectos', 'error');
            $this->redirect('/login');
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }
        
        $nombre = sanitizeInput($_POST['nombre']);
        $email = sanitizeInput($_POST['email_reg']);
        $password = $_POST['password_reg'];
        $password_confirm = $_POST['password_confirm'];
        
        if ($password !== $password_confirm) {
            showAlert('Las contraseñas no coinciden', 'error');
            $this->redirect('/login');
            return;
        }
        
        $usuarioModel = new Usuario();
        
        if ($usuarioModel->emailExiste($email)) {
            showAlert('Este email ya está registrado', 'error');
            $this->redirect('/login');
            return;
        }
        
        if ($usuarioModel->registrar($nombre, $email, $password)) {
            showAlert('Registro exitoso. Ya puedes iniciar sesión', 'success');
            $this->redirect('/login');
        } else {
            showAlert('Error al registrar el usuario', 'error');
            $this->redirect('/login');
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
?>
