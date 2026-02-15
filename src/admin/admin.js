// Seleccionamos todos los botones de eliminar
document.querySelectorAll('.btn-delete').forEach(boton => {
    boton.addEventListener('click', eliminarProducto);
});
document.querySelectorAll('.btn-edit').forEach(boton => {
    boton.addEventListener('click', editarProducto);
});



async function editarProducto($event) {
    const id = $event.currentTarget.value;
    sessionStorage.setItem("productoID", id);
    window.location.href = "/admin/editar/"+id;
}


async function eliminarProducto($event) {
    // Usamos currentTarget para asegurarnos de capturar el botón 
    // incluso si haces clic en un icono dentro del botón
    const value = $event.currentTarget.value;
    
    if (!confirm('¿Estás seguro de que deseas eliminar el ID ' + value + '?')) {
        return; // Cancelar si el usuario no confirma
    }

    try {
        const response = await fetch('/admin/eliminar/' + value, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });

        if (response.ok) {
            // Redirigir o recargar
            window.location.reload(); 
        } else {
            alert('Error al eliminar');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}