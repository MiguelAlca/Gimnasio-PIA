<?php
session_start();

if (!isset($_SESSION['IdRol']) || $_SESSION['IdRol'] != 2) {
    http_response_code(403);
    echo "No tiene permisos de administrador.";
    exit();
}

$host = "127.0.0.1";
$db   = "mydb";
$user = "root";
$pass = "2707";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Error al conectar a la base de datos.";
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_REQUEST['action'] ?? '';

if ($method === 'GET' && $action === 'cargar') {
    $result = $conn->query("SELECT IdNotificacion, Mensaje, Fecha FROM notificacion ORDER BY Fecha DESC");
    $avisos = [];

    while ($row = $result->fetch_assoc()) {
        $avisos[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($avisos);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $mensaje = trim($data['mensaje'] ?? '');
    $id = $data['id'] ?? null;

    switch ($action) {
        case 'crear':
            if ($mensaje === '') {
                http_response_code(400);
                echo "El mensaje no puede estar vacío.";
                break;
            }

            $stmt = $conn->prepare("INSERT INTO notificacion (Mensaje, Fecha) VALUES (?, NOW())");
            $stmt->bind_param("s", $mensaje);
            $stmt->execute();
            echo "Aviso guardado.";
            $stmt->close();
            break;

        case 'editar':
            if (!$id || $mensaje === '') {
                http_response_code(400);
                echo "Faltan datos para actualizar.";
                break;
            }

            $stmt = $conn->prepare("UPDATE notificacion SET Mensaje = ? WHERE IdNotificacion = ?");
            $stmt->bind_param("si", $mensaje, $id);
            $stmt->execute();
            echo "Aviso actualizado.";
            $stmt->close();
            break;

        case 'eliminar':
            if (!$id) {
                http_response_code(400);
                echo "No se proporcionó ID para eliminar.";
                break;
            }

            $stmt = $conn->prepare("DELETE FROM notificacion WHERE IdNotificacion = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            echo "Aviso eliminado.";
            $stmt->close();
            break;

        default:
            http_response_code(400);
            echo "Acción no válida.";
    }
}
else {
    http_response_code(405);
    echo "Método no permitido.";
}

$conn->close();
?>