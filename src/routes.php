<?php
require_once 'config/config.php';
require_once 'core/Router.php';

$router = new Router();

// Rutas públicas
$router->get('/', 'HomeController@index');
$router->get('/productos', 'ProductoController@index');
$router->get('/producto/{id}', 'ProductoController@show');

// Rutas de autenticación
$router->get('/login', 'AuthController@showLogin');
$router->post('/auth/login', 'AuthController@login');
$router->post('/auth/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Rutas de carrito
$router->get('/carrito', 'CarritoController@index');
$router->post('/api/carrito/agregar', 'CarritoController@agregar');
$router->post('/api/carrito/actualizar', 'CarritoController@actualizar');
$router->post('/api/carrito/eliminar', 'CarritoController@eliminar');
$router->post('/api/carrito/vaciar', 'CarritoController@vaciar');
$router->get('/api/carrito/obtener', 'CarritoController@obtener');
$router->post('/api/carrito/realizar-pedido', 'CarritoController@realizarPedido');

// Rutas de perfil (requieren autenticación)
$router->get('/perfil', 'PerfilController@index');
$router->get('/pedidos', 'PerfilController@pedidos');
$router->get('/pedido/{id}', 'PerfilController@verPedido');

// Rutas de admin (requieren autenticación y rol admin)
$router->get('/admin', 'AdminController@index');
$router->get('/admin/crear', 'AdminController@mostrarCrear');
$router->post('/admin/crear', 'AdminController@crear');
$router->get('/admin/editar/{id}', 'AdminController@mostrarEditar');
$router->post('/admin/editar/{id}', 'AdminController@editar');
$router->post('/admin/eliminar/{id}', 'AdminController@eliminar');

// Ruta de compatibilidad para /editar (redirige a /admin/editar)
$router->get('/editar', 'AdminController@redirigirEditar');


// Despachar la ruta
$router->dispatch();
?>
