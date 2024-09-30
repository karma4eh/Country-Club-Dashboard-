 console.log ('conectado perfectamente');
 function verDetallesSocio(socioId) {
        // Mostrar la modal
        document.getElementById('modal').classList.remove('hidden');

        // Hacer una solicitud AJAX para obtener los datos del socio
        fetch(`../backend/ver_socio.php?id=${socioId}`)
            .then(response => response.json())
            .then(data => {
                // Insertar los datos en la modal
                document.getElementById('modal-content').innerHTML = `
                    <p><strong>Nombre Completo:</strong> ${data.nombre} ${data.apellido}</p>
                    <p><strong>Cédula:</strong> V-${data.cedula}</p>
                    <p><strong>Acción:</strong> ${data.accion}</p>
                    <p><strong>Estado:</strong> ${data.estado == 'activo' ? 'Activo' : 'Inactivo'}</p>
                    <p><strong>Vencimiento:</strong> ${data.vencimiento}</p>
                `;
            })
            .catch(error => console.error('Error al cargar los datos del socio:', error));
    }

    function cerrarModal() {
        document.getElementById('modal').classList.add('hidden');
    }
