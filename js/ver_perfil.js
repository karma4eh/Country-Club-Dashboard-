$(document).ready(function() {
    // Obtener el parámetro de cédula desde la URL
    const cedula = new URLSearchParams(window.location.search).get('cedula');

    // Cargar datos del socio
    $.getJSON(`../backend/get_socio.php?cedula=${cedula}`, function(data) {
        if (data.error) {
            alert(data.error);
            return;
        }

        // Mostrar el nombre del socio y la foto de perfil
        $('#socio-nombre').text(`${data.nombre} ${data.apellido}`);
        $('#socio-imagen').attr('src', data.imagen_perfil);

        // Llenar los datos del socio en el perfil
        $('#socio-datos').html(`
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">account_circle</span>
                <p><strong>Nombre:</strong> ${data.nombre} ${data.apellido}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">badge</span>
                <p><strong>Cédula:</strong> V-${data.cedula}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">mail</span>
                <p><strong>Correo:</strong> ${data.correo}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">phone</span>
                <p><strong>Teléfono:</strong> ${data.numero}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">check_circle</span>
                <p><strong>Acción:</strong> ${data.accion}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">info</span>
                <p><strong>Estado:</strong> ${data.estado}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">cake</span>
                <p><strong>Fecha de nacimiento:</strong> ${
                    data.fecha_nacimiento 
                    ? new Date(data.fecha_nacimiento).toLocaleDateString('es-ES') 
                    : 'No disponible'
                }</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">calendar_today</span>
                <p><strong>Socio Desde:</strong> ${
                    data.socio_desde 
                    ? new Date(data.socio_desde).toLocaleDateString('es-ES') 
                    : 'No disponible'
                }</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="material-icons text-yellow-400">home</span>
                <p><strong>Dirección:</strong> ${data.direccion}</p>
            </div>
        `);
        

        // Cargar las visitas del socio
      
    });

    // Abrir el modal para editar datos
    $('a[href="#"]').click(function(e) {
        e.preventDefault();
        $('#modal-editar').removeClass('hidden');

        // Llamamos a la función llenarModal para cargar los datos actuales del socio
        $.getJSON(`../backend/get_socio.php?cedula=${cedula}`, function(data) {
            llenarModal(data); // Asegúrate de que 'data' contiene los datos del socio
        });
    });

    
    // Cerrar el modal de editar perfil
    $('#cerrar-modal').click(function() {
        $('#modal-editar').addClass('hidden');
    });

   

    // Función para llenar los campos del modal con los datos actuales del socio
    function llenarModal(data) {
        $('#input-nombre').val(data.nombre);
        $('#input-apellido').val(data.apellido);
        $('#input-correo').val(data.correo);
        $('#input-numero').val(data.numero);
        $('#input-direccion').val(data.direccion);

        // Asignar el valor de 'estado' al select correspondiente
    }

    // Actualizar los datos del socio
    $('#form-editar').submit(function(e) {
        e.preventDefault();

        const formData = {
            nombre: $('#input-nombre').val(),
            apellido: $('#input-apellido').val(),
            correo: $('#input-correo').val(),
            numero: $('#input-numero').val(),
            direccion: $('#input-direccion').val(),
            cedula: cedula
        };

        $.ajax({
            url: '../backend/actualizar_socio.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Verificar si 'response' es una cadena JSON y convertirlo en objeto si es necesario
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);  // Convertir a objeto si es una cadena JSON
                    } catch (e) {
                        console.error('Error al parsear la respuesta JSON: ', e);
                        alert('Error al procesar la respuesta del servidor');
                        return;
                    }
                }
        
                // Verificar si la propiedad 'success' está presente y es true
                if (response.success) {
                    alert('Datos actualizados exitosamente');
                    $('#modal-editar').addClass('hidden');
                    location.reload(); // Recargar la página para mostrar los nuevos datos
                } else {
                    alert('Error al actualizar los datos: ' + (response.error || 'Desconocido'));
                }
            },
            error: function() {
                alert('Hubo un error al intentar conectar con el servidor.');
            }
        });
        
    });
    
});
