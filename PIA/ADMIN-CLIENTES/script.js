const usuarios = [
    { Nombre: "---", Fecha_fin: "---", IdUsuario: "---" },
    { Nombre: "---", Fecha_fin: "---", IdUsuario: "---" },
    { Nombre: "---", Fecha_fin: "---", IdUsuario: "---" },
    { Nombre: "---", Fecha_fin: "---", IdUsuario: "---" },
    { Nombre: "---", Fecha_fin: "---", IdUsuario: "---" },
    { Nombre: "---", Fecha_fin: "---", IdUsuario: "---" }
  ];
  
  function mostrarUsuarios(lista) {
    const contenedor = document.getElementById('listaUsuarios');
    contenedor.innerHTML = "";
  
    lista.forEach(usuario => {
      const div = document.createElement('div');
      div.className = 'usuario';
      div.style.cursor = "pointer";
      div.addEventListener('click', () =>{
        window.location.href = `../ADMIN-adcliente/adcliente.html?id=${usuario.IdUsuario}`;
      });

      div.innerHTML = `
      <span><strong>Nombre:</strong> ${usuario.Nombre}</span><br>
      <span><strong>Fecha:</strong> ${usuario.Fecha_fin}</span><br>
      <span><strong>ID:</strong> ${usuario.IdUsuario}</span>
    `;
  
      contenedor.appendChild(div);
    });
  }
  
  function buscarUsuario() {
  const filtro = document.getElementById('searchInput').value.trim();
  
  fetch(`./index.php?q=${encodeURIComponent(filtro)}`)
    .then(response => response.json())
    .then(data => mostrarUsuarios(data));
  }

  window.onload = () => mostrarUsuarios(usuarios);
  