<?php

class Conexion {

 
    public $conn;


    public function __construct() {

        $ServerOption = '3';

        if ($ServerOption == '1'){
            $host = 'GEFORCE-DC';       
            $dbname = 'DBCRUD';       
            $username = 'sa';           
            $pass = 'DcPc1*';        
            $port = 1433;          
        }else if ($ServerOption == '2'){
            $host = 'IRISBOOK-DC';       
            $dbname = 'DBCRUD';       
            $username = 'sa';           
            $pass = 'Dilan123';        
            $port = 1433;
        }


        try {
     
            $this->conn = new PDO("sqlsrv:Server=$host,$port;Database=$dbname", $username, $pass);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exp) {
            die("No se pudo conectar, error: " . $exp->getMessage());
        }
    }
}
?>