<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require "conexion.php";

// Llama a la función PDO para evitar inyecciones sql
$pdo = getPDO();

/**
 * Función para obtener los datos de entrada, manejando JSON y POST.
 * @return array
 */
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
            $requiredFields = ['nombre', 'apellido', 'genero', 'ciudad'];

            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    throw new Exception("El campo '$field' es obligatorio.");
                }
            }

            
            $stmt = $pdo->prepare("INSERT INTO artistas (nombre, apellido, genero, ciudad) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                trim($data['nombre']),
                trim($data['apellido']),
                trim($data['genero']),
                trim($data['ciudad']),
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Artista creado correctamente.',
                'idartistas' => $pdo->lastInsertId()
            ]);

        case 'read':

            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                http_response_code(405);
                throw new Exception('Método no permitido. Use GET para la acción "read".');
            }

            $stmt = $pdo->query("SELECT nombre, apellido, genero, ciudad FROM artistas ORDER BY idartista");
            $artistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'artistas' => $artistas
            ]);
            break;
           

        default:
            throw new Exception('Acción no válida. Usa: create');
    }
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(400); 
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'received_data' => $data ?? null
    ]);
}
