<?php
function conectar() {
    $serverName = "localhost\\SQLEXPRESS";
    $database = "BD_Clinaltec";

    try {
        $conn = new PDO("sqlsrv:server=$serverName;Database=$database", null, null, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::SQLSRV_ATTR_DIRECT_QUERY => true
        ));
        return $conn;
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
    }
}
?>
