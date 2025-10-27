<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tercero</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <div class="card form-card">
        <h2>Editando a: <?php echo htmlspecialchars($tercero->nombres); ?></h2>
        
        <form action="index.php" method="POST">
            
            <input type="hidden" name="accion" value="actualizar">
            <input type="hidden" name="id" value="<?php echo $tercero->id; ?>">
            
            <select name="tipoDocumento" required>
                <option value="">Tipo de Documento</option>
                <option value="Cedula de Ciudadania" <?php if($tercero->tipoDocumento == 'Cedula de Ciudadania') echo 'selected'; ?>>Cédula de Ciudadanía</option>
                <option value="Cedula de Extranjeria" <?php if($tercero->tipoDocumento == 'Cedula de Extranjeria') echo 'selected'; ?>>Cédula de Extranjería</option>
                <option value="Tarjeta de Identidad" <?php if($tercero->tipoDocumento == 'Tarjeta de Identidad') echo 'selected'; ?>>Tarjeta de Identidad</option>
                <option value="Pasaporte" <?php if($tercero->tipoDocumento == 'Pasaporte') echo 'selected'; ?>>Pasaporte</option>
            </select>

            <input type="text" name="numeroDocumento" placeholder="Número de Documento" required value="<?php echo htmlspecialchars($tercero->numeroDocumento); ?>">
            <input type="text" name="nombres" placeholder="Nombres" required value="<?php echo htmlspecialchars($tercero->nombres); ?>">
            <input type="text" name="apellidos" placeholder="Apellidos" required value="<?php echo htmlspecialchars($tercero->apellidos); ?>">
            <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($tercero->email); ?>">
            <input type="text" name="telefono" placeholder="Teléfono" value="<?php echo htmlspecialchars($tercero->telefono); ?>">
            <input type="text" name="direccion" placeholder="Dirección" value="<?php echo htmlspecialchars($tercero->direccion); ?>">
            <input type="date" name="fechaNacimiento" value="<?php echo htmlspecialchars($tercero->fechaNacimiento ?? ''); ?>"> 
            
            <select name="estado" required>
                <option value="Activo" <?php if ($tercero->estado == 'Activo') echo 'selected'; ?>>Activo</option>
                <option value="Inactivo" <?php if ($tercero->estado == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
            </select>
            
            <button type="submit">Actualizar Cambios</button>
            
            <a href="index.php" class="btn-cancel" style="text-decoration:none; text-align:center; background-color:#7f8c8d; color:white; padding:12px; border-radius:6px;">Cancelar</a>
        </form>
    </div>
</div>

</body>
</html>