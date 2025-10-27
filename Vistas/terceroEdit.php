<?php
include_once("../Controladores/Conexion.php");
$conexion_bd = new Conexion();
$pdo = $conexion_bd->conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recibimos todos los datos
    $id = $_POST['id'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $numeroDocumento = $_POST['numeroDocumento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $estado = $_POST['estado'];


    $sql = "UPDATE terceros SET 
                tipoDocumento = ?, numeroDocumento = ?, nombres = ?, 
                apellidos = ?, email = ?, telefono = ?, 
                direccion = ?, fechaNacimiento = ?, estado = ? 
            WHERE 
                id = ?"; 
    
    try {
        $sentencia = $pdo->prepare($sql);
        $sentencia->execute([
            $tipoDocumento, $numeroDocumento, $nombres, $apellidos, 
            $email, $telefono, $direccion, $fechaNacimiento, 
            $estado, 
            $id
        ]);
        
        header('Location: ../index.php?status=success_update');
        exit; 

    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 2627 || $e->errorInfo[1] == 2601) {
            header('Location: ../index.php?status=error_duplicado');
        } else {
            header('Location: ../index.php?status=error_db');
        }
        exit;
    }

} else {
    
    if (!isset($_GET['id'])) {
        header('Location: ../index.php');
        exit;
    }
    
    $id = $_GET['id'];

    $sql = "SELECT * FROM terceros WHERE id = ?";
    $sentencia = $pdo->prepare($sql);
    $sentencia->execute([$id]);
    $tercero = $sentencia->fetch(PDO::FETCH_OBJ);

    if (!$tercero) {
        header('Location: ../index.php?status=notfound');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tercero</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
    <div class="card form-card">
        <h2>Editando a: <?php echo htmlspecialchars($tercero->nombres); ?></h2>
        
        <form action="" method="POST">
            
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
            <button type="submit" href="../index.php">Cancelar</button>
        </form>
    </div>
</div>

</body>
</html>