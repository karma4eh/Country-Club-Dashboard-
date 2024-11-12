function verDetallesSocio(socioId) {
    document.getElementById('modal').classList.remove('hidden');

    fetch(`../backend/ver_socio.php?id=${socioId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('nombre').textContent = data.nombre;
            document.getElementById('apellido').textContent = data.apellido;
            document.getElementById('direccion').textContent = data.direccion;
            document.getElementById('cedula').textContent = data.cedula;
            document.getElementById('numero').textContent = data.numero;
            document.getElementById('correo').textContent = data.correo;
            document.getElementById('accion').textContent = data.accion;
            document.getElementById('estado').textContent = data.estado === 'activo' ? 'Activo' : 'Inactivo';
        })
        .catch(error => console.error('Error al cargar los datos del socio:', error));
}
