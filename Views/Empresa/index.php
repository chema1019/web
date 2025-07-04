<?php include "Views/Templates/header.php"; ?>
<style>
    .btn-primary {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        font-size: 26px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        background-color: #28a745 !important;
        /* verde Bootstrap */
        border: none;
    }

    .btn-outline-secondary {
        border-radius: 8px;
    }


    .card-footer {
        padding-right: 1.5rem;
    }
</style>
<div class="modal-content">
  <div class="modal-header bg-primary text-white justify-content-between">
    <h5 class="modal-title" id="title">Registro de empresa</h5>
  </div>

  <form id="frmEmpresa" class="needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate onsubmit="registrarEmpresa(event)">
    <div class="modal-body">
      <input type="hidden" name="id" id="id">

      <div class="card">
        <div class="card-body">
          <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="razon_social">Razón Social</label>
                <input id="razon_social" type="text" class="form-control" name="razon_social" placeholder="Razón social" minlength="3" required autofocus>
                <div class="invalid-feedback">La razón social debe tener al menos 3 caracteres.</div>
              </div>

              <div class="form-group">
                <label for="direccion">Dirección</label>
                <input id="direccion" type="text" class="form-control" name="direccion" placeholder="Dirección" minlength="5" required>
                <div class="invalid-feedback">La dirección debe tener al menos 5 caracteres.</div>
              </div>

              <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input id="telefono" type="text" class="form-control" name="telefono" placeholder="Teléfono" pattern="^\d{9}$" required oninput="validarTelefono()">
                <div class="invalid-feedback">El teléfono debe tener exactamente 9 dígitos numéricos.</div>
              </div>

              <div class="form-group">
                <label for="autorizacion">Autorización</label>
                <input id="autorizacion" type="text" class="form-control" name="autorizacion" placeholder="Autorización" required>
                <div class="invalid-feedback">La autorización debe tener al menos 5 dígitos numéricos.</div>
              </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="ruc">RUC</label>
                <input id="ruc" type="text" class="form-control" name="ruc" placeholder="RUC" pattern="^\d{11}$" required oninput="validarRuc()">
                <div class="invalid-feedback">El RUC debe tener exactamente 11 dígitos numéricos.</div>
              </div>

              <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" oninput="formatCorreo()" placeholder="Correo electrónico"
                  required pattern="^[a-zA-Z0-9._%+-]+@(gmail|hotmail)\.com$"
                  title="Solo se permite correos @gmail.com o @hotmail.com">
                <div class="invalid-feedback">Solo se permite correos @gmail.com o @hotmail.com</div>
              </div>

              <div class="form-group">
                <label for="fecha_vigencia">Fecha de Vigencia</label>
                <input id="fecha_vigencia" type="date" class="form-control" name="fecha_vigencia" required>
                <div class="invalid-feedback">Seleccione una fecha válida.</div>
              </div>

              <div class="form-group">
                <label for="documento">Documento</label>
                <input id="documento" type="file" class="form-control" name="documento" accept=".pdf,.jpg,.jpeg,.png" required>
                <div class="invalid-feedback">Adjunte un documento válido (PDF o imagen).</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Botón -->
    <div class="modal-footer">
      <button type="submit" id="btnAccion" class="btn btn-primary">
        Guardar
      </button>
    </div>
  </form>
</div>


