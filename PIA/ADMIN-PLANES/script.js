let editingItem = null;

document.getElementById("addPlan").addEventListener("click", () => {
  const name = document.getElementById("planName").value.trim();
  const info = document.getElementById("planInfo").value.trim();
  const price = document.getElementById("planPrice").value.trim();

  if (!name || !price || !info) {
    alert("Completa todos los campos.");
    return;
  }

  if (editingItem) {
    const nombreOriginal = editingItem.dataset.originalName;

    fetch("./index.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `action=editar&nombre_original=${encodeURIComponent(nombreOriginal)}&nombre=${encodeURIComponent(name)}&info=${encodeURIComponent(info)}&precio=${encodeURIComponent(price)}`
    })
    .then(res => res.text())
    .then(respuesta => {
      alert(respuesta);

    editingItem.querySelector(".plan-name").textContent = name;
    editingItem.querySelector(".plan-price").textContent = price;
    editingItem.querySelector(".plan-info").textContent = info;
    editingItem.dataset.originalName = plan.nombre_plan;;

    resetForm()
    editingItem = null;
    document.getElementById("addPlan").textContent = "+";
    })
    .catch(err => {
      console.error("Error al editar:", err);
      alert("Error al guardar cambios.");
    });

  } else {
    fetch("./index.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `action=crear&nombre=${encodeURIComponent(name)}&info=${encodeURIComponent(info)}&precio=${encodeURIComponent(price)}`
    })
    .then(res => res.text())
    .then(respuesta => {
      alert(respuesta);

      const planList = document.getElementById("planList");
      const item = document.createElement("div");
      item.className = "plan-item";

      item.innerHTML = `
        <div class="plan-name">${name}</div>
        <div class="plan-price">${price}</div>
        <div class="plan-info">${info}</div>
        <div class="actions">
          <button class="edit-btn">✎</button>
          <button class="delete-btn">✖</button>
        </div>
      `;

      item.querySelector(".delete-btn").addEventListener("click", () => {
      if (confirm("¿Eliminar este plan?")) {
        fetch("./index.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `action=eliminar&nombre=${encodeURIComponent(name)}`
        })
        .then(res => res.text())
        .then(respuesta => {
          alert(respuesta);
          planList.removeChild(item);
          if (editingItem === item) {
            resetForm();
          }
        })
      }
    });

      item.querySelector(".edit-btn").addEventListener("click", () => {
        document.getElementById("planName").value = name;
        document.getElementById("planPrice").value = price;
        document.getElementById("planInfo").value = info;
        editingItem = item;
        document.getElementById("addPlan").textContent = "✔";
      });

      planList.appendChild(item);
      resetForm();
    })
  }
});

function resetForm() {
  document.getElementById("planName").value = "";
  document.getElementById("planInfo").value = "";
  document.getElementById("planPrice").value = "";
  if (!editingItem) {
    document.getElementById("addPlan").textContent = "+";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  fetch("./index.php")
    .then(response => {
      if (response.status === 403){      
        alert("Acceso denegado. Solo los administradores pueden ver esta página.");
        window.location.href = "../Pantalla-inicio/Inicio.html";
        return;
      }
      return response.json();
    })

    .then(planes => {
      planes.forEach(plan => {
        const item = document.createElement("div");
        item.className = "plan-item";

        item.innerHTML = `
          <div class="plan-name">${plan.nombre_plan}</div>
          <div class="plan-price">${plan.Precio}</div>
          <div class="plan-info">${plan.ventajas}</div>
          <div class="actions">
            <button class="edit-btn">✎</button>
            <button class="delete-btn">✖</button>
          </div>
        `;

        item.dataset.originalName = plan.nombre_plan;

        item.querySelector(".edit-btn").addEventListener("click", () => {
          document.getElementById("planName").value = plan.nombre_plan;
          document.getElementById("planPrice").value = plan.Precio;
          document.getElementById("planInfo").value = plan.ventajas.replace(/<br>/g, '\n');
          editingItem = item;
          document.getElementById("addPlan").textContent = "✔";
        });

        item.querySelector(".delete-btn").addEventListener("click", () => {
          if (confirm("¿Eliminar este plan?")) {
            fetch("./index.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded"
              },
              body: `action=eliminar&nombre=${encodeURIComponent(plan.nombre_plan)}`
            })
            .then(res => res.text())
            .then(respuesta => {
              alert(respuesta);
              document.getElementById("planList").removeChild(item);
              if (editingItem === item) {
                resetForm();
                editingItem = null;
              }
            });
          }
        });

        document.getElementById("planList").appendChild(item);
      });
    })
});