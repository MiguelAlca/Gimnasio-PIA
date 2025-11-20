$(document).ready(function () {
  $.ajax({
    url: "../sesion/sesion.php",
    method: "GET",
    dataType: "json",
    success: function (sesion) {
      if (sesion.loggedIn) {
        $("a[href='../Pantalla-Registro/index.html']").remove();
        $("a[href='../Pantalla-Iniciar-Sesion/index.html']").remove();

        const dropdown = generarDropdown(sesion.IdRol);
        $(".navbar .collapse").append(dropdown);
      }
    },
  });

  const imagenes = [
    "gim-cardio.jpg",
    "gim-deportes.jpg",
    "gimnasio pesas.jpg"
  ];

  
  $.getJSON("./index.php", function (data) {
    data.forEach((sucursal, index) => {
      const id = sucursal.IdSucursal;
      const nombre = sucursal.Nombre;
      const Direccion = sucursal.Direccion;

      const imagenNombre = imagenes[index % imagenes.length];
      const imagen = `../img y vid/${imagenNombre}`;

      $("#sucursales").append(`<div class="card mt-3" style="width: 18rem;">
        <img src=" ${imagen}" style="height: 200px;" class="card-img-top img-fluid" alt="Sucursal ${id}">
        <div class="card-body">
          <h5 class="card-title"> ${nombre} </h5>
          <p class="card-text"> ${Direccion} </p>
          <a href=" https://www.google.com/maps/search/${encodeURIComponent(Direccion)}" class="btn btn-primary">Direccion en el mapa</a>
        </div>
      </div>`
      );
    });
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