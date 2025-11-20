<?php
session_start();

$host = "127.0.0.1";
$db   = "mydb";
$user = "root";
$pass = "2707";

$conn = new mysqli($host, $user, $pass, $db);

$sql = "SELECT p.IdPlan, p.Nombre, p.Precio, v.Ventajas
        FROM plan p
        JOIN ventajasdelplan vp ON p.IdPlan = vp.IdPlan
        JOIN Ventajas v ON v.idVentajas = vp.IdVentajas
        ORDER BY p.IdPlan";

$result = $conn->query($sql);

$planes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['IdPlan'];
        if (!isset($planes[$id])) {
            $planes[$id] = [
                'Nombre' => $row['Nombre'],
                'Precio' => $row['Precio'],
                'Ventajas' => []
            ];
        }
        $planes[$id]['Ventajas'][] = $row['Ventajas'];
    }
}

header('Content-Type: application/json');
echo json_encode(array_values($planes));

$conn->close();
?>