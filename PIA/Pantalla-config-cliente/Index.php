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

// Manejo de actualización, cancelar, renovar aquí (igual que tu código original)
if (isset($_POST['nombre'], $_POST['correo'], $_POST['fecha_nacimiento'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    $updateUsuario = mysqli_query($conexion, "UPDATE Usuario SET Nombre = '$nombre', Correo = '$correo', Fecha_Nacimiento = '$fecha_nacimiento' WHERE IdUsuario = $idUsuario");

    if ($updateUsuario) {
        echo "<script>alert('Datos personales actualizados'); window.location.href = '';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar datos personales'); window.location.href = '';</script>";
        exit();
    }
}

if (isset($_POST['cancelar'])) {
    $cancelar = mysqli_query($conexion, "UPDATE Suscripcion SET IdEstado = 3 WHERE IdUsuario = $idUsuario");

    if ($cancelar) {
        echo "<script>alert('Suscripción cancelada'); window.location.href = '';</script>";
        exit();
    } else {
        echo "<script>alert('Error al cancelar la suscripción'); window.location.href = '';</script>";
        exit();
    }
}

if (isset($_POST['renovar'])) {
    $suscripcion = mysqli_query($conexion, "SELECT IdPlan FROM Suscripcion WHERE IdUsuario = $idUsuario");
    if ($row = mysqli_fetch_assoc($suscripcion)) {
        $idPlan = $row['IdPlan'];

        $plan = mysqli_query($conexion, "SELECT Precio FROM Plan WHERE IdPlan = $idPlan");
        $rowPlan = mysqli_fetch_assoc($plan);
        $precio = $rowPlan['Precio'];

        $fecha_inicio = date('Y-m-d');
        $fecha_fin = date('Y-m-d', strtotime('+1 month'));
        $fecha_pago = date('Y-m-d');

        $renovar = mysqli_query($conexion, "UPDATE Suscripcion SET Fecha_inicio = '$fecha_inicio', Fecha_fin = '$fecha_fin', IdEstado = 1 WHERE IdUsuario = $idUsuario");

        if ($renovar) {
            $resTarjeta = mysqli_query($conexion, "SELECT IdTarjeta FROM Usuario WHERE IdUsuario = $idUsuario");
            $rowTarjeta = mysqli_fetch_assoc($resTarjeta);
            $idTarjeta = $rowTarjeta['IdTarjeta'];

            $insertPago = mysqli_query($conexion, "INSERT INTO Pago (Fecha_pago, Monto, IdUsuario, IdPlan, IdTarjeta) VALUES ('$fecha_pago', $precio, $idUsuario, $idPlan, $idTarjeta)");

            if ($insertPago) {
                echo "<script>alert('Suscripción renovada'); window.location.href = '';</script>";
                exit();
            } else {
                echo "<script>alert('Error al registrar el pago'); window.location.href = '';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Error al renovar suscripción'); window.location.href = '';</script>";
            exit();
        }
    } else {
        echo "<script>alert('No se encontró el plan actual'); window.location.href = '';</script>";
        exit();
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Configuracion</title>
    <link href="../img y vid/person-arms-up.svg" rel="icon" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>
<body class="bg-dark">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark p-3"> 
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarToggler"> 
                <a class="navbar-brand d-none d-md-block" href="../Pantalla-inicio/Inicio.html">
                    <img class="img-fluid rounded d-none d-md-block" style="max-width: 75px;" src="../img y vid/img-logo.jpg" alt="Logo" />
                </a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0"> 
                    <li class="nav-item">
                        <a class="nav-link text-warning" aria-current="page" href="../Pantalla-inicio/Inicio.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="../Pantalla-Sucursales/sucursales.html">Sucursales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="../Pantalla-planes/planes.html">Planes</a>
                    </li>
                </ul>
                <div class="dropdown me-3 pe-3">
                    <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../Pantalla-Id-Cliente/index.php">Identificacion del Cliente</a></li>
                        <li><a class="dropdown-item" href="../Pantalla-informacion-cliente/index.php">Informacion del cliente</a></li>
                        <li><a class="dropdown-item" href="../Pantalla-Historial-Compras/index.php">Historial de compras</a></li>
                    </ul>
                </div>
                <a href="../Pantalla-Avisos-Cliente/index.php"><button class="btn btn-outline-warning me-5"><i class="bi bi-bell"></i></button></a>
            </div>
        </div>
    </nav>
    <hr class="border border-warning my-0" /> 

    <main class="text-light container my-3 pb-5">
        <h1 class="text-center">Configuracion</h1>
        <div class="row">

            <!--seccion izquierda-->
            <div class="col-6 mt-3 pe-3">
                <form action="index1.php" method="POST">
                    <p class="mb-1">Nombre</p>
                    <input name="nombre" placeholder="Cambiar nombre" type="text" class="form-control" />

                    <p class="mt-5 mb-1">Fecha de nacimiento</p>
                    <input name="fecha_nacimiento" type="date" class="form-control" />

                    <p class="mt-5 mb-1">Correo</p>
                    <input name="correo" type="text" placeholder="Cambiar correo" class="form-control" />

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-success">Actualizar datos</button>
                    </div>
                </form>
            </div>

            <!-- Sección derecha -->
            <div class="col-6 mt-5 ps-3 d-flex align-items-center">
                <div class="border p-3 mx-auto border-secondary rounded border-2 text-center">
                    <h3 id="plan-nombre"><?= htmlspecialchars($planNombre) ?></h3>
                    <h2 id="plan-precio">$<?= number_format($planPrecio, 2) ?></h2>

                    <form action="index1.php" method="POST" class="d-inline">
                        <input type="hidden" name="cancelar" value="1" />
                        <button type="submit" class="btn btn-outline-danger">Cancelar Suscripción</button>
                    </form>

                    <a class="nav-link" href="../Pantalla-informacion-cliente/index.php">
                        <button class="btn btn-outline-info">Informacion de la suscripcion</button>
                    </a>
                </div>
            </div>
        </div>
    </main>
    <div class="container">
        <hr class="border border-white border-2">
    </div>

    <footer class="bg-dark text-white container text-center my-3">
      <p>Síguenos en redes sociales:</p>
      <a href="https://www.facebook.com" target="_blank" style="color: white; margin: 0 10px; text-decoration: none;">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" width="24px">
      </a>
      <a href="https://www.instagram.com" target="_blank" style="color: white; margin: 0 10px; text-decoration: none;">
          <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Twitter" width="24px">
      </a>
      <p class="my-3">&copy; 2025 - Todos los derechos reservados</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>
