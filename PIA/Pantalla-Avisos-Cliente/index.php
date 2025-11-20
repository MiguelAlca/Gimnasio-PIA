<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avisos</title>
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
                  <li><a class="dropdown-item" href="../Pantalla-informacion-cliente/index.php">Informacion del cliente</a></li>
                  <li><a class="dropdown-item" href="../Pantalla-config-cliente/Index.php">Configuracion</a></li>
                  <li><a class="dropdown-item" href="../Pantalla-Historial-Compras/index.php">Historial de compras</a></li>
                </ul>
              </div>
              <a href="../Pantalla-Avisos-Cliente/index.php"><button class="btn btn-outline-warning me-5"><i class="bi bi-bell"></i></button></a>
          </div>
        </div>
    </nav>

    <hr class="border border-warning my-0"> 

    <div class="container border border-light text-center w-25 mt-3 mb-4 rounded-pill text-light d-flex align-items-center justify-content-center h2" style="height: 3rem;">Avisos</div>

    <div class="container h-auto p-3 rounded" id="contenedor">
    
    <?php
// Conexión a la base de datos
$conexion = mysqli_connect("127.0.0.1", "root", "2707", "mydb");
if (!$conexion) {
    echo "<p class='text-danger text-center'>Error de conexión a la base de datos.</p>";
} else {
    $query = "SELECT Mensaje, Fecha FROM Notificacion ORDER BY Fecha DESC";
    $result = mysqli_query($conexion, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $mensaje = htmlspecialchars($row['Mensaje']);
            $fecha = date("d/m/Y H:i", strtotime($row['Fecha']));
            echo "
            <div class='alert alert-warning d-flex justify-content-between align-items-center' role='alert'>
                <div>$mensaje</div>
                <small class='text-muted'>$fecha</small>
            </div>
            ";
        }
    } else {
        echo "<p class='text-light text-center'>No hay avisos disponibles.</p>";
    }
    mysqli_close($conexion);
}
?>

    </div>
    
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