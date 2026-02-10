<?php
require_once '../config/config.php';
require_once '../includes/classes/Usuario.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['login'])) {
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        
        $usuario = new Usuario();
        $user_data = $usuario->login($email, $password);
        
        if($user_data) {
            $_SESSION['usuario_id'] = $user_data['id'];
            $_SESSION['nombre'] = $user_data['nombre'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['rol'] = $user_data['rol'];
            
            showAlert('¡Bienvenido ' . $user_data['nombre'] . '!', 'success');
            redirect('../index.php');
        } else {
            $error = 'Email o contraseña incorrectos';
        }
    }
    
    if(isset($_POST['register'])) {
        $nombre = sanitizeInput($_POST['nombre']);
        $email = sanitizeInput($_POST['email_reg']);
        $password = $_POST['password_reg'];
        $password_confirm = $_POST['password_confirm'];
        
        if($password !== $password_confirm) {
            $error = 'Las contraseñas no coinciden';
        } else {
            $usuario = new Usuario();
            
            if($usuario->emailExiste($email)) {
                $error = 'Este email ya está registrado';
            } else {
                if($usuario->registrar($nombre, $email, $password)) {
                    $success = 'Registro exitoso. Ya puedes iniciar sesión';
                } else {
                    $error = 'Error al registrar el usuario';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Exo 2', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .tonka-logo {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
        }
        .auth-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="../index.php" class="tonka-logo text-5xl text-white">TONKATEK</a>
            <p class="text-white mt-2 text-lg"><?php echo SITE_SLOGAN; ?></p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-error shadow-lg mb-4">
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success shadow-lg mb-4">
                <span><?php echo $success; ?></span>
            </div>
        <?php endif; ?>

        <div class="card auth-card shadow-2xl">
            <div class="card-body">
                <div role="tablist" class="tabs tabs-boxed mb-6">
                    <a role="tab" class="tab tab-active" onclick="showLogin()">Iniciar Sesión</a>
                    <a role="tab" class="tab" onclick="showRegister()">Registrarse</a>
                </div>

                <!-- Login Form -->
                <form id="loginForm" method="POST" class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Email</span>
                        </label>
                        <input type="email" name="email" placeholder="tu@email.com" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Contraseña</span>
                        </label>
                        <input type="password" name="password" placeholder="••••••••" class="input input-bordered" required />
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-full">Iniciar Sesión</button>
                </form>

                <!-- Register Form -->
                <form id="registerForm" method="POST" class="space-y-4 hidden">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nombre completo</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Juan Pérez" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Email</span>
                        </label>
                        <input type="email" name="email_reg" placeholder="tu@email.com" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Contraseña</span>
                        </label>
                        <input type="password" name="password_reg" placeholder="••••••••" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Confirmar Contraseña</span>
                        </label>
                        <input type="password" name="password_confirm" placeholder="••••••••" class="input input-bordered" required />
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-full">Registrarse</button>
                </form>

                <div class="divider">O</div>
                <a href="/" class="btn btn-outline w-full">Volver al inicio</a>
            </div>
        </div>

        <div class="text-center mt-4 text-white text-sm">
            <p>Demo: admin@tonkatek.com / admin123</p>
        </div>
    </div>

    <script>
        function showLogin() {
            document.getElementById('loginForm').classList.remove('hidden');
            document.getElementById('registerForm').classList.add('hidden');
            document.querySelectorAll('.tab')[0].classList.add('tab-active');
            document.querySelectorAll('.tab')[1].classList.remove('tab-active');
        }

        function showRegister() {
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.remove('hidden');
            document.querySelectorAll('.tab')[0].classList.remove('tab-active');
            document.querySelectorAll('.tab')[1].classList.add('tab-active');
        }
    </script>
</body>
</html>
