<?php
function getPDO()
{
    $host = 'localhost';
    $db = 'cultutickets';
    $user = 'root';
    $pass = 'toor';
    $port ='3306';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, $user, $pass, $options);

    echo json_encode([
        'success' => true,
        'message' => 'conexion a la db correctamente',
        'id' => $pdo->lastInsertId()
    ]);
}
?>