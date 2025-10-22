<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require "conexion.php";

// Llama a la función PDO
$pdo = getPDO();

// Obtener los datos
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

            $requiredFields = ['idboleteria', 'idlocalidad', 'valor', 'stock'];

            foreach ($requiredFields as $field) {
                if (empty(trim($data[$field] ?? ''))) {
                    throw new Exception("El campo '$field' es obligatorio");
                }
            }

            
            $stmt_insert = $pdo->prepare("INSERT INTO boleteria (idboleteria, idlocalidad, valor, stock) VALUES (?, ?, ?, ?)");
            $stmt_insert->execute([
                trim($data['idboleteria']),
                trim($data['idlocalidad']),
                trim($data['valor']),
                trim($data['stock'])
            ]);

            
            $newIdBoleteria = $pdo->lastInsertId();
            $stmt_update = $pdo->prepare("UPDATE boleteria SET stock = ? WHERE idboleteria = ?");
            $stmt_update->execute([trim($data['stock']), $newIdBoleteria]);
            

            echo json_encode([
                'success' => true,
                'message' => 'Tipo de Boleta creado correctamente en la boletería.',
                'idboleteria' => $pdo->lastInsertId()
            ]);

            break;
        case 'read':

            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                http_response_code(405);
                throw new Exception('Método no permitido. Use GET para la acción "read".');
            }

            $stmt = $pdo->query("SELECT valor, stock FROM boleteria ORDER BY idboleteria");
            $localidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'boleteria' => $boleteria
            ]);
            break;

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
