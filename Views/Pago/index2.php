<?php include "Views/Templates/header.php"; ?>
<style>
  input[type="checkbox"] {
    width: 15px;
    height: 15px;
  }

  .spinner-cell {
    text-align: center;
    padding: 30px 0;
  }

  .spinner-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .spinner-material {
    width: 30px;
    height: 30px;
    border: 4px solid rgba(0, 123, 255, 0.3);
    border-top-color: #007bff;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
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

  .spinner-text {
    margin-top: 10px;
    color: #007bff;
    font-weight: bold;
    font-size: 14px;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
</style>
<!-- Tarjeta con Título y Formulario de Búsqueda -->
<div class="card shadow mb-4" style="margin-top: 20px;">
  <div class="card-header bg-primary text-white d-flex align-items-center">
    <h5 class="mb-0">Recibos de ingreso a caja</h5>
  </div>

  <div class="card-body">
    <form id="frmBusquedaRecibos" class="needs-validation" onsubmit="buscarRecibo(event)" novalidate>
      <div class="form-row align-items-end">
        <div class="form-group col-md-3">
          <label for="tipoBusqueda"><strong>Buscar por</strong></label>
          <select id="tipoBusqueda" class="form-control" onchange="actualizarCampoBusqueda()">
            <option value="ruc">RUC</option>
            <option value="razon_social">Razón Social</option>
          </select>
        </div>

        <div class="form-group col-md-6">
          <label for="valorBusqueda" id="labelBusqueda"><strong>Razón social o RUC</strong></label>
          <input type="text" id="valorBusqueda" class="form-control" onchange="Validarbusqueda()" placeholder="Ingrese razón social o RUC" required>
          <div class="invalid-feedback">
            Ingrese un valor válido
          </div>
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
      <div id="spinner-pagos" style="display: none; text-align: center; padding: 10px;">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Cargando...</span>
        </div>
        <p class="mt-2">Buscando datos...</p>
      </div>

      <table class="table table-bordered table-hover text-center" id="tblRecibo" style="width: 100%;">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>Recibo</th>
            <th>Nombre</th>
            <th>RUC</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody id="tbodyPagos">
          <tr id="spinner-pagos" style="display: table-row;">
            <td colspan="11" class="spinner-cell">
              <div class="spinner-content">
                <div class="spinner-material"></div>
                <p class="spinner-text">Cargando pagos...</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


<div class="modal fade" id="modalPagoDetalle" tabindex="-2" role="dialog" aria-labelledby="modalPagoDetalleLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content rounded-3 shadow-lg border-0">

      <!-- Cabecera -->
      <div class="modal-header bg-primary text-white rounded-top py-3 px-4">
        <h5 class="modal-title font-weight-bold" id="modalPagoDetalleLabel">
          Detalle del Recibo de Ingreso
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body bg-light p-4">

        <!-- Datos Generales -->
        <div class="row mb-4">
          <div class="col-md-6">
            <p class="mb-1 text-muted"><strong>Empresa:</strong> <span id="detalleEmpresa">---</span></p>
            <p class="mb-0 text-muted"><strong>RUC:</strong> <span id="detalleRuc">---</span></p>
          </div>
          <div class="col-md-6 text-md-right mt-3 mt-md-0">
            <p class="mb-1 text-muted"><strong>Fecha de Pago:</strong> <span id="detalleFecha">--/--/----</span></p>
          </div>
        </div>

        <!-- Detalles del Servicio -->
        <div class="table-responsive rounded shadow-sm">
          <table class="table table-sm table-striped table-hover bg-white border">
            <thead class="thead-light">
              <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th class="text-right">Importe</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td id="detalleCodigo">---</td>
                <td id="detalleDescripcion">---</td>
                <td class="text-right font-weight-semibold" id="detalleMonto">S/ 0.00</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Total -->
        <div class="d-flex justify-content-end mt-4">
          <h5 class="font-weight-bold text-dark">Total: <span class="text-success" id="detalleMontoTotal">S/ 0.00</span></h5>
        </div>

        <!-- Información adicional -->
        <div class="text-right mt-3">
          <a id="detalleRecibo" href="#" class="btn btn-sm btn-primary" target="_blank">
            Ver Recibo
          </a>
        </div>

        <hr class="my-4">
        <div class="small text-muted">
          <p class="mb-1">Reimpreso por IP: <span id="detalleIp">192.168.0.1</span></p>
          <p class="mb-0">Fecha de impresión: <span id="detalleFechaImpresion">--/--/----</span></p>
        </div>

      </div>

      <!-- Pie -->
      <div class="modal-footer bg-white border-top py-3">
        <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
          Cerrar
        </button>
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
      icono.innerHTML = '<i class="fa fa-building"></i>';
    } else if (tipo === "razon_social") {
      input.removeAttribute("maxLength");
      input.placeholder = "Ingrese Empresa";
      input.pattern = "^[A-ZÑ ]+$";
      label.textContent = "Razón Social";
      icono.innerHTML = '<i class="fa fa-user"></i>';
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


  function verPago(id) {
    fetch(base_url + "Pago/verPago/" + id)
      .then((res) => {
        if (!res.ok) throw new Error("Error al obtener los datos del pago");
        return res.json();
      })
      .then((data) => {
        if (data) {
          document.getElementById("detalleRuc").innerText = data.ruc || "";
          document.getElementById("detalleEmpresa").innerText =
            data.razon_social || "";
          document.getElementById("detalleCodigo").innerText =
            data.codigo_pago || "";
          document.getElementById("detalleDescripcion").innerText =
            data.descripcion || "";

          // Monto unitario (de un solo servicio o código principal)
          document.getElementById("detalleMonto").innerText =
            "S/ " + parseFloat(data.monto).toFixed(2);

          // Total (igual al monto, ya que representa el total de los códigos)
          document.getElementById("detalleMontoTotal").innerText =
            "S/ " + parseFloat(data.monto).toFixed(2);

          document.getElementById("detalleFecha").innerText =
            data.fecha_pago || "";
          document.getElementById("detalleRecibo").href = data.recibo_url || "#";

          // Fecha de impresión (fecha actual)
          const hoy = new Date();
          const fechaImpresion = hoy.toLocaleDateString("es-PE"); // formato dd/mm/aaaa
          document.getElementById("detalleFechaImpresion").innerText =
            fechaImpresion;

          // Mostrar el modal
          $("#modalPagoDetalle").modal("show");
        } else {
          Swal.fire("Advertencia", "No se encontró el pago", "warning");
        }
      })
      .catch((error) => {
        console.error("Error al cargar el detalle del pago:", error);
        Swal.fire("Error", "No se pudo cargar el detalle del pago", "error");
      });
  }

  
</script>
<?php include "Views/Templates/footer.php"; ?>