<?php
header('Content-Type: application/json');

include_once("Conexion.php");
$conexion_bd = new Conexion();
$pdo = $conexion_bd->conn;

$accion = $_REQUEST['accion'] ?? 'leer';

$respuesta = [
    'status' => 'error',
    'message' => 'Acción no válida'
];

try {
    
    switch ($accion) {
        
        case 'leer':
            $sentencia = $pdo->query("SELECT * FROM terceros ORDER BY id DESC");
            $terceros = $sentencia->fetchAll(PDO::FETCH_OBJ);
            $respuesta = [
                'status' => 'success',
                'data' => $terceros
            ];
            break;

        case 'leer_uno':
            $id = $_GET['id'];
            $sql = "SELECT * FROM terceros WHERE id = ?";
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute([$id]);
            $tercero = $sentencia->fetch(PDO::FETCH_OBJ);
            $respuesta = [
                'status' => 'success',
                'data' => $tercero
            ];
            break;

        case 'crear':
            $sql = "INSERT INTO terceros (tipoDocumento, numeroDocumento, nombres, apellidos, email, telefono, direccion, fechaNacimiento, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute([
                $_POST['tipoDocumento'], $_POST['numeroDocumento'], $_POST['nombres'],
                $_POST['apellidos'], $_POST['email'], $_POST['telefono'],
                $_POST['direccion'], $_POST['fechaNacimiento'], $_POST['estado']
            ]);
            $respuesta = ['status' => 'success_create'];
            break;

        case 'actualizar':
            $sql = "UPDATE terceros SET tipoDocumento = ?, numeroDocumento = ?, nombres = ?, 
                    apellidos = ?, email = ?, telefono = ?, direccion = ?, fechaNacimiento = ?, estado = ? 
                    WHERE id = ?";
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute([
                $_POST['tipoDocumento'], $_POST['numeroDocumento'], $_POST['nombres'],
                $_POST['apellidos'], $_POST['email'], $_POST['telefono'],
                $_POST['direccion'], $_POST['fechaNacimiento'], $_POST['estado'], $_POST['id']
            ]);
            $respuesta = ['status' => 'success_update'];
            break;

        case 'eliminar':
            $sql = "DELETE FROM terceros WHERE id = ?";
            $sentencia = $pdo->prepare($sql);
            $sentencia->execute([$_POST['id']]);
            $respuesta = ['status' => 'success_delete'];
            break;
    }

} catch (PDOException $e) {
    if ($e->errorInfo[1] == 2627 || $e->errorInfo[1] == 2601) {
        $respuesta = ['status' => 'error_duplicado'];
    } else {
        $respuesta = ['status' => 'error_db', 'message' => $e->getMessage()];
    }
}

echo json_encode($respuesta);
?>