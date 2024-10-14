
    document.addEventListener("DOMContentLoaded", function() {
        const searchButton = document.querySelector('#searchButton'); 
        const searchInput = document.querySelector('#search');
        const tableBody = document.querySelector('tbody'); 

        searchButton.addEventListener('click', function() {
            const query = searchInput.value;

            // Realiza la solicitud AJAX
            fetch(`../backend/search_socios.php?query=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(data => {
                    
                    tableBody.innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });

    function verSocio(id) {
        
        console.log(`Ver detalles del socio con ID: ${id}`);
    }

