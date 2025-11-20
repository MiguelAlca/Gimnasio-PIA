<?php
session_start();

if (!isset($_SESSION['IdRol']) || $_SESSION['IdRol'] != 2) {
    http_response_code(403);
    echo "No tiene permisos de administrador.";
    exit();
}

$host = "127.0.0.1";
$db = "mydb";
$user = "root";
$pass = "2707";

$conn = new mysqli($host, $user, $pass, $db);

$anio = $_GET['anio'] ?? date('Y');
$mes = $_GET['mes'] ?? date('m');

$meses = [
    "ENE" => "01", "FEB" => "02", "MAR" => "03",
    "ABR" => "04", "MAY" => "05", "JUN" => "06",
    "JUL" => "07", "AGO" => "08", "SEP" => "09",
    "OCT" => "10", "NOV" => "11", "DIC" => "12"
];

$mesNum = $meses[strtoupper($mes)] ?? "01";

$sql = "
    SELECT IdPlan, COUNT(*) AS total
    FROM Suscripcion
    WHERE YEAR(Fecha_inicio) = ? AND MONTH(Fecha_inicio) = ?
    GROUP BY IdPlan
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $anio, $mesNum);
$stmt->execute();
$result = $stmt->get_result();

$data = [
    "plan1" => 0,
    "plan2" => 0,
    "plan3" => 0
];

while ($row = $result->fetch_assoc()) {
    $plan = "plan" . intval($row['IdPlan']);
    if (isset($data[$plan])) {
        $data[$plan] = intval($row['total']);
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>