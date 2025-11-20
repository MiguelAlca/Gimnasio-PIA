<?php
header('Content-Type: application/json');

$host = "127.0.0.1";
$db   = "mydb";
$user = "root";
$pass = "2707";

$conn = new mysqli($host, $user, $pass, $db);

$sql = "SELECT IdSucursal, Nombre, Direccion FROM sucursal";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $sucursales = [];

    while ($row = $result->fetch_assoc()) {
        $sucursales[] = $row;
    }

    echo json_encode($sucursales);
} else {
    echo json_encode([]);
}

$conn->close();
?>