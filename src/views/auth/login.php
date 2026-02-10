<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
</style>

<div class="flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="/" class="tonka-logo text-5xl text-white" style="-webkit-text-fill-color: white;">TONKATEK</a>
            <p class="text-white mt-2 text-lg"><?php echo SITE_SLOGAN; ?></p>
        </div>

        <?php displayAlert(); ?>

        <div class="card bg-white shadow-2xl">
            <div class="card-body">
                <div role="tablist" class="tabs tabs-boxed mb-6">
                    <a role="tab" class="tab tab-active" onclick="showLogin()">Iniciar Sesión</a>
                    <a role="tab" class="tab" onclick="showRegister()">Registrarse</a>
                </div>

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="/auth/login" class="space-y-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Email</span></label>
                        <input type="email" name="email" placeholder="tu@email.com" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Contraseña</span></label>
                        <input type="password" name="password" placeholder="••••••••" class="input input-bordered" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Iniciar Sesión</button>
                </form>

                <!-- Register Form -->
                <form id="registerForm" method="POST" action="/auth/register" class="space-y-4 hidden">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Nombre completo</span></label>
                        <input type="text" name="nombre" placeholder="Juan Pérez" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Email</span></label>
                        <input type="email" name="email_reg" placeholder="tu@email.com" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Contraseña</span></label>
                        <input type="password" name="password_reg" placeholder="••••••••" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Confirmar Contraseña</span></label>
                        <input type="password" name="password_confirm" placeholder="••••••••" class="input input-bordered" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Registrarse</button>
                </form>

                <div class="divider">O</div>
                <a href="/" class="btn btn-outline w-full">Volver al inicio</a>
            </div>
        </div>

        <div class="text-center mt-4 text-white text-sm">
            <p>Demo: <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="a1c0c5ccc8cfe1d5cecfcac0d5c4ca8fc2cecc">[email&#160;protected]</a> / admin123</p>
        </div>
    </div>
</div>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
    function showLogin() {
        document.getElementById('loginForm').classList.remove('hidden');
        document.getElementById('registerForm').classList.add('hidden');
        document.querySelectorAll('.tab')[0].classList.add('tab-active');
        document.querySelectorAll('.tab')[1].classList.remove('tab-active');
    }
    function showRegister() {
        document.getElementById('loginForm').classList.add('hidden');
        docume