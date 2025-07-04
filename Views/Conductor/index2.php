<?php include "Views/Templates/header.php"; ?>

<style>
    #tblConductor tbody td:nth-child(2),
    #tblConductor tbody td:nth-child(4)
    /* Razón social */


    /* Monto */
        {
        font-weight: bold;
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


    .modal-header.bg-danger {
        background-color: #e60000 !important;
    }

    .modal-header .fa-check-circle {
        background: white;
        border-radius: 50%;
        padding: 2px;
    }

    /* Encabezado de la tabla */


    .text-truncate-tooltip {
        display: inline-block;
        max-width: 240px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
        cursor: pointer;
    }


    /* Botones dentro de la tabla */
    #tblConductor td .btn {
        padding: 1px 4px;
        font-size: 11px;
    }

    /* Contenedor con scroll para tabla grande */
    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
        overflow-x: auto;
        width: 100%;
    }

    /* Espaciado entre botones externos */
    .gap-2>* {
        margin-right: 0.5rem;
    }

    .input-group .form-control {
        min-height: 38px;
        /* o la altura estándar de tus inputs */
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

    th.no-sort::before,
    th.no-sort::after {
        display: none !important;
    }
</style>
<!-- Tarjeta con Título y Búsqueda -->
<div class="card shadow mb-4 mt-4">
  <div class="card-header bg-primary text-white d-flex align-items-center">
    <h5 class="mb-0">Nómina de Conductores</h5>
  </div>

  <div class="card-body">
    <form id="formBusquedaConductores" class="needs-validation" onsubmit="buscarConductores(event)" novalidate>
      <div class="form-row align-items-end">
        
        <!-- Tipo de búsqueda -->
        <div class="form-group col-md-3">
          <label for="tipoBusqueda"><strong>Buscar por</strong></label>
          <select id="tipoBusqueda" class="form-control" onchange="actualizarCampoBusqueda()">
            <option value="dni">DNI</option>
            <option value="ruc">RUC</option>
            <option value="ce">CE</option>
          </select>
        </div>

        <!-- Campo de búsqueda -->
        <div class="form-group col-md-6 position-relative">
          <label for="valorBusqueda"><strong id="labelBusqueda">Número de DNI</strong></label>
          <div class="input-group">
            <input
              type="text"
              name="valorBusqueda"
              id="valorBusqueda"
              class="form-control"
              placeholder="Ingrese número de documento"
              onchange="Validarbusqueda()"
              required
              pattern="\d*"
              inputmode="numeric"
              oninput="this.value = this.value.replace(/\D/g, '')"
            >
            <div class="invalid-feedback">Ingrese un documento válido</div>
          </div>
        </div>

        <!-- Botón de búsqueda -->
        <div class="form-group col-md-3">
          <button type="submit" class="btn btn-primary w-100">
            Buscar
          </button>
        </div>

      </div>
    </form>
  </div>
</div>



<!-- Tabla y botones -->
<div class="card shadow-sm mb-4">
<div class="card-header bg-primary text-white">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold">Resultado</h5>
    <button class="btn btn-light btn-sm" type="button" onclick="imprimirTabla()">
      IMPRIMIR
    </button>
  </div>
</div>

  <div class="card-body">
    <div class="table-responsive">
      <div id="spinner-conductores" style="display: none; text-align: center; padding: 10px;">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2">Buscando conductores...</p>
      </div>

      <table class="table table-bordered table-hover" id="tblConductor" style="width: 100%; font-size: 14px;">
        <thead class="thead-light text-center">
          <tr>
            <th>#</th>
            <th style="width: 240px;">Razón Social</th>
            <th>N° Doc</th>
            <th style="width: 170px;">Conductor</th>
            <th>Categoría</th>
            <th>Licencia</th>
            <th style="width: 80px;">Expedición</th>
            <th style="width: 100px;">Revalidación</th>
            <th>Estado</th>
            <th style="width: 60px;">Opc</th>
          </tr>
        </thead>
        <tbody id="tbodyConductores">
          <tr id="spinner-fila-conductores" style="display: table-row;">
            <td colspan="10" class="text-center py-3">
              <div class="spinner-border text-secondary" role="status"></div>
              <p class="mt-2 mb-0 text-muted">Cargando conductores...</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


<!-- Modal para editar conductor -->
<div class="modal fade" id="modalEditarConductor" tabindex="-1" role="dialog" aria-labelledby="modalEditarConductorLabel" aria-hidden="true"> 
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      
      <!-- Cabecera -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalEditarConductorLabel">Editar Conductor</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body">
        <form id="frmEditarConductor" class="needs-validation" onsubmit="actualizarConductor(event)" autocomplete="off" enctype="multipart/form-data" novalidate>
          <div class="row">

            <!-- Columna Izquierda -->
            <div class="col-md-4">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="mb-3">
                    <label for="edit_expediente" class="form-label">Expediente</label>
                    <input type="text" class="form-control" id="edit_expediente" name="expediente" required>
                  </div>

                  <label for="edit_ruc">RUC</label>
                  <input type="text" class="form-control" id="edit_ruc" name="ruc" readonly>
                  <input type="hidden" name="id_empresa" id="edit_id_empresa">
                  <input type="hidden" name="id_conductor" id="edit_id_conductor">
                </div>
              </div>

              <div class="card">
                <div class="card-header text-center py-2">Foto Conductor</div>
                <div class="card-body text-center p-2">
                  <img src="<?php echo base_url; ?>assets/img/silueta.jpg" id="edit_foto_preview" class="img-fluid mx-auto" style="max-height: 262px; object-fit: contain;">
                  <input type="file" class="form-control form-control-sm mt-2" name="foto" id="edit_foto" onchange="mostrarFotoEditar(event)">
                </div>
              </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-8">
              <div class="card">
                <div class="card-header bg-primary text-white">Datos del Conductor</div>
                <div class="card-body">
                  <div class="row">
                    <!-- Parte izquierda -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_t_documento">Tipo de documento</label>
                        <select id="edit_t_documento" name="t_documento" class="form-control" required>
                          <option value="DNI">DNI</option>
                          <option value="CE">CE</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="edit_numero_documento">Número de documento</label>
                        <input type="text" class="form-control" name="numero_documento" id="edit_numero_documento" pattern="\d*" required>
                      </div>

                      <div class="form-group">
                        <label for="edit_nombres">Nombres</label>
                        <input type="text" class="form-control" name="nombres" id="edit_nombres" required>
                      </div>

                      <div class="form-group">
                        <label for="edit_apellido_paterno">Apellido paterno</label>
                        <input type="text" class="form-control" name="apellido_paterno" id="edit_apellido_paterno" required>
                      </div>

                      <div class="form-group">
                        <label for="edit_apellido_materno">Apellido materno</label>
                        <input type="text" class="form-control" name="apellido_materno" id="edit_apellido_materno" required>
                      </div>
                    </div>

                    <!-- Parte derecha -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="edit_licencia">Licencia de conducir</label>
                        <input type="text" class="form-control" name="licencia" id="edit_licencia" required>
                      </div>

                      <div class="form-group">
                        <label for="edit_categoria">Categoría</label>
                        <input type="text" class="form-control" name="categoria" id="edit_categoria" required>
                      </div>

                      <div class="form-group">
                        <label for="edit_fecha_expedicion">Fecha de expedición</label>
                        <input type="date" class="form-control" name="fecha_expedicion" id="edit_fecha_expedicion" required>
                      </div>

                      <div class="form-group">
                        <label for="edit_fecha_vencimiento">Fecha de vencimiento</label>
                        <input type="date" class="form-control" name="fecha_vencimiento" id="edit_fecha_vencimiento" required>
                      </div>

                      <div class="form-group">
                        <label for="edit_estado_licencia">Estado de licencia</label>
                        <select class="form-control" id="edit_estado_licencia" name="estado_licencia">
                          <option value="Vigente">Vigente</option>
                          <option value="Vencido">Vencido</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                  <button type="submit" class="btn btn-success rounded-circle shadow" style="width: 65px; height: 65px;">
                    <i class="fa fa-save" style="font-size: 26px; color: white;"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="modalEliminarConductor" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true"> 
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content border-0">

      <!-- Cabecera -->
      <div class="modal-header bg-danger text-white d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-0 font-weight-bold" id="nombreEmpresa">NOMBRE DE EMPRESA</h5>
          <small id="rucEmpresa" class="h4">00000000000</small>
        </div>
        <div>
          <span class="h1 font-weight-bold">-1</span>
        </div>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body">
        <h4 class="text-danger font-weight-bold mb-3">Baja de conductor</h4>

        <div class="form-group">
          <label><strong>Número de expediente</strong></label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">EXP</span>
            </div>
            <input type="text" class="form-control" id="expedienteInput" placeholder="">
          </div>
        </div>

        <div class="mt-4 p-3 bg-light border">
          <h6 class="mb-3 font-weight-bold">Datos del conductor</h6>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>N° de documento</label>
              <input type="text" class="form-control" id="documentoInput" readonly>
            </div>
            <div class="form-group col-md-6">
              <label>Conductor</label>
              <input type="text" class="form-control" id="conductorInput" readonly>
            </div>
          </div>
        </div>

        <div class="mt-3 text-muted small">
          Mostrando 0 a 0 de registros
        </div>
      </div>

      <!-- Pie -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
      </div>

    </div>
  </div>
</div>




<script>
    function mostrarFotoEditar(event) {
        const archivo = event.target.files[0];
        if (archivo) {
            const lector = new FileReader();
            lector.onload = function(e) {
                document.getElementById('edit_foto_preview').src = e.target.result;

            };
            lector.readAsDataURL(archivo);
        }
    }

    function Validarbusqueda() {
        const tipo = document.getElementById("tipoBusqueda").value;
        const input = document.getElementById("valorBusqueda");
        const feedback = input.nextElementSibling; // div.invalid-feedback
        const valor = input.value.trim();

        input.classList.remove("is-valid", "is-invalid");
        input.setCustomValidity("");
        feedback.style.display = "none";

        // Validaciones según el tipo de documento
        let regex;
        let mensaje;

        switch (tipo) {
            case "dni":
                regex = /^\d{8}$/;
                mensaje = "El DNI debe tener exactamente 8 dígitos.";
                break;
            case "ruc":
                regex = /^\d{11}$/;
                mensaje = "El RUC debe tener exactamente 11 dígitos.";
                break;
            case "ce":
                regex = /^\d{9}$/; // puedes ajustar la longitud según lo que uses para CE
                mensaje = "El CE debe tener exactamente 9 dígitos.";
                break;
            default:
                regex = /^\d+$/;
                mensaje = "Ingrese un documento válido.";
        }

        if (valor === "") {
            // No mostrar error si está vacío, se puede validar al enviar
            return;
        }

        if (regex.test(valor)) {
            input.classList.add("is-valid");
        } else {
            input.classList.add("is-invalid");
            input.setCustomValidity(mensaje);
            feedback.textContent = mensaje;
            feedback.style.display = "block";
        }
    }

    document.getElementById("valorBusqueda").addEventListener("input", function(e) {
        this.value = this.value.replace(/\D/g, ""); // Elimina todo lo que no sea dígito
    });

    let idEliminar = 0;
    let tblConductor;

    $(document).ready(function() {
        tblConductor = $("#tblConductor").DataTable({
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
                    className: "text-center"
                },
                {
                    data: "razon_social",
                    render: function(data) {
                        // Escapa el texto para prevenir errores por comillas
                        const safeText = $('<div>').text(data).html();
                        return `<span class="text-truncate-tooltip" data-full="${safeText}">${safeText}</span>`;
                    },
                },



                {
                    data: "n_documento",
                    className: "text-center"
                },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        return row.nombre + ' ' + row.apellido_paterno + ' ' + row.apellido_materno;
                    }
                },
                {
                    data: "categoria",
                    className: "text-center"
                },
                {
                    data: "licencia_conducir",
                    className: "text-center"
                },
                {
                    data: "fecha_expedicion",
                    className: "text-center"
                },
                {
                    data: "fecha_vencimiento",
                    className: "text-center"
                },
                {
                    data: "estado",
                    className: "text-center"
                },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        return `
        <button class="btn btn-warning btn-sm" onclick="editarConductor(${row.id})">
          <i class="fa fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm ms-1" onclick="eliminarConductor(${row.id})">
          <i class="fa fa-trash"></i>
        </button>`;
                    }
                }
            ],

            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },

            initComplete: function() {
                // 👇 Muestra spinner al inicio
                $("#tbodyConductores").html(`
                <tr id="spinner-conductores">
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


    function imprimirTabla() {
        const tipo = document.getElementById("tipoBusqueda").value;
        const valor = document.getElementById("valorBusqueda").value.trim();

        // Validar que exista un valor antes de imprimir
        if (valor === "") {
            Swal.fire({
                icon: 'info',
                title: 'Campo vacío',
                html: '<strong>Realice una búsqueda antes de imprimir</strong>',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        const url = `${base_url}Conductores/pdf?tipo=${tipo}&valor=${valor}`;
        window.open(url, "_blank");
    }
    $(document).ready(function() {
        aplicarTooltipsSoloSiTruncado(); // por si hay algo precargado

        $("#tblConductor").on("draw.dt", function() {
            aplicarTooltipsSoloSiTruncado(); // solo si está truncado
        });
    });


    function aplicarTooltipsSoloSiTruncado() {
        $('.text-truncate-tooltip').each(function() {
            const $el = $(this);
            $el.removeAttr('title').tooltip('dispose'); // Limpia tooltip anterior

            if (this.offsetWidth < this.scrollWidth) {
                const fullText = $el.data('full');
                $el.attr('title', fullText).tooltip(); // Solo si se trunca
            }
        });
    }



    function buscarConductores(e) {
        e.preventDefault();
        const form = document.getElementById("formBusquedaConductores");

        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        }

        const tipo = document.getElementById("tipoBusqueda").value;
        const valor = document.getElementById("valorBusqueda").value;
        // Mostrar spinner
        document.getElementById('spinner-conductores').style.display = 'block';
        const nuevaUrl = base_url + "Conductores/buscarConductores?tipo=" + tipo + "&valor=" + valor;

        // Si la tabla ya existe, solo actualizamos los datos
        if ($.fn.DataTable.isDataTable('#tblConductor')) {
            $.ajax({
                url: nuevaUrl,
                method: "GET",
                dataType: "json",
                success: function(json) {
                    document.getElementById('spinner-conductores').style.display = 'none';

                    if (json.length === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sin resultados',
                            html: '<strong>No se encontraron datos para la búsqueda</strong>',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }

                    tblConductor.clear().rows.add(json).draw();
                },
                error: function() {
                    document.getElementById('spinner-conductores').style.display = 'none';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: '<strong>No se pudo realizar la búsqueda</strong>',
                        showConfirmButton: true
                    });
                }
            });
        } else {
            tblConductor = $("#tblConductor").DataTable({
                ajax: {
                    url: nuevaUrl,
                    dataSrc: function(json) {
                        document.getElementById('spinner-conductores').style.display = 'none';
                        if (json.length === 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Sin resultados',
                                html: '<strong>No se encontraron datos para la búsqueda</strong>',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                        return json;
                    }
                },
                responsive: true,
                processing: true,
                serverSide: false,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                searching: false,
                destroy: true,
                columns: [{
                        data: "id",
                        className: "text-center"
                    },
                    {
                        data: "razon_social",

                        render: function(data) {
                            const safeText = $('<div>').text(data).html(); // Escapar texto
                            return `<span class="text-truncate-tooltip" data-full="${safeText}">${safeText}</span>`;
                        }
                    },
                    {
                        data: "n_documento",
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            return row.nombre + ' ' + row.apellido_paterno + ' ' + row.apellido_materno;
                        }
                    },
                    {
                        data: "categoria",
                        className: "text-center"
                    },
                    {
                        data: "licencia_conducir",
                        className: "text-center"
                    },
                    {
                        data: "fecha_expedicion",
                        className: "text-center"
                    },
                    {
                        data: "fecha_vencimiento",
                        className: "text-center"
                    },
                    {
                        data: "estado",
                        className: "text-center"
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function(data, type, row) {
                            return `
        <button class="btn btn-warning btn-sm" onclick="editarConductor(${row.id})">
          <i class="fa fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm ms-1" onclick="eliminarConductor(${row.id})">
          <i class="fa fa-trash"></i>
        </button>`;
                        }
                    }
                ],

                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },

            });
        }
    }



    function eliminarConductor(id) {
        fetch(base_url + "Conductores/obtenerConductor?id=" + id)
            .then(res => res.json())
            .then(data => {
                if (data) {
                    // Insertar los datos en el modal
                    document.getElementById("nombreEmpresa").textContent = data.empresa;
                    document.getElementById("rucEmpresa").textContent = data.ruc;
                    document.getElementById("documentoInput").value = data.n_documento;
                    document.getElementById("conductorInput").value = data.nombre;
                    document.getElementById("expedienteInput").value = "";

                    idEliminar = id; // Guardar el ID para eliminar después

                    $('#modalEliminarConductor').modal('show');
                } else {
                    Swal.fire("Error", "No se encontró información del conductor", "error");
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire("Error", "Error de conexión", "error");
            });
    }


    document.getElementById('btnConfirmarEliminar').addEventListener('click', async function() {
        const expediente = document.getElementById("expedienteInput").value.trim();

        if (idEliminar > 0 && expediente !== "") {
            try {
                const response = await fetch(base_url + 'Conductores/eliminar/' + idEliminar, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'expediente=' + encodeURIComponent(expediente)
                });

                const data = await response.json();
                $('#modalEliminarConductor').modal('hide');

                if (data.success) {
                    Swal.fire('Eliminado', data.message, 'success');
                    tblConductor.ajax.reload(null, false);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Error de conexión', 'error');
            }
        } else {
            Swal.fire('Advertencia', 'Debes ingresar el número de expediente', 'warning');
        }
    });


    function actualizarCampoBusqueda() {
        const tipo = document.getElementById("tipoBusqueda").value;
        const input = document.getElementById("valorBusqueda"); // ✅ Este es el ID correcto
        const label = document.getElementById("labelBusqueda");
        const icono = document.getElementById("iconoBusqueda");

        if (tipo === "dni") {
            input.maxLength = 8;
            input.placeholder = "8 dígitos";
            input.pattern = "\\d{8}";
            label.textContent = "Número de DNI";
            icono.innerHTML = '<i class="fa fa-id-card"></i>';
        } else if (tipo === "ruc") {
            input.maxLength = 11;
            input.placeholder = "Hasta 11 dígitos";
            input.pattern = "\\d{11}";
            label.textContent = "Número de RUC";
            icono.innerHTML = '<i class="fa fa-building"></i>';
        } else if (tipo === "ce") {
            input.maxLength = 11;
            input.placeholder = "Hasta 11 dígitos";
            input.pattern = "\\d{9,11}"; // CE puede tener diferentes longitudes
            label.textContent = "Número de CE";
            icono.innerHTML = '<i class="fa fa-passport"></i>';
        }


        // Estas líneas deben ir fuera del if
        input.value = "";
        input.classList.remove("is-valid", "is-invalid");
        input.setCustomValidity("");
    }

    function editarConductor(id) {
        const url = base_url + "Conductores/obtenerConductorPorId";

        fetch(url, {
                method: "POST",
                body: new URLSearchParams({
                    id
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data) {
                    // Llenar campos del formulario
                    document.getElementById("edit_id_conductor").value = data.id;
                    document.getElementById("edit_expediente").value = data.expediente;
                    document.getElementById("edit_ruc").value = data.ruc;
                    document.getElementById("edit_id_empresa").value = data.id_empresa;
                    document.getElementById("edit_t_documento").value = data.t_documento;
                    document.getElementById("edit_numero_documento").value = data.numero_documento;
                    document.getElementById("edit_nombres").value = data.nombre;
                    document.getElementById("edit_apellido_paterno").value = data.apellido_paterno;
                    document.getElementById("edit_apellido_materno").value = data.apellido_materno;
                    document.getElementById("edit_licencia").value = data.licencia_conducir;
                    document.getElementById("edit_categoria").value = data.categoria;
                    document.getElementById("edit_fecha_expedicion").value = data.fecha_expedicion;
                    document.getElementById("edit_fecha_vencimiento").value = data.fecha_vencimiento;
                    document.getElementById("edit_estado_licencia").value = data.estado;

                    // Mostrar foto si existe
                    if (data.foto) {
                        const ruta = base_url + "assets/img/conductores/" + data.foto;
                        console.log("Ruta foto:", ruta); // ✅ Aquí sí usas la variable correcta

                        const img = new Image();
                        img.onload = function() {
                            document.getElementById("edit_foto_preview").src = ruta;
                        };
                        img.onerror = function() {
                            console.log("Imagen no encontrada, mostrando silueta");
                            document.getElementById("edit_foto_preview").src = base_url + "assets/img/silueta.jpg";
                        };
                        img.src = ruta;
                    } else {
                        document.getElementById("edit_foto_preview").src = base_url + "assets/img/silueta.jpg";
                    }


                    // Mostrar modal
                    $("#modalEditarConductor").modal("show");
                } else {
                    Swal.fire("Error", "No se encontraron datos del conductor", "error");
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Error al obtener datos del conductor", "error");
            });
    }


    // Ejecutar al cargar la página por defecto
    document.addEventListener("DOMContentLoaded", actualizarCampoBusqueda);
</script>
<?php include "Views/Templates/footer.php"; ?>