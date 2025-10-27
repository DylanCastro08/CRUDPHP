
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="assets/style.css">
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/app.js"></script>

</body>
</html>