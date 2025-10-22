<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require "conexion.php";
$pdo = getPDO();

$response = [
    'success' => false,
    'data' => [],
    'message' => '',
    'count' => 0
];

try {
    // Validar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        throw new Exception('Método no permitido. Use GET');
    }

    // No parámetros en la URL
    if (!empty($_GET)) {
        http_response_code(400);
        throw new Exception('Parámetros de consulta no permitidos');
    }

    // Consulta todos los eventos
    $sql = "
        SELECT * 
        FROM eventos 
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($eventos);

    $response = [
        'success' => true,
        'data' => $eventos,
        'count' => $count,
        'message' => "$count eventos encontrados"
    ];

    http_response_code(200);
} catch (PDOException $e) {
    error_log('Error en obtenerCursos: ' . $e->getMessage());

    $response['message'] = 'Error al obtener los eventos: ' . $e->getMessage();
    http_response_code(500);
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    // Si el código HTTP no es válido, usar 400 por defecto
    $code = (int)$e->getCode();
    http_response_code(($code >= 400 && $code < 600) ? $code : 400);
}

//Respuesta json
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>