<?php
if (
    !isset($_POST['nombres']) || empty($_POST['nombres']) ||
    !isset($_POST['apellidos']) || empty($_POST['apellidos']) ||
    !isset($_POST['numeroDocumento']) || empty($_POST['numeroDocumento']) ||
    !isset($_POST['email']) || empty($_POST['email'])
) {
    header('Location: ../index.php?status=error_datos');
    exit;
}

include_once("Conexion.php");

$tipoDocumento = $_POST['tipoDocumento'];
$numeroDocumento = $_POST['numeroDocumento'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$estado = $_POST['estado'];


$sql = "INSERT INTO terceros 
        (tipoDocumento, numeroDocumento, nombres, apellidos, email, telefono, direccion, fechaNacimiento, estado) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 

try {
    $conexion_bd = new Conexion();
    $pdo = $conexion_bd->conn;

    $sentencia = $pdo->prepare($sql);

    $sentencia->execute([
        $tipoDocumento,
        $numeroDocumento,
        $nombres,
        $apellidos,
        $email,
        $telefono,
        $direccion,
        $fechaNacimiento,
        $estado 
    ]);

    
    header('Location: ../index.php?status=success_create');

} catch (PDOException $e) {
  
    if ($e->errorInfo[1] == 2627 || $e->errorInfo[1] == 2601) {
        header('Location: ../index.php?status=error_duplicado');
    } else {
        header('Location: ../index.php?status=error_db');
    }
}
?>