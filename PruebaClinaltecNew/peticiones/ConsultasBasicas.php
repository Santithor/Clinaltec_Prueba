<?php

header('Content-Type: application/json');
include_once("../includes/funciones.php");

// Verifica si se ha enviado la opción en la solicitud POST
if (isset($_POST["opcion"])) {
    $opcion = $_POST["opcion"];
    $response = array();

    if ($opcion == "ConsultaDepartamentos") {
        // Consulta para obtener todos los departamentos
        $consulta = "SELECT ID, DESCRIPCION FROM TP_DEPARTAMENTOS";
        $departamentos = ejecutarConsulta($consulta, array(), true); // True para obtener los datos
        
        if ($departamentos) {
            $response = array(
                'success' => true,
                'data' => $departamentos
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron departamentos.'
            );
        }
    } elseif ($opcion == "ConsultaGeneros") {
        // Consulta para obtener todos los géneros
        $consulta = "SELECT ID, DESCRIPCION FROM TP_SEXOS";
        $generos = ejecutarConsulta($consulta, array(), true); // True para obtener los datos
        
        if ($generos) {
            $response = array(
                'success' => true,
                'data' => $generos
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'No se encontraron géneros.'
            );
        }
    } elseif ($opcion == "ConsultaMunicipios") {
        // Verifica si el 'departamento_id' está en la solicitud
        if (isset($_POST["departamento_id"])) {
            $departamento_id = $_POST["departamento_id"];
            $consulta = "SELECT ID, DESCRIPCION FROM TP_MUNICIPIOS WHERE DEPARTAMENTO_ID = :departamento_id";
            $municipios = ejecutarConsulta($consulta, array(':departamento_id' => $departamento_id), true); // True para obtener los datos
            
            if ($municipios) {
                $response = array(
                    'success' => true,
                    'data' => $municipios
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'No se encontraron municipios para el departamento seleccionado.'
                );
            }
        } else {
            $response = array(
                'success' => false,
                'message' => 'ID de departamento no proporcionado.'
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Opción no reconocida.'
        );
    }

    // Envía la respuesta JSON
    echo json_encode($response);
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'No se ha especificado ninguna opción.'
    ));
}

?>
