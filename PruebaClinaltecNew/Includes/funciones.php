<?php
include_once("conexion.php");

function ejecutarConsulta($sql, $parametros = []) {
    try {
        $conn = conectar(); // Asumiendo que la función conectar() devuelve una conexión PDO

        // Preparar la consulta
        $stmt = $conn->prepare($sql);

        // Ejecutar la consulta con parámetros
        $stmt->execute($parametros);

        // Verificar si es una consulta SELECT
        if (strpos(strtoupper($sql), 'SELECT') === 0) {
            // Devolver los resultados en formato de array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Para consultas de tipo INSERT, UPDATE, DELETE
            return $stmt->rowCount();
        }
    } catch (PDOException $e) {
        // Manejo de errores
        error_log("Error en la consulta: " . $e->getMessage());
        return -1; // Indicador de error
    }
}

?>
