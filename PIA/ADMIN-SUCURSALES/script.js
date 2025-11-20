let editingBranch = null;
let editingBranchId = null;

document.getElementById("addBranch").addEventListener("click", () => {
  const name = document.getElementById("branchName").value.trim();
  const address = document.getElementById("branchAddress").value.trim();

  if (!name || !address) {
    alert("Completa todos los campos.");
    return;
  }

  if (editingBranch) {
    fetch('./index.php', {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      id: editingBranchId,
      nombre: name,
      direccion: address,
    })
  }).then(response => response.json()).then(data => {
    if (data.success) {
      editingBranch.querySelector(".branch-name").textContent = name;
      editingBranch.querySelector(".branch-address").textContent = address;
    }
  });

    editingBranch = null;
    editingBranchId = null; 
    document.getElementById("addBranch").textContent = "+";
    return;

  } else {

    fetch('./index.php', {
    method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        nombre: name,
        direccion: address,
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success){
        const branchList = document.getElementById("branchList");

        const item = document.createElement("div");
        item.className = "branch-item";
        item.dataset.id = data.id;

        item.innerHTML = `
          <div class="branch-name">${name}</div>
          <div class="branch-address">${address}</div>
          <div class="actions">
            <button class="edit-btn">✎</button>
            <button class="delete-btn">✖</button>
          </div>
        `;

        item.querySelector(".delete-btn").addEventListener("click", () => {
          fetch('./index.php', {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: item.dataset.id })
          }).then(response => response.json()).then(data => {
            if (data.success) {
              branchList.removeChild(item);
              if (editingBranch === item) {
                resetForm();
              }
            }
          });
        });

        item.querySelector(".edit-btn").addEventListener("click", () => {
          document.getElementById("branchName").value = item.querySelector(".branch-name").textContent;
          document.getElementById("branchAddress").value = item.querySelector(".branch-address").textContent;
          editingBranch = item;
          editingBranchId = item.dataset.id;
          document.getElementById("addBranch").textContent = "✔";
        });

        branchList.appendChild(item);
        resetForm();
      }
    });  
  }
});

window.addEventListener("DOMContentLoaded", () => {
  fetch('./index.php')
    .then(response => {
      if (response.status === 403) {
        window.location.href = "../Pantalla-inicio/Inicio.html";
        };
      return response.json();
    })
    .then(data => {
      const branchList = document.getElementById("branchList");

      data.forEach(sucursal => {
        const item = document.createElement("div");
        item.className = "branch-item";
        item.dataset.id = sucursal.IdSucursal;

        item.innerHTML = `
          <div class="branch-name">${sucursal.Nombre}</div>
          <div class="branch-address">${sucursal.Direccion}</div>
          <div class="actions">
            <button class="edit-btn">✎</button>
            <button class="delete-btn">✖</button>
          </div>
        `;

        item.querySelector(".delete-btn").addEventListener("click", () => {
          fetch('./index.php', {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: item.dataset.id })
          }).then(response => response.json()).then(data => {
            if (data.success) {
              branchList.removeChild(item);
              if (editingBranch === item) {
                resetForm();
              }
            }
          });
        });

        item.querySelector(".edit-btn").addEventListener("click", () => {
          document.getElementById("branchName").value = item.querySelector(".branch-name").textContent;
          document.getElementById("branchAddress").value = item.querySelector(".branch-address").textContent;
          editingBranch = item;
          editingBranchId = item.dataset.id;
          document.getElementById("addBranch").textContent = "✔";
        });

        branchList.appendChild(item);
      });
    })
});

function resetForm() {
  document.getElementById("branchName").value = "";
  document.getElementById("branchAddress").value = "";
  editingBranch = null;
  editingBranchId = null;
  document.getElementById("addBranch").textContent = "+";
}
