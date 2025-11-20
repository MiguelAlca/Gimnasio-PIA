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

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT IdSucursal, Nombre, Direccion FROM sucursal");
    $sucursales = [];

    while ($row = $result->fetch_assoc()) {
        $sucursales[] = $row;
    }

    echo json_encode($sucursales);
    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre = $data['nombre'] ?? '';
    $direccion = $data['direccion'] ?? '';
    $idUsuario = 1;

    if (!empty($nombre) && !empty($direccion)) {
        $stmt = $conn->prepare("INSERT INTO sucursal (Nombre, Direccion, IdUsuario) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nombre, $direccion, $idUsuario);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Sucursal agregada correctamente."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Faltan datos."]);
    }

    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;
    $nombre = $data['nombre'] ?? '';
    $direccion = $data['direccion'] ?? '';

    if ($id && $nombre && $direccion) {
        $stmt = $conn->prepare("UPDATE sucursal SET Nombre = ?, Direccion = ? WHERE IdSucursal = ?");
        $stmt->bind_param("ssi", $nombre, $direccion, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Sucursal modificada."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al modificar."]);
        }

        $stmt->close();
    }
    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM sucursal WHERE IdSucursal = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Sucursal eliminada."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al eliminar."]);
        }

        $stmt->close();
    }

    $conn->close();
    exit;
}    
?>