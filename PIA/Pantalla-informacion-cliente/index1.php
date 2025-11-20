<?php

date_default_timezone_set('America/Monterrey');


session_start();

if (!isset($_SESSION['IdUsuario'])) {
    header("Location: /PAGINA-GIMNASIO/Iniciar-Sesion/index.html");
    exit();
}

$id_usuario = $_SESSION['IdUsuario'];

$conexion = mysqli_connect("127.0.0.1", "root", "2707", "mydb");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$user_query = "
    SELECT u.IdUsuario, u.IdTarjeta, s.IdPlan
    FROM Usuario u
    INNER JOIN Suscripcion s ON u.IdUsuario = s.IdUsuario
    WHERE u.IdUsuario = $id_usuario AND s.IdEstado = 1
    ORDER BY s.Fecha_fin DESC
    LIMIT 1";
$user_result = mysqli_query($conexion, $user_query);


if ($user_row = mysqli_fetch_assoc($user_result)) {
    $id_usuario = $user_row['IdUsuario'];
    $id_plan = $user_row['IdPlan'];
    $id_tarjeta = $user_row['IdTarjeta'];

    if (!$id_tarjeta) {
        die("No se encontró tarjeta registrada para realizar el pago.");
    }


    $plan_query = "SELECT Precio FROM Plan WHERE IdPlan = $id_plan LIMIT 1";
    $plan_result = mysqli_query($conexion, $plan_query);
    if ($plan_row = mysqli_fetch_assoc($plan_result)) {
        $monto_renovacion = $plan_row['Precio'];
    } else {
        die("No se pudo obtener el precio del plan.");
    }

    $sql = "SELECT Fecha_fin FROM Suscripcion WHERE IdUsuario = $id_usuario AND IdPlan = $id_plan AND IdEstado = 1 ORDER BY Fecha_fin DESC LIMIT 1";
    $result = mysqli_query($conexion, $sql);

     if ($row = mysqli_fetch_assoc($result)) {
        $fecha_fin_actual = $row['Fecha_fin'];
        $nueva_fecha_fin = date('Y-m-d', strtotime($fecha_fin_actual . ' +1 month'));

        // Actualizar la suscripción
        $update = mysqli_query($conexion, "
            UPDATE Suscripcion
            SET Fecha_fin = '$nueva_fecha_fin'
            WHERE IdUsuario = $id_usuario AND IdPlan = $id_plan AND Fecha_fin = '$fecha_fin_actual'
        ");

        if ($update) {
            // Registrar el pago
            $fecha_pago = date('Y-m-d');

            $insert_pago = mysqli_query($conexion, "
                INSERT INTO Pago (IdUsuario, IdPlan, Fecha_pago, Monto, IdTarjeta)
                VALUES ($id_usuario, $id_plan, '$fecha_pago', $monto_renovacion, $id_tarjeta)
            ");

            if ($insert_pago) {
                echo "<script>
                    alert('Suscripción renovada hasta: $nueva_fecha_fin y pago registrado correctamente.');
                    window.location.href = 'index.php';
                </script>";
            } else {
                echo "<script>
                    alert('Suscripción renovada, pero error al registrar el pago.');
                    window.location.href = 'index.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('Error al renovar la suscripción.');
                window.location.href = 'index.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('No se encontró suscripción activa a renovar.');
            window.location.href = '/PAGINA-GIMNASIO/Pantalla-Iniciar-Sesion/index.html';
        </script>";
    }
} else {
    echo "<script>
        alert('No existe esta sesión.');
        window.location.href = '/PAGINA-GIMNASIO/Pantalla-Iniciar-Sesion/index.html';
    </script>";
}

mysqli_close($conexion);
?>