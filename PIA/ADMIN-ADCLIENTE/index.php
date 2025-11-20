<?php
header('Content-Type: application/json');

$host = "127.0.0.1";
$db = "mydb";
$user = "root";
$pass = "2707";

$conn = new mysqli($host, $user, $pass, $db);

$data = json_decode(file_get_contents("php://input"), true);
$idUsuario = $data['IdUsuario'];

$sql = "UPDATE Suscripcion SET IdEstado = 3 WHERE IdUsuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$updateSuccess = $stmt->execute();
$stmt->close();

$sqlInfo = "
    SELECT u.Nombre, p.Nombre AS Plan 
    FROM Usuario u
    JOIN Suscripcion s ON u.IdUsuario = s.IdUsuario
    JOIN Plan p ON s.IdPlan = p.IdPlan
    WHERE u.IdUsuario = ?
";
$stmtInfo = $conn->prepare($sqlInfo);
$stmtInfo->bind_param("i", $idUsuario);
$stmtInfo->execute();
$result = $stmtInfo->get_result();

$info = $result->fetch_assoc();
$stmtInfo->close();

if ($updateSuccess && $info) {
    $sqlEstado = "SELECT IdEstado FROM Suscripcion WHERE IdUsuario = ?";
    $stmtEstado = $conn->prepare($sqlEstado);
    $stmtEstado->bind_param("i", $idUsuario);
    $stmtEstado->execute();
    $resultEstado = $stmtEstado->get_result();
    $estadoRow = $resultEstado->fetch_assoc();
    $stmtEstado->close();

    $sqlPagos = "SELECT Fecha_pago, Monto FROM Pago WHERE IdUsuario = ? ORDER BY Fecha_pago DESC";
    $stmtPagos = $conn->prepare($sqlPagos);
    $stmtPagos->bind_param("i", $idUsuario);
    $stmtPagos->execute();
    $resultPagos = $stmtPagos->get_result();

    $historial = [];
    while ($fila = $resultPagos->fetch_assoc()) {
        $fecha = date("d/m/Y", strtotime($fila['Fecha_pago']));
        $historial[] = "Suscripción mensual - $" . $fila['Monto'] . " - tarjeta **** - " . $fecha;
    }

    $stmtPagos->close();
    echo json_encode([
        "ok" => true,
        "mensaje" => "La suscripción se canceló correctamente.",
        "usuario" => $info['Nombre'],
        "plan" => $info['Plan'],
        "estado" => $estadoRow['IdEstado'],
        "pagos" => $historial
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "No se pudo completar la operación."
    ]);
}

$conn->close();
?>