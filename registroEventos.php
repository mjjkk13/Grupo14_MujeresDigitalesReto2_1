<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require "conexion.php";

//Llama a la función PDO para evitar inyecciones sql
$pdo = getPDO();


//Respuesta satisfactoria servidor
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

function getInputData()
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
    return $_POST;
}

try {
    $data = getInputData();

    if (empty($data['action'])) {
        throw new Exception('El parámetro "action" es requerido');
    }
    switch ($data['action']) {

        case 'create':

            if ($data['action'] === 'create') {
                $requiredFields = ['nombre', 'descripcion','municipio','departamento', 'fecha_horaInicio', 'fecha_horaFin'];
                foreach ($requiredFields as $field) {
                    if (empty($data[$field])) {
                        throw new Exception("El campo '$field' es obligatorio");
                    }
                }


                $stmt = $pdo->prepare("INSERT INTO eventos (nombre, descripcion,municipio,departamento, fecha_horaInicio, fecha_horaFin) VALUES (?, ?, ?, ?, ?,?)");
                $stmt->execute([
                    trim($data['nombre']),
                    trim($data['descripcion']),
                    trim($data['municipio']),
                    trim($data['departamento']),
                    trim($data['fecha_horaInicio']),
                    trim($data['fecha_horaFin']),
                ]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Evento creado correctamente',
                    'id' => $pdo->lastInsertId()
                ]);
            }
        
        default:
            throw new Exception('Acción no válida. Usa: create');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'received_data' => $data ?? null
    ]);
}
