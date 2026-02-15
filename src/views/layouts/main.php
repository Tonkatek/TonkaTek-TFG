<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --tonka-primary: #FF6B35;
            --tonka-secondary: #004E89;
            --tonka-accent: #1A1A2E;
        }
        body { font-family: 'Exo 2', sans-serif; }
        .tonka-logo {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            background: linear-gradient(135deg, var(--tonka-primary), var(--tonka-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .price-tag { font-family: 'Orbitron', sans-serif; color: var(--tonka-primary); font-weight: 700; }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
    </style>
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
<?php echo $content; ?>
<?php if(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin') !== false): ?>
<script src="/assets/js/admin.js"></script>
<?php endif; ?>
<script>
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) themeToggle.checked = newTheme === 'light';
}
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        themeToggle.checked = currentTheme === 'light';
    }
});
</script>
</body>
</html>
