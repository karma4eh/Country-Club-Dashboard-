function verDetallesSocio(socioId) {
    // Mostrar la modal
    document.getElementById('modal').classList.remove('hidden');

    // Hacer una solicitud AJAX para obtener los datos del socio
    fetch(`../backend/ver_socio.php?id=${socioId}`)
        .then(response => response.json())
        .then(data => {
            // Insertar los datos en la modal
            document.getElementById('modal-content').innerHTML = `
                <h2 class="text-2xl font-bold mb-4 text-white text-center text-yellow-500">Socio ${data.id}</h2>
                <p><strong>Nombre:</strong> ${data.nombre} ${data.apellido}</p>
                <p><strong>Dirección:</strong> ${data.direccion}</p>
                <p><strong>Cédula:</strong> V-${data.cedula}</p>
                <p><strong>Numero:</strong> ${data.numero}</p>
                <p><strong>Correo:</strong> ${data.correo}</p>
                <p><strong>Acción:</strong> ${data.accion}</p>
                <p><strong>Estado:</strong> ${data.estado == 'activo' ? 'Activo' : 'Inactivo'}</p>
                
            `;
        })
        .catch(error => console.error('Error al cargar los datos del socio:', error));
}