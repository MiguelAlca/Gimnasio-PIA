<?php
session_start();

if (!isset($_SESSION['IdUsuario'])) {
    header("Location: /PAGINA-GIMNASIO/Iniciar-Sesion/index.html");
    exit();
}

$idUsuario = $_SESSION['IdUsuario'];
$conexion = mysqli_connect("127.0.0.1", "root", "2707", "mydb");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener datos del usuario para mostrar en formulario
$usuario = mysqli_query($conexion, "SELECT Nombre, Correo, Fecha_Nacimiento, IdTarjeta FROM Usuario WHERE IdUsuario = $idUsuario");
$rowUsuario = mysqli_fetch_assoc($usuario);

// Obtener plan y precio para mostrar
$planNombre = "Sin plan activo";
$planPrecio = 0;

$query = "
    SELECT p.Nombre, p.Precio 
    FROM Suscripcion s 
    JOIN Plan p ON s.IdPlan = p.IdPlan 
    WHERE s.IdUsuario = $idUsuario AND s.IdEstado = 1
    LIMIT 1";
$result = mysqli_query($conexion, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $planNombre = $row['Nombre'];
    $planPrecio = $row['Precio'];
}

// Actualizar datos personales
if (isset($_POST['nombre'], $_POST['correo'], $_POST['fecha_nacimiento'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);

    $updateUsuario = mysqli_query($conexion, "UPDATE Usuario SET Nombre = '$nombre', Correo = '$correo', Fecha_Nacimiento = '$fecha_nacimiento' WHERE IdUsuario = $idUsuario");

    if ($updateUsuario) {
        echo "<script>alert('Datos personales actualizados'); window.location.href = 'index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar datos personales'); window.location.href = 'index.php';</script>";
        exit();
    }
}

// Cancelar suscripción
if (isset($_POST['cancelar'])) {
    $cancelar = mysqli_query($conexion, "UPDATE Suscripcion SET IdEstado = 3 WHERE IdUsuario = $idUsuario");

    if ($cancelar) {
        echo "<script>alert('Suscripción cancelada'); window.location.href = '../Pantalla-inicio/inicio.html';</script>";
        exit();
    } else {
        echo "<script>alert('Error al cancelar la suscripción'); window.location.href = '../Pantalla-inicio/inicio.html';</script>";
        exit();
    }
}


mysqli_close($conexion);
?>
