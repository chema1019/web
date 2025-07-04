<?php include "Views/Templates/header.php"; ?>
<style>
  .card-header {
    width: 100%;
  }

  .card-header>div {
    flex: 1;
  }

  .invalid-feedback {
    position: absolute;
    top: 100%;
    /* Justo debajo del input */
    left: 0;
    font-size: 0.875rem;
    margin-top: 2px;
    color: #dc3545;
    /* Color rojo de Bootstrap */
    z-index: 10;
  }


  .form-group {
    position: relative;
  }


  .card-header>div:last-child {
    text-align: right;
  }
</style>

<!-- Tarjeta con Título y Formulario de Búsqueda -->
<div class="card shadow mb-4" style="margin-top: 20px;">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0">Empresas de Transportes</h5>
  </div>

  <div class="card-body">
    <form id="frmBusquedaEmpresas" class="needs-validation" onsubmit="buscarEmpresas(event)" novalidate>
      <div class="form-row align-items-end">
        <div class="form-group col-md-3">
          <label for="tipoBusqueda"><strong>Buscar por</strong></label>
          <select id="tipoBusqueda" class="form-control" onchange="actualizarCampoBusqueda()">
            <option value="ruc">RUC</option>
            <option value="razon_social">RAZÓN SOCIAL</option>
          </select>
        </div>

        <div class="form-group col-md-6 position-relative">
          <label for="valorBusqueda" id="labelBusqueda"><strong>Razón social o RUC</strong></label>
          <input type="text" id="valorBusqueda" class="form-control" onchange="Validarbusqueda()" placeholder="Ingrese razón social o RUC" required>
          <div class="invalid-feedback">Ingrese un valor válido</div>
        </div>

        <div class="form-group col-md-3">
          <button type="submit" class="btn btn-primary btn-block">Buscar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Sección de Resultados -->
<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0 fw-bold">Resultado</h5>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center" id="tblEmpresas" style="width: 100%;">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>Razón Social</th>
            <th>RUC</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th>Fecha Vigencia</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal: Detalle de la Empresa -->
<div class="modal fade" id="modalEmpresa" tabindex="-1" role="dialog" aria-labelledby="modalEmpresaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg rounded">
      <!-- Encabezado -->
      <div class="modal-header bg-primary border-bottom-0 rounded-top">
        <h5 class="modal-title text-white" id="modalEmpresaLabel">Detalle de la Empresa</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body text-dark" style="font-size: 0.9rem;">
        <form id="frmEmpresa" novalidate>
          <input type="hidden" id="id" name="id" />

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="font-weight-bold">Razón Social</label>
              <input type="text" id="razon_social" name="razon_social" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6">
              <label class="font-weight-bold">RUC</label>
              <input type="text" id="ruc" name="ruc" class="form-control bg-light" readonly>
            </div>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold">Dirección</label>
            <input type="text" id="direccion" name="direccion" class="form-control bg-light" readonly>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="font-weight-bold">Teléfono</label>
              <input type="text" id="telefono" name="telefono" class="form-control bg-light" readonly>
            </div>
            <div class="col-md-6">
              <label class="font-weight-bold">Fecha de Vigencia</label>
              <input type="date" id="fecha_vigencia" name="fecha_vigencia" class="form-control bg-light" readonly>
            </div>
          </div>

          <div class="mb-3">
            <label class="font-weight-bold">Email</label>
            <input type="text" id="email" name="email" class="form-control bg-light" readonly>
          </div>
        </form>
      </div>

      <!-- Pie -->
      <div class="modal-footer bg-light border-top-0 rounded-bottom">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<script>
  document.getElementById("valorBusqueda").addEventListener("input", function() {
    const tipo = document.getElementById("tipoBusqueda").value;
    let valor = this.value;

    if (tipo === "ruc") {
      // Solo números
      this.value = valor.replace(/[^\d]/g, '');
    } else if (tipo === "razon_social") {
      // Solo letras mayúsculas y espacios
      this.value = valor.toUpperCase().replace(/[^A-ZÑ ]/g, '');
    }
  });

  function actualizarCampoBusqueda() {
    const tipo = document.getElementById("tipoBusqueda").value;
    const input = document.getElementById("valorBusqueda");
    const label = document.getElementById("labelBusqueda");
    const icono = document.getElementById("iconoBusqueda");

    if (tipo === "ruc") {
      input.maxLength = 11;
      input.placeholder = "11 dígitos";
      input.pattern = "\\d{11}";
      label.textContent = "Número de RUC";
 
    } else if (tipo === "razon_social") {
      input.removeAttribute("maxLength");
      input.placeholder = "Ingrese Empresa";
      input.pattern = "^[A-ZÑ ]+$";
      label.textContent = "Razón Social";

    }

    input.value = "";
    input.classList.remove("is-valid", "is-invalid");
    input.setCustomValidity("");
  }

  function Validarbusqueda() {
    const tipo = document.getElementById("tipoBusqueda").value;
    const input = document.getElementById("valorBusqueda");
    const feedback = input.nextElementSibling;
    const valor = input.value.trim();

    input.classList.remove("is-valid", "is-invalid");
    input.setCustomValidity("");
    feedback.style.display = "none";

    let regex;
    let mensaje;

    switch (tipo) {
      case "ruc":
        regex = /^\d{11}$/;
        mensaje = "El RUC debe tener exactamente 11 dígitos.";
        break;
      case "razon_social":
        regex = /^[A-ZÑ ]+$/;
        mensaje = "La razón social debe contener solo letras mayúsculas y espacios.";
        break;
      default:
        return;
    }

    if (valor === "") return;

    if (regex.test(valor)) {
      input.classList.add("is-valid");
    } else {
      input.classList.add("is-invalid");
      input.setCustomValidity(mensaje);
      feedback.textContent = mensaje;
      feedback.style.display = "block";
    }
  }
</script>
<?php include "Views/Templates/footer.php"; ?>