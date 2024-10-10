
function validateSearch() {
    var searchTerm = document.getElementById('search_term').value.trim();
    
    // Si el campo está vacío, mostrar la alerta y evitar el envío del formulario
    if (searchTerm === '') {
        
        var alertDiv = document.createElement('div');
        alertDiv.className = "bg-red-500 text-white p-4 rounded-lg mt-4 shadow-lg max-w-md mx-auto";
        alertDiv.innerHTML = '<p class="text-center">No has ingresado ningún valor.</p>';

     
        var formContainer = document.querySelector('form');
        formContainer.parentNode.insertBefore(alertDiv, formContainer.nextSibling);

        
        setTimeout(function() {
            alertDiv.remove();
        }, 3000);

        return false; // 
    }
    return true; // 
}

