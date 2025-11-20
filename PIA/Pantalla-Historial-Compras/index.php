<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <link href="../img y vid/person-arms-up.svg" rel="icon" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-dark">
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
                  <li><a class="dropdown-item" href="../Pantalla-informacion-cliente/index.html">Informacion del cliente</a></li>
                  <li><a class="dropdown-item" href="../Pantalla-config-cliente/Index.php">Configuracion</a></li>
                </ul>
              </div>
              <a href="../Pantalla-Avisos-Cliente/index.php"><button class="btn btn-outline-warning me-5"><i class="bi bi-bell"></i></button></a>
          </div>
        </div>
    </nav>
    <hr class="border border-warning my-0"> 

    <main>
      <h1 class="text-center text-light my-3">Historial de pagos</h1>
      <div class="container text-light text-center border border-secondary rounded">
        <div class="row py-2">
          <div class="col-4" id="fecha-pago"><h2>Fecha de emision</h2></div>
          <div class="col-4" id="info-pago"><h2>Descripcion</h2></div>
          <div class="col-4" id="precio-pago"><h2>Monto</h2></div>
        </div>
      </div>
       <?php
session_start();

if (!isset($_SESSION['IdUsuario'])) {
    header("Location: /PAGINA-GIMNASIO/Iniciar-Sesion/index.html");
    exit();
}

$conexion = mysqli_connect("127.0.0.1", "root", "2707", "mydb");
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$id_usuario = $_SESSION['IdUsuario'];

$query_pagos = "SELECT p.Fecha_pago, pl.Nombre AS Descripcion, p.Monto
                FROM Pago p
                INNER JOIN Plan pl ON p.IdPlan = pl.IdPlan
                WHERE p.IdUsuario = $id_usuario
                ORDER BY p.Fecha_pago DESC";

$result_pagos = mysqli_query($conexion, $query_pagos);

if (mysqli_num_rows($result_pagos) > 0) {
    while ($pago = mysqli_fetch_assoc($result_pagos)) {
        echo "
        <div class='container text-light text-center border border-secondary border-top-0'>
            <div class='row py-2'>
                <div class='col-4'>{$pago['Fecha_pago']}</div>
                <div class='col-4'>{$pago['Descripcion']}</div>
                <div class='col-4'>\$ {$pago['Monto']}</div>
            </div>
        </div>";
    }
} else {
    echo "<p class='text-center text-light'>No hay pagos registrados.</p>";
}

mysqli_close($conexion);
?>

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