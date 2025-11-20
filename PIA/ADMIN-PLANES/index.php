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

$method = $_SERVER["REQUEST_METHOD"];

if($method === "POST"){
    $action = $_POST["action"] ?? "";

    if ($action === "crear") {
        $nombre = $_POST['nombre'] ?? '';
        $info = $_POST['info'] ?? '';
        $precio = $_POST['precio'] ?? '';

        if ($nombre && $precio && $info) {
            $stmt_plan = $conn->prepare("INSERT INTO plan (Nombre, Precio) VALUES (?, ?)");
            $stmt_plan->bind_param("sd", $nombre, $precio);

            if ($stmt_plan->execute()) {
                $plan_id = $stmt_plan->insert_id;
                $ventajas_array = array_filter(array_map('trim', explode("\n", $info)));

                foreach ($ventajas_array as $ventaja_texto) {
                    $stmt_check = $conn->prepare("SELECT idVentajas FROM ventajas WHERE Ventajas = ?");
                    $stmt_check->bind_param("s", $ventaja_texto);
                    $stmt_check->execute();
                    $result = $stmt_check->get_result();

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $ventaja_id = $row['idVentajas'];
                    } else {
                        $stmt_insert = $conn->prepare("INSERT INTO ventajas (Ventajas) VALUES (?)");
                        $stmt_insert->bind_param("s", $ventaja_texto);
                        $stmt_insert->execute();
                        $ventaja_id = $stmt_insert->insert_id;
                        $stmt_insert->close();
                    }
                    $stmt_check->close();

                    $stmt_rel = $conn->prepare("INSERT INTO ventajasdelplan (idPlan, idVentajas) VALUES (?, ?)");
                    $stmt_rel->bind_param("ii", $plan_id, $ventaja_id);
                    $stmt_rel->execute();
                    $stmt_rel->close();
                    }
                    echo "Plan creado correctamente.";
                } 

                $stmt_plan->close();
            } else {
                echo "Todos los campos son obligatorios.";
        }
    } elseif ($action === "eliminar") {
        $nombre = $_POST['nombre'] ?? '';

        if ($nombre) {
            $stmt_get_id = $conn->prepare("SELECT idPlan FROM plan WHERE Nombre = ?");
            $stmt_get_id->bind_param("s", $nombre);
            $stmt_get_id->execute();
            $result = $stmt_get_id->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $idPlan = $row['idPlan'];

                $conn->query("DELETE FROM ventajasdelplan WHERE idPlan = $idPlan");
                $conn->query("DELETE FROM plan WHERE idPlan = $idPlan");

                echo "Plan eliminado correctamente.";
            } 
            $stmt_get_id->close();
        }

    } elseif ($action === "editar") {
        $nombre_original = $_POST['nombre_original'] ?? '';
        $nuevo_nombre = $_POST['nombre'] ?? '';
        $nuevo_precio = $_POST['precio'] ?? '';
        $nueva_info = $_POST['info'] ?? '';

        if ($nombre_original && $nuevo_nombre && $nuevo_precio && $nueva_info) {
            $stmt_get_id = $conn->prepare("SELECT idPlan FROM plan WHERE Nombre = ?");
            $stmt_get_id->bind_param("s", $nombre_original);
            $stmt_get_id->execute();
            $result = $stmt_get_id->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $idPlan = $row['idPlan'];

                $stmt_update = $conn->prepare("UPDATE plan SET Nombre = ?, Precio = ? WHERE idPlan = ?");
                $stmt_update->bind_param("sdi", $nuevo_nombre, $nuevo_precio, $idPlan);
                $stmt_update->execute();
                $stmt_update->close();

                $conn->query("DELETE FROM ventajasdelplan WHERE idPlan = $idPlan");

                $ventajas_array = array_filter(array_map('trim', explode("\n", $nueva_info)));
                foreach ($ventajas_array as $ventaja_texto) {
                    $stmt_check = $conn->prepare("SELECT idVentajas FROM ventajas WHERE Ventajas = ?");
                    $stmt_check->bind_param("s", $ventaja_texto);
                    $stmt_check->execute();
                    $result = $stmt_check->get_result();

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $ventaja_id = $row['idVentajas'];
                    } else {
                        $stmt_insert = $conn->prepare("INSERT INTO ventajas (Ventajas) VALUES (?)");
                        $stmt_insert->bind_param("s", $ventaja_texto);
                        $stmt_insert->execute();
                        $ventaja_id = $stmt_insert->insert_id;
                        $stmt_insert->close();
                    }

                    $stmt_check->close();

                    $stmt_rel = $conn->prepare("INSERT INTO ventajasdelplan (idPlan, idVentajas) VALUES (?, ?)");
                    $stmt_rel->bind_param("ii", $idPlan, $ventaja_id);
                    $stmt_rel->execute();
                    $stmt_rel->close();
                }

                echo "Plan actualizado correctamente.";
            }
            $stmt_get_id->close();
        } else {
            echo "Todos los campos son obligatorios.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    header('Content-Type: application/json');

    $sql = "
    SELECT 
        p.idPlan, 
        p.Nombre AS nombre_plan, 
        p.Precio, 
        GROUP_CONCAT(v.Ventajas SEPARATOR '<br>') AS ventajas
    FROM 
        plan p
    JOIN 
        ventajasdelplan vp ON p.idPlan = vp.idPlan
    JOIN 
        ventajas v ON vp.idVentajas = v.idVentajas
    GROUP BY 
        p.idPlan
    ";

    $result = $conn->query($sql);
    $planes = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $planes[] = $row;
        }
    }

    echo json_encode($planes);
}

$conn->close();
?>