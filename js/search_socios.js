document.addEventListener("DOMContentLoaded", function() {
    const searchButton = document.querySelector('#searchButton'); 
    const searchInput = document.querySelector('#search');
    const tableBody = document.querySelector('tbody'); 
    const loadingRow = document.querySelector('#loading-row'); // Elemento de carga

    searchButton.addEventListener('click', function() {
        const query = searchInput.value;

        // Muestra el mensaje de carga
        loadingRow.style.display = ''; // Muestra el mensaje de carga
        tableBody.innerHTML = ''; // Limpia la tabla para mostrar los resultados más tarde

        // Realiza la solicitud AJAX
        fetch(`../backend/search_socios.php?query=${encodeURIComponent(query)}`)
            .then(response => response.text())
            .then(data => {
                // Oculta el mensaje de carga
                loadingRow.style.display = 'none'; // Oculta el mensaje de carga
                tableBody.innerHTML = data; // Muestra los resultados
            })
            .catch(error => {
                console.error('Error:', error);
                loadingRow.style.display = 'none'; // Oculta el mensaje de carga en caso de error
                tableBody.innerHTML = "<tr><td colspan='5' class='text-center py-4'>Error al cargar datos.</td></tr>";
            });
    });
});

function verSocio(id) {
    console.log(`Ver detalles del socio con ID: ${id}`);
}
