<?php include "Views/Templates/header.php"; ?>
<style>
    .btn-primarys {
        position: fixed;
        /* Esto hace que esté fijo */
        bottom: 30px;
        right: 30px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        font-size: 26px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        background: linear-gradient(90deg, #0d6efd, #0a58ca);
        border: none;
        color: white;
    }

    .btn-primarys:hover {
        transform: scale(1.08);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
    }



    .btn-outline-secondary {
        border-radius: 8px;
    }
</style>
<div class="card shadow mb-4">
  <div class="card-header bg-primary text-white">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h4 class="mb-0">Usuarios</h4>
      </div>
      <div class="col-md-6 text-right">
        <button class="btn btn-light btn-sm" onclick="frmUsuario();">
          Nuevo
        </button>
      </div>
    </div>
  </div>
  
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-bordered" id="tblUsuarios">
        <thead class="thead-light text-center">
          <tr>
            <th>Id</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>



<div class="modal fade" id="nuevo_usuario" tabindex="-1" role="dialog" aria-labelledby="nuevoUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content shadow rounded border-0">
      <div class="modal-header bg-primary text-white rounded-top">
        <h5 class="modal-title font-weight-bold" id="title">Nuevo Usuario</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="frmUsuario" method="post" class="needs-validation" novalidate autocomplete="off" enctype="multipart/form-data">
        <div class="modal-body p-3">
          <input type="hidden" id="id" name="id">
          <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">
              <div class="card border-light shadow-sm p-3">
                <h6 class="text-dark mb-3">Información Personal</h6>

                <div class="form-group">
                  <label for="dni" class="font-weight-bold">DNI</label>
                  <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingrese DNI"
                    pattern="[0-9]{8}" maxlength="8" required oninput="validarDni()">
                  <div class="invalid-feedback">Por favor ingrese un DNI válido de 8 dígitos.</div>
                </div>

                <div class="form-group">
                  <label for="nombre" class="font-weight-bold">Nombre</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese nombre" maxlength="20" required>
                  <div class="invalid-feedback">El nombre es obligatorio.</div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="apellido_paterno" class="font-weight-bold">Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido paterno" maxlength="15" required>
                    <div class="invalid-feedback">Ingrese el apellido paterno.</div>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="apellido_materno" class="font-weight-bold">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Apellido materno" maxlength="15" required>
                    <div class="invalid-feedback">Ingrese el apellido materno.</div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="correo" class="font-weight-bold">Correo</label>
                  <input type="email" class="form-control" id="correo" name="correo" oninput="formatCorreo()" placeholder="Correo electrónico"
                    required pattern="^[a-zA-Z0-9._%+-]+@(gmail|hotmail)\.com$"
                    title="Solo se permite correos @gmail.com o @hotmail.com">
                  <div class="invalid-feedback">Solo se permite correos @gmail.com o @hotmail.com</div>
                </div>
              </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">
              <div class="card border-light shadow-sm p-3">
                <h6 class="text-dark mb-3">Datos de Acceso</h6>

                <div class="form-group">
                  <label for="telefono" class="font-weight-bold">Teléfono</label>
                  <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" pattern="[0-9]{9}" maxlength="9" required oninput="validarTelefono()">
                  <div class="invalid-feedback">Ingrese un número de 9 dígitos.</div>
                </div>

                <div class="form-group">
                  <label for="direccion" class="font-weight-bold">Dirección</label>
                  <input type="text" class="form-control" id="direccion" name="direccion" required maxlength="20" placeholder="Dirección">
                  <div class="invalid-feedback">La dirección es obligatoria.</div>
                </div>
              </div>

              <div class="form-group">
                <label for="usuarios" class="font-weight-bold">Usuario</label>
                <input type="text" class="form-control" id="usuarios" name="usuarios" placeholder="Usuario" maxlength="12" required>
                <div class="invalid-feedback">Ingrese un nombre de usuario.</div>
              </div>

              <div class="form-group">
                <label for="rol" class="font-weight-bold">Rol</label>
                <select class="form-control" id="rol" name="rol" required>
                  <option value="">Seleccione Rol</option>
                  <option value="Administrador">Administrador</option>
                  <option value="Usuario">Usuario</option>
                </select>
                <div class="invalid-feedback">Seleccione un rol.</div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="claves" class="font-weight-bold">Contraseña</label>
                  <input type="password" class="form-control" id="claves" name="claves" placeholder="Contraseña" minlength="8" required>
                  <div class="invalid-feedback">Ingrese una contraseña de al menos 8 caracteres.</div>
                </div>
                <div class="form-group col-md-6">
                  <label for="confirmar" class="font-weight-bold">Confirmar</label>
                  <input type="password" class="form-control" id="confirmar" name="confirmar" placeholder="Confirmar contraseña" minlength="8" required>
                  <div class="invalid-feedback">Confirme la contraseña.</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Botón -->
        <div class="text-right px-4 pb-3">
          <button class="btn btn-primary" type="submit" onclick="registrarUsuario(event);" id="btnAccion">
            Registrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



<div id="permisos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Asignar Permisos</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmPermisos">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const mayusculas = ["direccion", "nombre", "apellido_paterno", "apellido_materno"];
        mayusculas.forEach(id => {
            const input = document.getElementById(id);
            input.addEventListener("input", function() {
                this.value = this.value.toUpperCase();
            });
        });
    });

    function validarDni() {
        const input = document.getElementById("dni");
        const regex = /^\d{8}$/;
        const valor = input.value.trim();

        input.classList.remove("is-valid", "is-invalid");
        input.setCustomValidity("");

        if (valor === "") return;

        if (regex.test(valor)) {
            input.classList.add("is-valid");
        } else {
            input.classList.add("is-invalid");
            input.setCustomValidity("El DNI debe tener exactamente 8 dígitos numéricos.");
        }
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
    $(document).ready(function() {
        $('#tblUsuarios').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            ajax: {
                url: base_url + "Usuarios/listar",
                dataSrc: function(json) {
                    console.log("Datos recibidos:", json);
                    return json;
                }
            },
            columns: [{
                    data: "id"
                },
                {
                    data: "usuario"
                },
                {
                    data: "nombre"
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return row.apellido_paterno + ' ' + row.apellido_materno;
                    }
                },

                {
                    data: "estado",
                    className: "text-center"
                },
                {
                    data: "acciones"
                },
            ],

            responsive: true,
            destroy: true,
            pageLength: 10
        });
    });
</script>
<?php include "Views/Templates/footer.php"; ?>