function frmLogin(e) {
  e.preventDefault();

  const frm = document.getElementById("frmLogin");
  const usuario = document.getElementById("usuario");
  const clave = document.getElementById("clave");

  // Validación HTML básica
  if (usuario.value.trim() === "") {
    Swal.fire({
      icon: "warning",
      title: "Campo vacío",
      text: "Por favor ingresa el usuario.",
    });
    usuario.focus();
    return;
  }

  if (clave.value.trim() === "") {
    Swal.fire({
      icon: "warning",
      title: "Campo vacío",
      text: "Por favor ingresa la contraseña.",
    });
    clave.focus();
    return;
  }
  const recordar = document.getElementById("recordar");

  if (recordar.checked) {
    localStorage.setItem("usuario_guardado", usuario.value.trim());
  } else {
    localStorage.removeItem("usuario_guardado");
  }
  const url = base_url + "Usuarios/validar";
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.send(new FormData(frm));

  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const res = JSON.parse(this.responseText);

      if (res.icono === "success") {
        window.location = base_url + "Configuracion/admin";
      } else {
        // Manejo de errores específicos
        if (res.msg === "Usuario incorrecto") {
          Swal.fire({
            icon: "error",
            title: "Usuario incorrecto",
            text: "Verifica el nombre de usuario.",
          });
          usuario.value = "";
          clave.value = "";
          usuario.focus();
        } else if (res.msg === "Contraseña incorrecta") {
          Swal.fire({
            icon: "error",
            title: "Contraseña incorrecta",
            text: "La contraseña ingresada es incorrecta.",
          });
          clave.value = "";
          clave.focus();
        } else if (res.msg === "Usuario y contraseña incorrectos") {
          Swal.fire({
            icon: "error",
            title: "Credenciales incorrectas",
            text: "Usuario y contraseña incorrectos.",
          });
          usuario.value = "";
          clave.value = "";
          usuario.focus();
        }

        // Puedes agregar más condiciones según lo que devuelva tu backend
      }
    }
  };
}
window.addEventListener("DOMContentLoaded", () => {
  const usuarioGuardado = localStorage.getItem("usuario_guardado");
  if (usuarioGuardado) {
    document.getElementById("usuario").value = usuarioGuardado;
    document.getElementById("recordar").checked = true;
  }
});

// Al recargar, eliminar errores visibles
window.addEventListener("load", () => {
  document.getElementById("usuario").classList.remove("is-invalid", "is-valid");
  document.getElementById("clave").classList.remove("is-invalid", "is-valid");
  document.getElementById("frmLogin").classList.remove("was-validated");
  document.getElementById("frmUsuario").classList.remove("was-validated");
});

function frmUsuario() {
  document.getElementById("title").textContent = "Nuevo Usuario";
  document.getElementById("claves").classList.remove("d-none");
  document.getElementById("frmUsuario").reset();
  document.getElementById("id").value = "";
  $("#nuevo_usuario").modal("show");
}
function registrarUser(e) {
  e.preventDefault();

  const frm = document.getElementById("frmUsuario");

  // Validación visual Bootstrap
  if (!frm.checkValidity()) {
    frm.classList.add("was-validated");
    return;
  }

  const url = base_url + "Usuarios/registrar";
  const formData = new FormData(frm);

  const http = new XMLHttpRequest();
  http.open("POST", url, true);

  http.onreadystatechange = function () {
    if (this.readyState === 4) {
      if (this.status === 200) {
        const res = JSON.parse(this.responseText);
        Swal.fire({
          title: res.msg,
          icon: res.icono,
          confirmButtonText: "Aceptar",
        }).then(() => {
          if (res.icono === "success") {
            frm.reset();
            frm.classList.remove("was-validated");
            $("#nuevo_usuario").modal("hide");
            if (typeof tblUsuarios !== "undefined") {
              tblUsuarios.ajax.reload();
            }
          }
        });
      } else {
        Swal.fire("Error", "Ocurrió un error al registrar el usuario", "error");
      }
    }
  };

  http.send(formData);
}

(function () {
  "use strict";
  const forms = document.querySelectorAll(".needs-validation");

  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add("was-validated");
      },
      false
    );
  });

  document.getElementById("telefono").addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "").slice(0, 9);
  });
  document.getElementById("dni").addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "").slice(0, 9);
  });
})();

function alertas(mensaje, tipo) {
  Swal.fire({
    icon: tipo, // success, warning, error, info
    title: mensaje,
    showConfirmButton: true,
    timer: 3000,
  });
}
