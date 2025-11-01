// Variable global para la URL de la API
const API_URL = 'Controladores/ControladorUsuario.php';

// Función de alerta nativa
function showAlert(message) {
    alert(message);
}

// Función para obtener los datos del formulario
function getFormData($form) {
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

// ----------------------------------------------------------------------
// FUNCIÓN PARA REALIZAR PETICIONES CON FETCH (Método Genérico)
// ----------------------------------------------------------------------
async function apiFetch(action, method = 'GET', data = null) {
    let url = API_URL + `?accion=${action}`;
    let config = { method: method };

    // Si el método es POST (crear, actualizar, eliminar), ajustamos el cuerpo
    if (method === 'POST' && data) {
        // Convertimos el objeto de datos a un formato compatible con POST
        const formData = new URLSearchParams();
        for (const key in data) {
            formData.append(key, data[key]);
        }
        config.body = formData;
    } 
    // Si es GET, los datos van en la URL (ya cubierto por el parámetro 'action')

    try {
        const response = await fetch(url, config);
        
        // Manejamos errores de red o HTTP (ej: 404, 500)
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const json = await response.json();
        return json;

    } catch (error) {
        console.error('Error en la petición:', error);
        return { status: 'error_network', message: error.message };
    }
}

// ----------------------------------------------------------------------
// FUNCIÓN PARA CARGAR LA TABLA (READ) - Usando FETCH
// ----------------------------------------------------------------------
async function cargarTerceros() {
    const response = await apiFetch('leer', 'GET');

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
    } else {
        $('#tabla-body').html('<tr><td colspan="8">Error al cargar datos.</td></tr>');
    }
}

// Función para limpiar el formulario
function resetFormulario() {
    $('#formulario-tercero')[0].reset();
    $('#form-titulo').text('Registrar Nuevo Tercero');
    $('#form-accion').val('crear');
    $('#form-id').val('0');
}

// --- Lógica Principal ---
$(document).ready(function() {

    // Cargar los terceros al iniciar la página
    cargarTerceros();

    // Evento para CREAR o ACTUALIZAR
    $('#formulario-tercero').submit(async function(e) {
        e.preventDefault();
        
        // Obtener los datos del formulario (usando la función de jQuery + helper)
        let formData = getFormData($(this));
        let action = formData.accion; // 'crear' o 'actualizar'
        
        // Llamada FETCH al API
        const response = await apiFetch(action, 'POST', formData);

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
    });

    // Evento para EDITAR (traer datos al formulario)
    $(document).on('click', '.btn-edit', async function() {
        let id = $(this).data('id');

        // Llamada FETCH al API
        const response = await apiFetch('leer_uno', 'GET', null, { id: id });

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
            
            // Sin animación de scroll
            window.scrollTo(0, 0);
        }
    });

    // Evento para ELIMINAR
    $(document).on('click', '.btn-delete', async function() {
        let id = $(this).data('id');

        if (confirm('¿Estás seguro? ¡No podrás revertir esta acción!')) {
            const response = await apiFetch('eliminar', 'POST', { accion: 'eliminar', id: id });

            if (response.status === 'success_delete') {
                showAlert('¡Eliminado! El tercero fue eliminado.');
                cargarTerceros();
            } else {
                showAlert('Error al eliminar.');
            }
        }
    });

    // Evento para CANCELAR
    $('#btn-cancelar').click(function() {
        resetFormulario();
    });

});