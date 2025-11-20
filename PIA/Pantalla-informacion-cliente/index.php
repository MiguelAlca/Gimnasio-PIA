<?php
date_default_timezone_set('America/Monterrey');

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

$sql = "SELECT 
            u.Nombre, 
            p.IdPlan,
            p.Nombre AS TipoSuscripcion, 
            s.Fecha_fin 
        FROM Usuario u
        INNER JOIN Suscripcion s ON u.IdUsuario = s.IdUsuario
        INNER JOIN Plan p ON s.IdPlan = p.IdPlan
        WHERE u.IdUsuario = $idUsuario";

$resultado = mysqli_query($conexion, $sql);

$tipo_suscripcion = "No encontrada";
$fecha_fin = "N/A";
$id_plan = null;

if ($fila = mysqli_fetch_assoc($resultado)) {
    $tipo_suscripcion = $fila['TipoSuscripcion'];
    $fecha_fin = date("d/m/Y", strtotime($fila['Fecha_fin']));
    $id_plan = $fila['IdPlan'];
}

$ventajas = [];
if ($id_plan) {
    $sql_ventajas = "SELECT v.Ventajas
                     FROM VentajasDelPlan vp
                     JOIN Ventajas v ON vp.IdVentajas = v.idVentajas
                     WHERE vp.IdPlan = $id_plan";
    $resultado_ventajas = mysqli_query($conexion, $sql_ventajas);
    while ($fila_ven = mysqli_fetch_assoc($resultado_ventajas)) {
        $ventajas[] = $fila_ven['Ventajas'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../img y vid/person-arms-up.svg" rel="icon" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-dark text-light">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark p-3"> 
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarToggler"> 
            <a class="navbar-brand d-none d-md-block" href="../Pantalla-inicio/Inicio.html"><img class="img-fluid rounded d-none d-md-block" style="max-width: 75px;" src="../img y vid/img-logo.jpg"></a>
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
                  <li><a class="dropdown-item" href="../Pantalla-config-cliente/Index.php">Configuracion</a></li>
                  <li><a class="dropdown-item" href="../Pantalla-Historial-Compras/index.php">Historial de compras</a></li>
                </ul>
              </div>
              <a href="../Pantalla-Avisos-Cliente/index.php"><button class="btn btn-outline-warning me-5"><i class="bi bi-bell"></i></button></a>
          </div>
        </div>
    </nav>
    <hr class="border border-warning my-0"> 

    <main>
        <h1 class="text-center my-4">-Informacion de la suscripcion-</h1>
        <div class="border rounded border-2-secondary container w-50 p-3">
        <h1 id="SuscripcionTitulo" class="mb-3 text-center"><?php echo htmlspecialchars($tipo_suscripcion); ?></h1>
        <p>Beneficios de la suscripción:</p>
        <ul id="SuscripcionBeneficios">
            <?php if (!empty($ventajas)): ?>
            <?php foreach ($ventajas as $ventaja): ?>
            <li><?php echo htmlspecialchars($ventaja); ?></li>
            <?php endforeach; ?>
            <?php else: ?>
            <li>No hay beneficios disponibles.</li>
            <?php endif; ?>
            </ul>
        </div>
        <h3 id="SuscripcionExpiracion" class="text-center my-3">Tu suscripción expira en: <?php echo htmlspecialchars($fecha_fin); ?></h3>
<form action="index1.php" method="post" class="text-center my-3">
    <button type="submit" name="renovar" class="btn btn-outline-success">Renovar otro mes</button>
</form>
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