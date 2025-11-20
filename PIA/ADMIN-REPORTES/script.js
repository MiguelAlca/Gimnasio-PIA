async function cargarDatos(anio, mes) {
  const response = await fetch(`index.php?anio=${anio}&mes=${mes}`);
  if (response.status === 403) {
    const data = await response.json();
    alert(data.error || "Acceso denegado");
    window.location.href = "../Pantalla-inicio/Inicio.html";
    return null;
  }

  const datosPlanes = await response.json();
  if (!datos[anio]) datos[anio] = {};
  datos[anio][mes] = datosPlanes;

  actualizarReporte();
  actualizarGrafico(anio);
}

const datos = {
    "2024": {
      "ENE": null,
      "FEB": null,
      "MAR": null
    },
    "2025": {
      "ENE": null,
      "FEB": null,
      "MAR": null
    }
  };
  
  const plan1El = document.getElementById('plan1');
  const plan2El = document.getElementById('plan2');
  const plan3El = document.getElementById('plan3');
  const totalEl = document.getElementById('total');
  const descriptionsEl = document.getElementById('descriptions');
  const chartEl = document.getElementById('chart');
  const anioSelect = document.getElementById('anio');
  const mesSelect = document.getElementById('mes');
  
  function actualizarReporte() {
    const anio = anioSelect.value;
    const mes = mesSelect.value;
  
    const data = datos[anio]?.[mes];
  
    if (!data || data === null) {
      plan1El.innerHTML = '--<br><span>Plan 1</span>';
      plan2El.innerHTML = '--<br><span>Plan 2</span>';
      plan3El.innerHTML = '--<br><span>Plan 3</span>';
      totalEl.innerHTML = '--<br><span>Total</span>';
      descriptionsEl.innerHTML = '<p>Los datos no están disponibles para este mes.</p>';
      return;
    }
  
    const total = data.plan1 + data.plan2 + data.plan3;
  
    plan1El.innerHTML = `${data.plan1}<br><span>Plan 1</span>`;
    plan2El.innerHTML = `${data.plan2}<br><span>Plan 2</span>`;
    plan3El.innerHTML = `${data.plan3}<br><span>Plan 3</span>`;
    totalEl.innerHTML = `${total}<br><span>Total</span>`;
  
    const pct1 = Math.round((data.plan1 / total) * 100);
    const pct2 = Math.round((data.plan2 / total) * 100);
    const pct3 = Math.round((data.plan3 / total) * 100);
  
    descriptionsEl.innerHTML = `
      <p>Se suscribieron ${data.plan1} usuarios nuevos en este mes en el Plan 1</p>
      <p>Se suscribieron ${data.plan2} usuarios nuevos en este mes en el Plan 2</p>
      <p>Se suscribieron ${data.plan3} usuarios nuevos en este mes en el Plan 3</p>
      <p>El plan 1 contó con un ${pct1}% de los usuarios este mes</p>
      <p>El plan 2 contó con un ${pct2}% de los usuarios este mes</p>
      <p>El plan 3 contó con un ${pct3}% de los usuarios este mes</p>
    `;
  }
  
  function actualizarGrafico(anio) {
  chartEl.innerHTML = '';

  const todosLosMeses = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];
  const mesActual = mesSelect.value;
  const indiceActual = todosLosMeses.indexOf(mesActual);

  const indices = [indiceActual - 2, indiceActual - 1, indiceActual].filter(i => i >= 0);

  const datosMeses = indices.map(i => {
    const mesNombre = todosLosMeses[i];
    const data = datos[anio]?.[mesNombre];
    const total = data ? data.plan1 + data.plan2 + data.plan3 : 0;
    return { mesNombre, total };
  });

  const maxTotal = Math.max(...datosMeses.map(m => m.total), 1);

  datosMeses.forEach(({ mesNombre, total }) => {
    const altura = (total / maxTotal) * 110;

    const bar = document.createElement('div');
    bar.className = 'bar';
    bar.style.height = `${altura}px`;
    bar.innerText = mesNombre;

    chartEl.appendChild(bar);
  });
  }
  
  anioSelect.addEventListener('change', () => {
    cargarDatos(anioSelect.value, mesSelect.value);
    actualizarGrafico(anioSelect.value);
  });

  mesSelect.addEventListener('change', () =>{
    cargarDatos(anioSelect.value, mesSelect.value);
    actualizarGrafico(anioSelect.value);
  })
  
  window.addEventListener('load', () => {
    cargarDatos(anioSelect.value, mesSelect.value);
    actualizarGrafico(anioSelect.value);
  });
  