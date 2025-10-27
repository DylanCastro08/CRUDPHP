<?php
include_once("Controladores/ControladorUsuario.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Profesional con PHP (Switch)</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <h1>Gestión de Terceros (Controlador Único)</h1>

    <div class="card form-card">
        <h2>Registrar Nuevo Tercero</h2>
        <form action="index.php" method="POST">
            
            <input type="hidden" name="accion" value="crear">

            <select name="tipoDocumento" required>
                <option value="" disabled selected>Tipo de Documento</option>
                <option value="Cedula de Ciudadania">Cédula de Ciudadanía</option>
                <option value="Cedula de Extranjeria">Cédula de Extranjería</option>
                <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                <option value="Pasaporte">Pasaporte</option>
            </select>

            <input type="text" name="numeroDocumento" placeholder="Número de Documento" required>
            <input type="text" name="nombres" placeholder="Nombres" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="telefono" placeholder="Teléfono">
            <input type="text" name="direccion" placeholder="Dirección">
            <input type="date" name="fechaNacimiento"> 
            
            <select name="estado" required>
                <option value="Activo" selected>Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>

            <button type="submit">Guardar Tercero</button>
            <button type="reset" class="btn-cancel">Limpiar Campos</button>
            
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
            <tbody>
                <?php foreach ($terceros as $tercero) { ?>
                <tr>
                    <td><?php echo $tercero->id; ?></td>
                    <td><?php echo htmlspecialchars($tercero->tipoDocumento); ?>: <?php echo htmlspecialchars($tercero->numeroDocumento); ?></td>
                    <td><?php echo htmlspecialchars($tercero->nombres); ?></td>
                    <td><?php echo htmlspecialchars($tercero->apellidos); ?></td>
                    <td><?php echo htmlspecialchars($tercero->email); ?></td>
                    <td><?php echo htmlspecialchars($tercero->telefono); ?></td>
                    <td><?php echo htmlspecialchars($tercero->estado); ?></td>
                    <td class="actions">
                        <a href="index.php?accion=editar&id=<?php echo $tercero->id; ?>" class="btn-edit">Editar</a>
                        
                        <a href="index.php?accion=eliminar&id=<?php echo $tercero->id; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('¿Estás seguro de que deseas eliminar este tercero?');">Eliminar</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>