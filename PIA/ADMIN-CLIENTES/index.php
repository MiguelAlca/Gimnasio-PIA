<?php

$host = "127.0.0.1";
$db = "mydb";
$user = "root";
$pass = "2707";

$conn = new mysqli($host, $user, $pass, $db);

$search = $_GET['q'] ?? '';

$sql = "SELECT Usuario.IdUsuario, Usuario.Nombre, Suscripcion.Fecha_fin 
        FROM Usuario 
        JOIN Suscripcion ON Usuario.IdUsuario = Suscripcion.IdUsuario
        WHERE Usuario.Nombre LIKE ?";

$stmt = $conn->prepare($sql);
$param = '%' . $search . '%';
$stmt->bind_param("s", $param);
$stmt->execute();
$result = $stmt->get_result();

$usuario = [];
while ($row = $result->fetch_assoc()) {
    $usuario[] = $row;
}

header('Content-Type: application/json');
echo json_encode($usuario);

$conn->close();
?>