<!-- JavaScript para validación y restricciones -->
<script>
    function frmEmpresa() {
        document.getElementById("btnAccion").textContent = "";
        document.getElementById("frmEmpresa").reset();
        document.getElementById("id").value = "";
    }

    let enviando = false; // VARIABLE GLOBAL

    function registrarEmpresa(e) {
        e.preventDefault();

        if (enviando) return;
        enviando = true;

        const btnAccion = document.getElementById("btnAccion");
        const frm = document.getElementById("frmEmpresa");

        if (!frm.checkValidity()) {
            frm.classList.add("was-validated");
            enviando = false;
            return;
        }

        const documento = document.getElementById("documento");
        const file = documento.files[0];

        if (
            file &&
            !["application/pdf", "image/jpeg", "image/png"].includes(file.type)
        ) {
            Swal.fire(
                "Advertencia",
                "Solo se permiten archivos PDF o imágenes (JPG, PNG)",
                "warning"
            );
            enviando = false;
            return;
        }

        btnAccion.disabled = true;
        btnAccion.textContent = "Procesando...";

        const url = base_url + "Empresa/registrar";
        const formData = new FormData(frm);
        const http = new XMLHttpRequest();
        http.open("POST", url, true);

        http.onreadystatechange = function() {
            if (this.readyState === 4) {
                btnAccion.disabled = false;
                btnAccion.innerHTML = '<i class="fa fa-check" style="font-size: 26px; color: white; display: block; margin: 0; padding: 0; line-height: 1; text-align: center;"></i>';
                enviando = false;

                if (this.status === 200) {
                    const res = JSON.parse(this.responseText);

                    Swal.fire({
                        icon: res.status ? "success" : "error",
                        title: res.status ? "¡Éxito!" : "Error",
                        html: `<strong>${res.msg}</strong>`,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        background: "#f4f6f9",
                        color: "#333",
                        iconColor: res.status ? "#28a745" : "#dc3545",
                        customClass: {
                            popup: "swal-custom-popup",
                            title: "swal-custom-title",
                            htmlContainer: "swal-custom-html",
                        },
                    });

                    // ✅ Aquí se limpia el formulario si fue exitoso
                    if (res.status) {
                        setTimeout(() => {
                            frm.reset();
                            frm.classList.remove("was-validated");

                            frm.querySelectorAll(".is-valid, .is-invalid").forEach((el) => {
                                el.classList.remove("is-valid", "is-invalid");
                            });

                            // Si tienes un modal o tabla, puedes ocultar o recargar aquí:
                            // $('#modalEmpresa').modal('hide');
                            if (typeof tablaEmpresas !== "undefined") {
                                tablaEmpresas.ajax.reload();
                            }
                        }, 2000);
                    }
                } else {
                    Swal.fire("Error", "Ocurrió un error al registrar la empresa", "error");
                }
            }
        };

        http.send(formData);
    }


    function validarTelefono() {
        const input = document.getElementById("telefono");
        const regex = /^\d{9}$/;
        const valor = input.value.trim();

        input.classList.remove("is-valid", "is-invalid");
        input.setCustomValidity("");

        if (valor === "") return;

        if (regex.test(valor)) {
            input.classList.add("is-valid");
        } else {
            input.classList.add("is-invalid");
            input.setCustomValidity("El teléfono debe tener exactamente 9 dígitos numéricos.");
        }
    }

    function validarRuc() {
        const input = document.getElementById("ruc");
        const regex = /^\d{11}$/;
        const valor = input.value.trim();

        input.classList.remove("is-valid", "is-invalid");
        input.setCustomValidity("");

        if (valor === "") return;

        if (regex.test(valor)) {
            input.classList.add("is-valid");
        } else {
            input.classList.add("is-invalid");
            input.setCustomValidity("El RUC debe tener exactamente 11 dígitos numéricos.");
        }
    }

    function formatCorreo() {
        const correoInput = document.getElementById("correo");
        const valor = correoInput.value.trim();
        const regex = /^[a-zA-Z0-9._%+-]+@(gmail|hotmail)\.com$/;

        correoInput.classList.remove("is-valid", "is-invalid");
        correoInput.setCustomValidity(""); // Resetear cualquier error previo

        if (valor === "") {
            return;
        }

        if (regex.test(valor)) {
            correoInput.classList.add("is-valid");
            correoInput.setCustomValidity(""); // válido
        } else {
            correoInput.classList.add("is-invalid");
            correoInput.setCustomValidity("Solo se permite correos @gmail.com o @hotmail.com"); // inválido
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const mayusculas = ["direccion", "razon_social", "autorizacion"];
        mayusculas.forEach(id => {
            const input = document.getElementById(id);
            input.addEventListener("input", function() {
                this.value = this.value.toUpperCase();
            });
        });
    });
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');

        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Validación en tiempo real para restringir caracteres
        document.getElementById('telefono').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);
        });

        document.getElementById('ruc').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        });


    })();
</script>

<?php include "Views/Templates/footer.php"; ?>