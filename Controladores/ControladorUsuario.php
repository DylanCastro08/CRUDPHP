<?php
if (!isset($pdo)) {
    include_once("Conexion.php");
    $conexion_bd = new Conexion();
    $pdo = $conexion_bd->conn;
}

$accion = $_REQUEST['accion'] ?? 'leer';

try {
    
    switch ($accion) {
        
        case 'crear':
            if (!isset($_POST['nombres']) || empty($_POST['nombres'])) {
                header('Location: index.php?status=error_datos');
                exit;
            }
            
            $tipoDocumento = $_POST['tipoDocumento'];
            $numeroDocumento = $_POST['numeroDocumento'];
            $nombres = $_POST['nombres'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $fechaNacimiento = $_POST['fechaNacimiento'];
            $estado = $_POST['estado'];

            $sql = "INSERT INTO terceros (tipoDocumento, numeroDocumento, nombres, apellidos, email, telefono, direccion, fechaNacimiento, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute([
                $tipoDocumento, $numeroDocumento, $nombres, $apellidos,
                $email, $telefono, $direccion, $fechaNacimiento, $estado
            ]);
            
            header('Location: index.php?status=success_create');
            exit; 
       
        case 'actualizar':
            if (!isset($_POST['id'])) {
                header('Location: index.php?status=error_no_id');
                exit;
            }
            
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

            $sql = "UPDATE terceros SET tipoDocumento = ?, numeroDocumento = ?, nombres = ?, 
                    apellidos = ?, email = ?, telefono = ?, direccion = ?, fechaNacimiento = ?, estado = ? 
                    WHERE id = ?";
            
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute([
                $tipoDocumento, $numeroDocumento, $nombres, $apellidos,
                $email, $telefono, $direccion, $fechaNacimiento, $estado, $id
            ]);
            
            header('Location: index.php?status=success_update');
            exit; 

        
        case 'eliminar':
            if (!isset($_GET['id'])) {
                header('Location: index.php?status=error_no_id');
                exit;
            }
            
            $id = $_GET['id'];
            $sql = "DELETE FROM terceros WHERE id = ?";
            
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute([$id]);
            
            header('Location: index.php?status=success_delete');
            exit; 

        
        case 'editar':
            if (!isset($_GET['id'])) {
                header('Location: index.php?status=error_no_id');
                exit;
            }
            $id = $_GET['id'];

            $sql = "SELECT * FROM terceros WHERE id = ?";
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute([$id]);
            $tercero = $sentencia->fetch(PDO::FETCH_OBJ);

            if (!$tercero) {
                header('Location: index.php?status=notfound');
                exit;
            }            
            include 'Vistas/terceroEdit.php';
            
            exit; 

        

        case 'leer':
        default:
            $sentencia = $pdo->query("SELECT id, tipoDocumento, numeroDocumento, nombres, apellidos, email, telefono, estado FROM terceros ORDER BY id DESC");
            $terceros = $sentencia->fetchAll(PDO::FETCH_OBJ);
            break; 
    }

} catch (PDOException $e) {
    if ($e->errorInfo[1] == 2627 || $e->errorInfo[1] == 2601) {
        $error_redirect = 'error_duplicado';
    } else {
        $error_redirect = 'error_db';
    }
    header('Location: index.php?status=' . $error_redirect);
    exit;
}
?>