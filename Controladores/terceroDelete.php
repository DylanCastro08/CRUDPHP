
<?php

if (!isset($_GET['id'])) {
    header('Location: ../index.php?status=error_no_id');
    exit;
}


include_once("Conexion.php");
$id = $_GET['id'];


$sql = "DELETE FROM terceros WHERE id = ?";

try {

    $conexion_bd = new Conexion();
    $pdo = $conexion_bd->conn;

    $sentencia = $pdo->prepare($sql);
    

    $sentencia->execute([$id]);


    header('Location: ../index.php?status=success_delete');

} catch (PDOException $e) {
    header('Location: ../index.php?status=error_db');
}
?>