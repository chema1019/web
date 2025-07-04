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




  .card-footer {
    padding-right: 1.5rem;
  }

  .table-responsive {
    max-height: 500px;
    overflow-y: auto;
    overflow-x: auto;
    width: 100%;
  }
  
  #tblPagos tbody td:nth-child(4)
 
  /* Razón social */


  /* Monto */
    {
    font-weight: bold;
  }



  #tblPagos th,
  #tblPagos td {
    font-size: 14px;
    /* o el tamaño que prefieras */
  }

  .text-truncate-tooltip {
    display: inline-block;
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
    cursor: pointer;
  }
</style>
<div class="container mt-3" style="max-width: 98%;">
  <div class="card shadow">
    <div class="modal-header bg-primary text-white justify-content-between">
      <h5 class="mb-0">Validación de Pagos</h5>
    </div>

    <div class="card-body">
      <form id="frmValidarpago" class="needs-validation" onsubmit="registrarValidacion(event)" autocomplete="off" novalidate>
        <div class="row">

          <!-- Columna Izquierda -->
          <div class="col-md-8 border-right">
            <div class="input-group mb-4">
              <input id="ruc" type="text" class="form-control" name="ruc" placeholder="RUC" pattern="^\d{11}$" required oninput="validarRuc()" inputmode="numeric">
              <button class="btn btn-outline-secondary" type="button" onclick="buscarRucTabla()">Buscar</button>
              <div class="invalid-feedback">El RUC debe tener exactamente 11 dígitos numéricos.</div>
              <input type="hidden" name="id_empresa" id="id_empresa">
              <input type="hidden" name="ids_pagos" id="ids_pagos">
            </div>

            <div class="table-responsive" style="max-height: 500px;">
              <table class="table table-striped table-bordered table-sm text-center align-middle small" id="tblPagos">
                <thead class="table-light">
                  <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th style="width: 60px;">Estado</th>
                    <th style="width: 50px;">Código</th>
                    <th>Razón social</th>
                    <th>Monto</th>
                    <th>Fecha</th>
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

          <!-- Columna Derecha -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="expediente">Número de Expediente</label>
              <input type="text" class="form-control" id="expediente" name="expediente" placeholder="Ej: EXP-00123" required oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '')">
              <div class="invalid-feedback">Ingrese un número de expediente válido</div>
            </div>

            <div class="form-group">
              <label for="fecha_expediente">Fecha de Expediente</label>
              <input type="date" class="form-control" id="fecha_expediente" name="fecha_expediente" required>
              <div class="invalid-feedback">Ingrese la Fecha</div>
            </div>

            <div class="form-group">
              <label for="modalidad">Modalidad</label>
              <select class="form-control" id="modalidad" name="modalidad" required onchange="llenarModalidad()">
                <option value="" selected disabled>Seleccione</option>
                <option>Transporte en Autocolectivo</option>
                <option>Transporte Especial</option>
                <option>Otro</option>
              </select>
              <div class="invalid-feedback">Seleccione la modalidad</div>
            </div>

            <div class="form-group">
              <label for="n_conductores">N° de conductores</label>
              <select class="form-control" id="n_conductores" name="n_conductores" required onchange="llenarConductor()">
                <option value="" disabled selected>Seleccione</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
              </select>
              <div class="invalid-feedback">Seleccione la cantidad</div>
            </div>
          </div>

          <!-- Botón -->
          <div class="text-right mt-4 border-top pt-3">
            <button type="submit" class="btn btn-primary">
              Guardar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>



<div class="modal fade" id="modalPagoDetalle" tabindex="-1" role="dialog" aria-labelledby="modalPagoDetalleLabel" aria-hidden="true"> 
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
          <a id="detalleRecibo" href="#" class="btn btn-sm btn-secondary" target="_blank">
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
  function llenarModalidad() {
    const modalidad = document.getElementById("modalidad");


    // Validar modalidad
    if (!modalidad.value) {
      modalidad.classList.add("is-invalid");
    } else {
      modalidad.classList.remove("is-invalid");
    }

    // Validar cantidad de conductores

  }

  function llenarConductor() {
    const conductores = document.getElementById("n_conductores");

    // Validar cantidad de conductores
    if (!conductores.value) {
      conductores.classList.add("is-invalid");
    } else {
      conductores.classList.remove("is-invalid");
    }

    // Validar cantidad de conductores

  }

  document.getElementById("ruc").addEventListener("input", function() {
    this.value = this.value.replace(/[^0-9]/g, "").slice(0, 11);
  });

  function validarRuc() {
    const input = document.getElementById("ruc");
    const feedback = document.getElementById("ruc-feedback");
    const valor = input.value.trim();
    const regex = /^\d{11}$/;

    input.classList.remove("is-valid", "is-invalid");
    input.setCustomValidity("");

    if (valor === "") {
      feedback.style.display = "none";
      return;
    }

    if (regex.test(valor)) {
      input.classList.add("is-valid");
      feedback.style.display = "none";
    } else {
      input.classList.add("is-invalid");
      input.setCustomValidity("El RUC debe tener exactamente 11 dígitos numéricos.");
      feedback.style.display = "block";
    }
  }
  let tblPagos;
  $(document).ready(function() {
    tblPagos = $("#tblPagos").DataTable({
      responsive: true,
      processing: true,
      serverSide: false,
      pageLength: 25,
      lengthMenu: [
        [10, 25, 50, 100],
        [10, 25, 50, 100]
      ],
      searching: false,
      data: [], // 👈 Sin datos al inicio
      columns: [{
          data: "id",
          orderable: false,
          className: "no-sort",
          render: function(data) {
            return `<input type="checkbox" class="checkPago" value="${data}">`;
          },
        },
        {
          data: "estado_pago",
          render: function(data) {
            if (data == 1) {
              return '<span class="badge badge-info">Activo</span>';
            } else {
              return '<span class="badge badge-warning">Pagado</span>';
            }
          },
          width: "70px"
        },
        {
          data: "codigo_pago",
          width: "80px"
        },
        {
          data: "razon_social"
        },
        {
          data: "monto"
        },
        {
          data: "fecha_pago"
        },
        {
          data: "id",
          render: function(data, type, row) {
            return `<button type="button" onclick="verPago(${data})" class="btn btn-info btn-sm">Ver</button>`;
          },
        },
      ],
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
      },

      initComplete: function() {
        // 👇 Muestra spinner al inicio
        $("#tbodyPagos").html(`
                <tr id="spinner-pagos">
                    <td colspan="11" class="spinner-cell">
                        <div class="spinner-content">
                            <div class="spinner-material"></div>
                            <p class="spinner-text">Esperando búsqueda...</p>
                        </div>
                    </td>
                </tr>
            `);
      },
      destroy: true
    });
  });
</script>
<?php include "Views/Templates/footer.php"; ?>