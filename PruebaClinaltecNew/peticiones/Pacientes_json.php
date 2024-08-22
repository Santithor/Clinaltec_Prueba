<?php
// Incluir archivo de conexión
include_once("../includes/funciones.php");

header('Content-Type: application/json');

// Leer datos JSON del cuerpo de la solicitud
$datos = json_decode(file_get_contents('php://input'), true);

// Preparar respuesta
$response = ['success' => false, 'data' => [], 'message' => ''];

// Guardar paciente
if (isset($datos['opcion']) && $datos['opcion'] === 'GuardarPaciente') {
    $numero_documento = $datos['numero_documento'];
    $nombre = $datos['nombre'];
    $edad = $datos['edad'];
    $generoId = $datos['genero_id'];
    $departamentoId = $datos['departamento_id'];
    $municipioId = $datos['municipio_id'];

    $sql = "INSERT INTO TB_PACIENTES (NUMERO_DOCUMENTO, NOMBRE, EDAD, GENERO_ID, DEPARTAMENTO_ID, MUNICIPIO_ID) VALUES (?, ?, ?, ?, ?, ?)";
    
    $resultado = ejecutarConsulta($sql, [$numero_documento, $nombre, $edad, $generoId, $departamentoId, $municipioId]);

    if ($resultado) {
        $response['success'] = true;
        $response['message'] = 'Paciente guardado con éxito.';
    } else {
        $response['message'] = 'Error al guardar el paciente.';
    }
}

// Consultar todos los pacientes
elseif (isset($datos['opcion']) && $datos['opcion'] === 'ConsultarPacientes') {
    $sql = "SELECT  TB_P.ID, 
                    TB_P.NUMERO_DOCUMENTO,
                    TB_P.NOMBRE, 
                    TB_P.EDAD, 
                    TB_P.GENERO_ID, 
                    GEN.DESCRIPCION SEXO,
                    TB_P.DEPARTAMENTO_ID, 
                    DEP.DESCRIPCION DEPARTAMENTO, 
                    TB_P.MUNICIPIO_ID,
                    MUN.DESCRIPCION MUNICIPIO 
            FROM TB_PACIENTES TB_P
            INNER JOIN TP_DEPARTAMENTOS DEP ON DEP.ID = TB_P.DEPARTAMENTO_ID
            INNER JOIN TP_SEXOS GEN ON GEN.ID = TB_P.GENERO_ID
            INNER JOIN TP_MUNICIPIOS MUN ON MUN.ID = TB_P.MUNICIPIO_ID
                                        AND MUN.DEPARTAMENTO_ID = TB_P.DEPARTAMENTO_ID";
    $result = ejecutarConsulta($sql);

    if ($result !== -1 && is_array($result)) {
        $response['success'] = true;
        $response['data'] = $result;
    } else {
        $response['message'] = 'Error al consultar los pacientes.';
    }
}

// Consultar paciente por ID
elseif (isset($datos['opcion']) && $datos['opcion'] === 'ConsultarPaciente') {
    $pacienteId = $datos['paciente_id'];

    if ($pacienteId) {
        $sql = "SELECT  TB_P.ID, 
                        TB_P.NUMERO_DOCUMENTO,
                        TB_P.NOMBRE, 
                        TB_P.EDAD, 
                        TB_P.GENERO_ID, 
                        TB_P.DEPARTAMENTO_ID, 
                        TB_P.MUNICIPIO_ID,
                        DEP.DESCRIPCION DEPARTAMENTO,
                        MUN.DESCRIPCION MUNICIPIO
                FROM TB_PACIENTES TB_P
                INNER JOIN TP_DEPARTAMENTOS DEP ON DEP.ID = TB_P.DEPARTAMENTO_ID
                INNER JOIN TP_MUNICIPIOS MUN ON MUN.ID = TB_P.MUNICIPIO_ID
                                            AND MUN.DEPARTAMENTO_ID = TB_P.DEPARTAMENTO_ID
                WHERE TB_P.ID = ?";
        $result = ejecutarConsulta($sql, [$pacienteId]);

        if ($result !== -1 && is_array($result)) {
            if (count($result) > 0) {
                $response['success'] = true;
                $response['data'] = $result[0];
            } else {
                $response['message'] = 'Paciente no encontrado.';
            }
        } else {
            $response['message'] = 'Error al consultar el paciente.';
        }
    } else {
        $response['message'] = 'ID de paciente no especificado.';
    }
}

// Actualizar paciente
elseif (isset($datos['opcion']) && $datos['opcion'] === 'ActualizarPaciente') {
    $pacienteId = $datos['paciente_id'];
    $numero_documento = $datos['numero_documento'];
    $nombre = $datos['nombre'];
    $edad = $datos['edad'];
    $generoId = $datos['genero_id'];
    $departamentoId = $datos['departamento_id'];
    $municipioId = $datos['municipio_id'];

    $sql = "UPDATE TB_PACIENTES 
            SET NUMERO_DOCUMENTO = ?, NOMBRE = ?, EDAD = ?, GENERO_ID = ?, DEPARTAMENTO_ID = ?, MUNICIPIO_ID = ? 
            WHERE ID = ?";
    
    $resultado = ejecutarConsulta($sql, [$numero_documento, $nombre, $edad, $generoId, $departamentoId, $municipioId, $pacienteId]);

    if ($resultado) {
        $response['success'] = true;
        $response['message'] = 'Paciente actualizado con éxito.';
    } else {
        $response['message'] = 'Error al actualizar el paciente.';
    }
}

// Eliminar paciente
elseif (isset($datos['opcion']) && $datos['opcion'] === 'EliminarPaciente') {
    $pacienteId = $datos['paciente_id'];

    if ($pacienteId) {
        $sql = "DELETE FROM TB_PACIENTES WHERE ID = ?";
        $resultado = ejecutarConsulta($sql, [$pacienteId]);

        if ($resultado) {
            $response['success'] = true;
            $response['message'] = 'Paciente eliminado con éxito.';
        } else {
            $response['message'] = 'Error al eliminar el paciente.';
        }
    } else {
        $response['message'] = 'ID de paciente no especificado.';
    }
}

// Enviar respuesta en formato JSON
echo json_encode($response);
?>
