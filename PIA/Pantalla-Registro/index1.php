<?php 

date_default_timezone_set('America/Monterrey');


$conexion = mysqli_connect("127.0.0.1", "root", "2707", "mydb");

if (!$conexion) {
    echo "<script>
        alert('No se pudo conectar a la base de datos');
        window.location.href = 'index.html';
    </script>";
    exit();
}
$nombre = $_POST['nombre'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$correo = $_POST['correo'];
$contra = $_POST['contra'];

$numero_tarjeta = $_POST['numero_tarjeta'];
$cvv = $_POST['cvv'];
$fecha_venc = $_POST['fecha_venc'];

$id_plan = $_POST['id_plan'];

$result_plan = mysqli_query($conexion, "SELECT Precio FROM Plan WHERE IdPlan = $id_plan");
if ($row_plan = mysqli_fetch_assoc($result_plan)) {
    $precio_plan = $row_plan['Precio'];
} else {
    echo "<script>
        alert('Plan no válido');
        window.location.href = 'index.html';
    </script>";
    exit();
}

$insertTarjeta = mysqli_query($conexion, "INSERT INTO Tarjeta (Numero_Tarjeta, CVV, Fecha_Venc) 
VALUES ('$numero_tarjeta', '$cvv', '$fecha_venc')");

if ($insertTarjeta) {
    $id_tarjeta = mysqli_insert_id($conexion);

    $insertUsuario = mysqli_query($conexion, "INSERT INTO Usuario (Nombre, Fecha_Nacimiento, Correo, Contra, IdTarjeta, IdRol) 
    VALUES ('$nombre', '$fecha_nacimiento', '$correo', '$contra', $id_tarjeta, 1)");

    if ($insertUsuario) {
        $id_usuario = mysqli_insert_id($conexion);

        $fecha_inicio = date('Y-m-d');
        $fecha_fin = date('Y-m-d', strtotime('+1 month'));

        $insertSuscripcion = mysqli_query($conexion, "INSERT INTO Suscripcion (Fecha_inicio, Fecha_fin, IdUsuario, IdPlan, IdEstado) 
        VALUES ('$fecha_inicio', '$fecha_fin', $id_usuario, $id_plan, 1)");

        if ($insertSuscripcion) {
            $fecha_pago = date('Y-m-d');
            $insertPago = mysqli_query($conexion, "INSERT INTO Pago (Fecha_pago, Monto, IdUsuario, IdPlan, IdTarjeta) 
            VALUES ('$fecha_pago', $precio_plan, $id_usuario, $id_plan, $id_tarjeta)");

            if ($insertPago) {
                echo "<script>
                    alert('Registro exitoso');
                    window.location.href = '/PAGINA-GIMNASIO/Pantalla-Iniciar-Sesion/index.html';
                </script>";
            } else {
                echo "<script>
                    alert('Error al registrar el pago');
                    window.location.href = 'index.html';
                </script>";
            }

        } else {
            echo "<script>
                alert('Error al registrar la suscripción');
                window.location.href = 'index.html';
            </script>";
        }

    } else {
        echo "<script>
            alert('Error al registrar el usuario');
            window.location.href = 'index.html';
        </script>";
    }

} else {
    echo "<script>
        alert('Error al registrar la tarjeta');
        window.location.href = 'index.html';
    </script>";
}

mysqli_close($conexion);

?>
