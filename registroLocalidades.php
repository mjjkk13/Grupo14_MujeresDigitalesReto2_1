<?php
require "conexion.php";

// Llama a la función PDO
$pdo = getPDO();

/**
 * Obtiene los datos de entrada, manejando JSON y POST.
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
    
    $data = $_SERVER['REQUEST_METHOD'] === 'GET' ? $_GET : getInputData();

    if (empty($data['action'])) {
     
        throw new Exception('El parámetro "action" es requerido');
    }

    switch ($data['action']) {

       
        case 'create':

            
            $requiredFields = ['nombre'];

            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    throw new Exception("El campo '$field' es obligatorio");
                }
            }

           
            $stmt = $pdo->prepare("INSERT INTO localidades (nombre) VALUES (?)");
            $stmt->execute([
                trim($data['nombre'])
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Localidad creada correctamente',
                'idlocalidad' => $pdo->lastInsertId() 
            ]);
            break;

       
        case 'read':
           
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                http_response_code(405);
                throw new Exception('Método no permitido. Use GET para la acción "read".');
            }

            $stmt = $pdo->query("SELECT idlocalidad, nombre FROM localidades ORDER BY idlocalidad");
            $localidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'localidades' => $localidades
            ]);
            break;

        default:
            throw new Exception('Acción no válida. Use: create o read');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'received_data' => $data ?? null
    ]);
}
