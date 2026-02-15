// Panel de Administración - TonkaTek (VERSIÓN CORREGIDA CON DEBUG)
console.log('✅ Admin JS cargado correctamente - VERSIÓN DEBUG');

// Auto-cerrar alertas después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    if (alerts.length > 0) {
        console.log(`📢 ${alerts.length} alerta(s) encontrada(s)`);
        
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    }
});

// Validación simple del formulario antes de enviar
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[method="POST"]');
    
    console.log(`🔍 Encontrados ${forms.length} formularios POST`);
    
    forms.forEach((form, index) => {
        console.log(`📝 Formulario ${index + 1}:`, {
            action: form.action,
            method: form.method,
            hasOnSubmit: !!form.onsubmit
        });
        
        form.addEventListener('submit', function(e) {
            console.group(`🚀 Enviando formulario ${index + 1}`);
            console.log('Action:', form.action);
            console.log('Method:', form.method);
            
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            console.log(`Validando ${requiredFields.length} campos requeridos...`);
            
            requiredFields.forEach(field => {
                const fieldName = field.name || field.id || 'campo sin nombre';
                const fieldValue = field.value.trim();
                
                if (!fieldValue) {
                    isValid = false;
                    field.classList.add('input-error');
                    console.warn(`❌ Campo vacío: ${fieldName}`);
                } else {
                    field.classList.remove('input-error');
                    console.log(`✅ Campo válido: ${fieldName} = "${fieldValue}"`);
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                console.error('❌ Validación fallida - formulario no enviado');
                alert('Por favor, completa todos los campos obligatorios');
            } else {
                console.log('✅ Validación exitosa - formulario se enviará');
            }
            
            console.groupEnd();
        });
    });
    
    // Debug para botones de eliminar
    const deleteButtons = document.querySelectorAll('form[action*="/admin/eliminar/"] button[type="submit"]');
    console.log(`🗑️ Encontrados ${deleteButtons.length} botones de eliminar`);
    
    deleteButtons.forEach((button, index) => {
        const form = button.closest('form');
        console.log(`Botón eliminar ${index + 1}:`, {
            action: form.action,
            onsubmit: form.getAttribute('onsubmit')
        });
        
        button.addEventListener('click', function(e) {
            console.log(`🗑️ Click en botón eliminar - Action: ${form.action}`);
        });
    });
    
    // Debug para enlaces de editar
    const editLinks = document.querySelectorAll('a[href*="/admin/editar/"]');
    console.log(`✏️ Encontrados ${editLinks.length} enlaces de editar`);
    
    editLinks.forEach((link, index) => {
        console.log(`Enlace editar ${index + 1}: ${link.href}`);
        
        link.addEventListener('click', function(e) {
            console.log(`✏️ Click en enlace editar - Href: ${link.href}`);
        });
    });
});

// Log de información de sesión (si está disponible)
console.log('📊 Estado de la página:', {
    url: window.location.href,
    pathname: window.location.pathname,
    method: document.forms[0]?.method || 'N/A'
});

console.log('🎯 Sistema de alertas, validación y debugging activado');