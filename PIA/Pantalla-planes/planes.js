$(document).ready(function() {
  $.ajax({
    url: "../sesion/sesion.php",
    method: "GET",
    dataType: "json",
    success: function (sesion) {
      if (sesion.loggedIn) {
        $(".btn-login").hide();
        $(".btn-register").hide();

        const dropdown = generarDropdown(sesion.IdRol);
        $(".navbar .container-fluid").append(dropdown);
      }
    },
  });


  $.ajax({
    url: "./index.php",
    method: "GET",
    dataType: "json",
    success: function(data) {
      console.log("Datos recibidos:", data);

      if (data.length === 0) {
        $("#contenedor").append("<p class='text-white'>No hay planes disponibles actualmente.</p>");
      } else {
        data.forEach(plan => {
          const ventajasHTML = plan.Ventajas.map(v => `<li>${v}</li>`).join("");

          $("#contenedor").append(`
            <div class="card" style="width: 18rem;">
              <div class="card-body">
                <h5 class="card-title border-bottom border-2 border-secondary text-center">${plan.Nombre}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Precio: $${plan.Precio}</h6>
                <ul class="card-text mt-4">${ventajasHTML}</ul>
                <a href="../Pantalla-Registro/index.php" class="nav-link">
                  <button class="btn btn-outline-success d-flex mx-auto mt-4">Seleccionar</button>
                </a>
              </div>
            </div>
          `);
        });
      }
    },
    error: function(xhr, status, error) {
      console.error("Error en la solicitud AJAX:", status, error);
    }
  });
});

function generarDropdown(idRol) {
  if (idRol === 1) {
    return `
      <div class="dropdown me-3 pe-3"> 
        <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="../Pantalla-Id-Cliente/index.html">Identificación del Cliente</a></li>  
          <li><a class="dropdown-item" href="../Pantalla-informacion-cliente/index.html">Información del cliente</a></li>
          <li><a class="dropdown-item" href="../Pantalla-config-cliente/Index.html">Configuración</a></li>
          <li><a class="dropdown-item" href="../Pantalla-Historial-Compras/index.html">Historial de compras</a></li>
        </ul>
      </div>
      <a href="../Pantalla-Avisos-Cliente/index.php"><button class="btn btn-outline-warning me-5"><i class="bi bi-bell"></i></button>
    `;
  } else if (idRol === 2) {
    return `
      <div class="dropdown me-3 pe-3"> 
        <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-gear"></i>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="../ADMIN-reportes/reportes.html">Reporte de ventas</a></li>
          <li><a class="dropdown-item" href="../ADMIN-planes/planes.html">Administrar planes</a></li>
          <li><a class="dropdown-item" href="../ADMIN-sucursales/sucursales.html">Administrar sucursales</a></li>
          <li><a class="dropdown-item" href="../ADMIN-clientes/clientes.html">Administrar clientes</a></li>
        </ul>
      </div>
      <a href="../ADMIN-avisos/avisos.html"><button class="btn btn-outline-warning me-5"><i class="bi bi-bell"></i></button>
    `;
  }

  return "";
}