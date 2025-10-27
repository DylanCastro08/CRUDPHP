
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<div class="container">
    <h1>Gestión de Terceros</h1>

    <div class="card form-card">
        <h2 id="form-titulo">Registrar Nuevo Tercero</h2>
        <form id="formulario-tercero" method="POST">
            
            <input type="hidden" name="accion" id="form-accion" value="crear">
            <input type="hidden" name="id" id="form-id" value="0">

            <select name="tipoDocumento" id="tipoDocumento" required>
                <option value="" disabled selected>Tipo de Documento</option>
                <option value="Cedula de Ciudadania">Cédula de Ciudadanía</option>
                <option value="Cedula de Extranjeria">Cédula de Extranjería</option>
                <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                <option value="Pasaporte">Pasaporte</option>
            </select>

            <input type="text" name="numeroDocumento" id="numeroDocumento" placeholder="Número de Documento" required>
            <input type="text" name="nombres" id="nombres" placeholder="Nombres" required>
            <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="text" name="telefono" id="telefono" placeholder="Teléfono">
            <input type="text" name="direccion" id="direccion" placeholder="Dirección">
            <input type="date" name="fechaNacimiento" id="fechaNacimiento"> 
            
            <select name="estado" id="estado" required>
                <option value="Activo" selected>Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>

            <button type="submit">Guardar</button>
            <button type="button" class="btn-cancel" id="btn-cancelar">Cancelar</button>
            
        </form> 
    </div>

    <div class="card table-card">
        <h2>Terceros Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Documento</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-body">
                <tr><td colspan="8">Cargando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
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
    });

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

        if (confirm('¿Estás seguro?')) {
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

</script>
</body>
</html>