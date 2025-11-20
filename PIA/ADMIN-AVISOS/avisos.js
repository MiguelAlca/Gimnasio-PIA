fetch('./index.php')
  .then(response => {
    if (response.status === 403) {
      window.location.href = "../Pantalla-inicio/Inicio.html";
    }
    return response;
  })
  .then(() => {
    cargarPagina();
  });

function cargarPagina(){
  const inputAviso = document.getElementById('inputAviso');
  const btnAgregar = document.getElementById('btnAgregar');
  const listaAvisos = document.getElementById('listaAvisos');
  let avisoEditando = null;
  let idEditando = null;

  function cargarAvisos() {
    fetch('./index.php?action=cargar')
      .then(res => res.json())
      .then(data => {
        listaAvisos.innerHTML = '';
        data.forEach(aviso => mostrarAviso(aviso));
      })
  }

  function mostrarAviso(aviso) {
    const contenedor = document.createElement('div');
    contenedor.className = 'd-flex justify-content-between align-items-center bg-dark text-white p-3 rounded';

    const avisoTexto = document.createElement('span');
    avisoTexto.textContent = aviso.Mensaje;

    const acciones = document.createElement('div');
    acciones.className = 'd-flex gap-2';

    const btnEditar = document.createElement('button');
    btnEditar.className = 'btn btn-light rounded-circle';
    btnEditar.innerHTML = '<i class="bi bi-pencil"></i>';
    btnEditar.onclick = () => {
      inputAviso.value = avisoTexto.textContent;
      btnAgregar.textContent = '✔';
      avisoEditando = contenedor;
      idEditando = aviso.IdNotificacion;
    };

    const btnEliminar = document.createElement('button');
    btnEliminar.className = 'btn btn-danger rounded-circle';
    btnEliminar.innerHTML = '<i class="bi bi-x-lg"></i>';
    btnEliminar.onclick = () => {
      fetch('./index.php?action=eliminar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: aviso.IdNotificacion })
      })
      .then(res => res.text())
      .then(() => cargarAvisos());
    };

    acciones.append(btnEditar, btnEliminar);
    contenedor.append(avisoTexto, acciones);
    listaAvisos.appendChild(contenedor);
  }

  btnAgregar.addEventListener('click', () => {
    const texto = inputAviso.value.trim();
    if (!texto) return alert("Escribe un aviso antes de agregar.");

    const datos = avisoEditando
      ? { action: 'editar', id: idEditando, mensaje: texto }
      : { action: 'crear', mensaje: texto };

    fetch(`./index.php?action=${datos.action}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(datos)
    })
    .then(res => res.text())
    .then(() => {
      inputAviso.value = '';
      btnAgregar.textContent = '+';
      avisoEditando = null;
      idEditando = null;
      cargarAvisos();
    });
  });

  cargarAvisos();
}