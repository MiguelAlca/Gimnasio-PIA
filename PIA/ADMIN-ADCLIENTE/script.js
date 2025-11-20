function obtenerIdUsuario() {
  const params = new URLSearchParams(window.location.search);
  return params.get("id");
}

function cancelarSuscripcion() {
  const idUsuario = obtenerIdUsuario();
  fetch("./index.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ IdUsuario: parseInt(idUsuario) })
  })
    .then(response => response.json())
    .then(data => {
      if (data.ok) {
        document.getElementById('tipo-suscripcion').textContent = 'Suscripción cancelada';
        alert("La suscripción fue cancelada correctamente.");
      }
    });
}

window.onload = function () {
  const idUsuario = obtenerIdUsuario();

  fetch("./index.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ IdUsuario: parseInt(idUsuario) })
  })
    .then(response => response.json())
    .then(data => {
      
      const lista = document.getElementById('lista-pagos');

      if (data.ok) {
        const userInfo = document.querySelector('.usuario-info');
        const tipoSuscripcion = document.getElementById('tipo-suscripcion');

        userInfo.textContent = `${data.usuario} (ID: ${idUsuario})`;
        tipoSuscripcion.textContent = `Plan actual: ${data.plan}`;
      }

      if (data.pagos && data.pagos.length > 0) {
        lista.innerHTML = '';
        data.pagos.forEach(pago => {
          const div = document.createElement('div');
          div.className = 'item-pago';
          div.textContent = pago;
          lista.appendChild(div);
        });
      } else {
          lista.innerHTML = '<div>No hay pagos registrados.</div>';
      }
  });
};