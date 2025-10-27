
const API_URL = 'Controladores/ControladorUsuario.php';

function showAlert(message) {
    alert(message);
}

function cargarTerceros() {
    $.ajax({
        url: API_URL,
        type: 'GET',
        data: { accion: 'leer' },
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                let html = '';
                response.data.forEach(function(tercero) {
                    html += `
                        <tr>
                            <td>${tercero.id}</td>
                            <td>${tercero.tipoDocumento}: ${tercero.numeroDocumento}</td>
                            <td>${tercero.nombres}</td>
                            <td>${tercero.apellidos}</td>
                            <td>${tercero.email}</td>
                            <td>${tercero.telefono}</td>
                            <td>${tercero.estado}</td>
                            <td class="actions">
                                <button class="btn-edit" data-id="${tercero.id}">Editar</button>
                                <button class="btn-delete" data-id="${tercero.id}">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                $('#tabla-body').html(html);
            }
        }
    });
}

function resetFormulario() {
    $('#formulario-tercero')[0].reset();
    $('#form-titulo').text('Registrar Nuevo Tercero');
    $('#form-accion').val('crear');
    $('#form-id').val('0');
}

$(document).ready(function() {

    cargarTerceros();

    $('#formulario-tercero').submit(function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: API_URL,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success_create') {
                    showAlert('¡Guardado! El tercero ha sido registrado.');
                } else if (response.status === 'success_update') {
                    showAlert('¡Actualizado! El tercero ha sido actualizado.');
                } else if (response.status === 'error_duplicado') {
                    showAlert('Error: El email o documento ya existe.');
                } else {
                    showAlert('Error: Ocurrió un problema.');
                }
                
                resetFormulario();
                cargarTerceros();
            }
        });
    });

    $(document).on('click', '.btn-edit', function() {
        let id = $(this).data('id');

        $.ajax({
            url: API_URL,
            type: 'GET',
            data: { accion: 'leer_uno', id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let t = response.data;
                    $('#tipoDocumento').val(t.tipoDocumento);
                    $('#numeroDocumento').val(t.numeroDocumento);
                    $('#nombres').val(t.nombres);
                    $('#apellidos').val(t.apellidos);
                    $('#email').val(t.email);
                    $('#telefono').val(t.telefono);
                    $('#direccion').val(t.direccion);
                    $('#fechaNacimiento').val(t.fechaNacimiento);
                    $('#estado').val(t.estado);
                    
                    $('#form-titulo').text('Editando a: ' + t.nombres);
                    $('#form-accion').val('actualizar');
                    $('#form-id').val(t.id);
                    
                    window.scrollTo(0, 0);
                }
            }
        });
    });


    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');

        if (confirm('¿Estás seguro? ¡No podrás revertir esta acción!')) {
            $.ajax({
                url: API_URL,
                type: 'POST',
                data: { accion: 'eliminar', id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success_delete') {
                        showAlert('¡Eliminado! El tercero fue eliminado.');
                        cargarTerceros();
                    }
                }
            });
        }
    });

    $('#btn-cancelar').click(function() {
        resetFormulario();
    });

});