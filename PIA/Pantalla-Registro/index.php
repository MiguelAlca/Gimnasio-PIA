<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate</title>
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
              <li class="nav-item">
                <a class="nav-link text-warning" href="../Pantalla-Sucursales/sucursales.html">Sucursales</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-warning" href="../Pantalla-planes/planes.html">Planes</a>
              </li>
            </ul>
      
            <button class="btn btn-outline-warning me-3">Registrarse</button>
            <a href="../Pantalla-Iniciar-Sesion/index.php"><button class="btn btn-warning">Iniciar sesion</button></a>
          </div>
        </div>
    </nav>

    <hr class="border border-white border-2 my-0">

    <main class="container text-light px-5">
        <div class="row">
            <div class="col-7">
                <h2 class="text-center my-4">¡Ingresa tus datos para la inscripcion!</h2>
                <img src="../img y vid/hombre-gim.jpg" class="img-fluid">
            </div>

            <div class="col-5">
              <br>
              <h3 class="mt-1 text-center">¿Ya tienes una cuenta?, <a href="../Pantalla-Iniciar-Sesion/index.html">Inicia sesion</a></h3>
              <form action="index1.php" method="post">
              <div class="mb-3"> <!--Nombre-->
                <label for="exampleFormControlInput1" class="form-label mt-5">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="exampleFormControlInput1" placeholder="Ingresa tu nombre">
              </div>

              <div class="mb-3"><!--Fecha de nacimiento-->
                <label for="exampleFormControlInput1" class="form-label">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control" id="exampleFormControlInput1" placeholder="dd/mm/yyyy">
              </div>

              <div class="mb-3"><!--Correo-->
                <label for="exampleFormControlInput1" class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control" id="exampleFormControlInput1" placeholder="nombre@ejemplo.com">
              </div>

              <div><!--Contraseña-->
                <label for="inputPassword5" class="form-label">Contraseña</label>
                <input type="password" name="contra" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock">
                <div id="passwordHelpBlock" class="form-text text-secondary mb-3" >
                  Colocar aqui los requerimientos minimos y maximos para la contraseña
                </div>
              </div>

              <div class="mb-3"><!--Numero de tarjeta-->
                <label for="exampleFormControlInput1" class="form-label">Numero de Tarjeta</label>
                <input type="text" name="numero_tarjeta" class="form-control" id="exampleFormControlInput1" placeholder="16 digitos">
              </div>

              <div class="mb-3"><!--CVV-->
                <label for="exampleFormControlInput1" class="form-label">CVV</label>
                <input type="number" name="cvv" class="form-control" id="exampleFormControlInput1" placeholder="3 digitos">
              </div>

              <div class="mb-3"><!--Fecha de expiracion-->
                <label for="exampleFormControlInput1" class="form-label">Fecha de expiracion</label>
                <input type="date" name="fecha_venc" class="form-control" id="exampleFormControlInput1" placeholder="nombre@ejemplo.com">
              </div>

              <select aria-label="Default select example" id="planes" class="form-select mt-5" name="id_plan" required><!--Planes-->
                <option selected disabled>Selecciona un plan</option>
                <?php
                $conexion = mysqli_connect("127.0.0.1", "root", "2707", "mydb");
                $planes = mysqli_query($conexion, "SELECT IdPlan, Nombre FROM Plan");
                while ($plan = mysqli_fetch_assoc($planes)) {
                  echo "<option value='{$plan['IdPlan']}'>{$plan['Nombre']}</option>";
                }
                mysqli_close($conexion);
                ?>
              </select>
              <br>
                <input type="submit" value="Registrarme" class="btn btn-success px-5 d-flex mx-auto">
              </form>
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