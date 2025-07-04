let tblUsuarios, tblRecibo, tblpagos, tblEmpresas, tbl, tblConductor;

document.addEventListener("DOMContentLoaded", function () {
  document.querySelector("#modalPass").addEventListener("click", function () {
    document.querySelector("#frmCambiarPass").reset();
    $("#cambiarClave").modal("show");
  });
  const language = {
    decimal: "",
    emptyTable: "No hay información",
    info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
    infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
    infoFiltered: "(Filtrado de _MAX_ total entradas)",
    infoPostFix: "",
    thousands: ",",
    lengthMenu: "Mostrar _MENU_ Entradas",
    loadingRecords: "Cargando...",
    processing: "Procesando...",
    search: "Buscar:",
    zeroRecords: "Sin resultados encontrados",
    paginate: {
      first: "Primero",
      last: "Ultimo",
      next: "Siguiente",
      previous: "Anterior",
    },
  };
  const buttons = [];

  tblUsuarios = $("#tblUsuarios").DataTable({
    ajax: {
      url: base_url + "Usuarios/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "usuario" },
      { data: "nombre" },
      { data: "estado" },
      { data: "acciones" },
    ],
    responsive: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
    language,
    dom:
      "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons,
  });

  tbl = $("#tbl").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom: dom, // Asegúrate de que 'dom' esté definido
    buttons: buttons, // Asegúrate de que 'buttons' esté definido
    responsive: true, // Corregido de 'resonsieve' a 'responsive'
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });
});
// Tabla Empresa
$(document).ready(function () {
  $("#tblEmpresas").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    pageLength: 25,
    lengthMenu: [
      [10, 25, 50, 100],
      [10, 25, 50, 100],
    ],
    searching: false,
    ajax: {
      url: base_url + "Empresas/listarEmpresa",
      dataSrc: function (json) {
        console.log("Datos recibidos:", json);
        return json;
      },
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "razon_social",
      },
      {
        data: "ruc",
      },
      {
        data: "direccion",
      },
      {
        data: "telefono",
      },
      {
        data: "estado",
        render: function (data) {
          if (data == 1) {
            return '<span class="badge badge-info">ACTIVO</span>';
          } else {
            return '<span class="badge badge-danger">INACTIVO</span>';
          }
        },
      },

      {
        data: "fecha_vigencia",
      },
      {
        data: "id",
        render: function (data) {
          return `<button class="btn btn-info btn-sm" onclick="verEmpresa(${data})" title="Ver"><i></i></button>`;
        },
      },
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },

    responsive: true,
    destroy: true,
    pageLength: 10,
  });
   $(document).ready(function() {
    tblRecibo = $("#tblRecibo").DataTable({
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
          data: "id"
         
        },
        {
          data: "codigo_pago"
        },
        {
          data: "razon_social"
        },
        {
          data: "ruc"
        },
        {
          data: "fecha_pago"
        },
        {
          data: "monto"
        },
        {
          data: "estado",
          render: function(data) {
            if (data == 1) {
              return '<span class="badge badge-success">Pagado</span>';
            } else {
              return '<span class="badge badge-warning">Pendiente</span>';
            }
          }
        },
        {
          data: "id",
          render: function(data) {
            return `<button class="btn btn-info btn-sm" onclick="verPago(${data})" title="Ver"><i class="fa fa-eye"></i></button>`;
          }
        }
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
});
//Registro de Empresa


document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("frmEmpresa")
    .addEventListener("submit", registrarEmpresa);
});

function frmBusquedaEmpresas() {
  document.getElementById("btnAccion").textContent = "Buscar";
  document.getElementById("frmBusquedaEmpresas").reset();
  document.getElementById("id").value = "";
}

function buscarEmpresas(e) {
  e.preventDefault();

  const frm = document.getElementById("frmBusquedaEmpresas");

  // Validación nativa HTML5 con estilo Bootstrap
  if (!frm.checkValidity()) {
    frm.classList.add("was-validated");
    return;
  }

  const tipo = document.getElementById("tipoBusqueda").value;
  const valor = document.getElementById("valorBusqueda").value.trim();

  const nuevaUrl = `${base_url}Empresas/buscarEmpresa?tipo=${tipo}&valor=${valor}`;

  if ($.fn.DataTable.isDataTable("#tblEmpresas")) {
    $("#tblEmpresas").DataTable().destroy();
  }

  $("#tblEmpresas").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    searching: false,
    lengthChange: false,
    ajax: {
      url: nuevaUrl,
      dataSrc: function (json) {
        if (json.length === 0) {
          Swal.fire("Advertencia", "Datos no encontrados", "warning");
        }
        return json;
      },
    },
    columns: [
      { data: "id" },
      { data: "razon_social" },
      { data: "ruc" },
      { data: "direccion" },
      { data: "telefono" },
      {
        data: "estado",
        render: function (data) {
          return data == 1
            ? '<span class="badge badge-info">ACTIVO</span>'
            : '<span class="badge badge-danger">INACTIVO</span>';
        },
      },
      { data: "fecha_vigencia" },
      {
        data: "id",
        render: function (data) {
          return `<button class="btn btn-info btn-sm" onclick="verEmpresa(${data})" title="Ver"><i class="fa fa-eye"></i></button>`;
        },
      },
    ],
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
    order: [[0, "desc"]],
    iDisplayLength: 10,
    bDestroy: true,
  });
}

function verEmpresa(id) {
  const url = base_url + "Empresas/obtenerEmpresa/" + id;

  fetch(url)
    .then((res) => res.json())
    .then((data) => {
      if (data) {
        // Llenar los campos del formulario con los datos obtenidos
        document.getElementById("id").value = data.id;
        document.getElementById("razon_social").value = data.razon_social;
        document.getElementById("ruc").value = data.ruc;
        document.getElementById("direccion").value = data.direccion;
        document.getElementById("telefono").value = data.telefono;
        document.getElementById("fecha_vigencia").value = data.fecha_vigencia;
        document.getElementById("email").value = data.correo;
        // Desactivar los campos para solo visualización (opcional)
        document.querySelectorAll("#frmEmpresa input").forEach((input) => {
          input.setAttribute("readonly", "true");
        });

        // Cambiar el texto del botón o deshabilitarlo

        // Mostrar el modal
        $("#modalEmpresa").modal("show");
      } else {
        Swal.fire("Error", "No se encontró la empresa", "error");
      }
    })
    .catch((error) => {
      console.error("Error al obtener empresa:", error);
      Swal.fire("Error", "Ocurrió un error al obtener los datos", "error");
    });
}

//Registro de Pago

function frmPago() {
  document.getElementById("btnAccion").textContent = "Registrar";
  document.getElementById("frmPago").reset();
  document.getElementById("id").value = "";
}

function llenarDatosServicio() {
  const codigo = document.getElementById("codigo_servicio").value;
  let descripcion = "";
  let monto = "";

  if (codigo === "110") {
    descripcion = "Registro de conductor";
    monto = 37.5;
  } else if (codigo === "120") {
    descripcion = "Baja de nómina";
    monto = 100;
  }

  document.getElementById("descripcion").value = descripcion;
  document.getElementById("monto").value = monto;
}

function buscarRuc() {
  const ruc = document.getElementById("ruc").value.trim();

  if (ruc === "") {
    Swal.fire("Advertencia", "Ingrese el RUC", "warning");
    return;
  }

  const url = base_url + "Pago/buscarRuc/" + ruc;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();

  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const data = JSON.parse(this.responseText);
      if (data) {
        // RUC encontrado, se llenan los campos automáticamente
        document.getElementById("empresa").value = data.razon_social;
        document.getElementById("id_empresa").value = data.id;
      } else {
        // RUC no encontrado
        Swal.fire("Advertencia", "Empresa no encontrada", "warning");
        document.getElementById("empresa").value = "";
        document.getElementById("id_empresa").value = "";
      }
    }
  };
}
// Buscar  Pago

function frmBusquedaRecibos() {
  document.getElementById("btnAccion").textContent = "Buscar";
  document.getElementById("frmBusquedaRecibos").reset();
  document.getElementById("id").value = "";
}
function buscarRecibo(e) {
  e.preventDefault();

  const frm = document.getElementById("frmBusquedaRecibos");

  // Validación nativa HTML5 + Bootstrap
  if (!frm.checkValidity()) {
    frm.classList.add("was-validated");
    return;
  }

  const tipo = document.getElementById("tipoBusqueda").value;
  const valor = document.getElementById("valorBusqueda").value.trim();

  // Validación adicional para RUC (11 dígitos)
  if (tipo === "ruc" && valor.length !== 11) {
    Swal.fire({
      icon: "error",
      title: "Búsqueda incorrecta",
      html: "<strong>El RUC debe tener 11 dígitos</strong>",
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true,
      background: "#ffffff",
      color: "#721c24",
      iconColor: "#dc3545",
    });
    return;
  }

  document.getElementById("spinner-pagos").style.display = "block";
  const nuevaUrl = `${base_url}Pagos/buscarPagos?tipo=${tipo}&valor=${valor}`;

  $.ajax({
    url: nuevaUrl,
    method: "GET",
    dataType: "json",
    success: function (json) {
      document.getElementById("spinner-pagos").style.display = "none";

      if (json.length === 0) {
        Swal.fire({
          icon: "info",
          title: "Sin resultados",
          html: "<strong>No se encontraron datos para la búsqueda</strong>",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          background: "#ffffff",
          color: "#333",
          iconColor: "#17a2b8",
        });
      }

      if ($.fn.DataTable.isDataTable("#tblRecibo")) {
        tblRecibo.clear().rows.add(json).draw();
      } else {
        tblRecibo = $("#tblRecibo").DataTable({
          data: json,
          responsive: true,
          processing: true,
          pageLength: 25,
          searching: false,
          lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100],
          ],
          columns: [
            {
              data: "id",
              render: function (data) {
                return `<input type="checkbox" class="checkPago" value="${data}">`;
              },
            },
            { data: "codigo_pago" },
            { data: "razon_social" },
            { data: "ruc" },
            { data: "fecha_pago" },
            { data: "monto" },
            {
              data: "estado",
              render: function (data) {
                return data == 1
                  ? '<span class="badge badge-success">Pagado</span>'
                  : '<span class="badge badge-warning">Pendiente</span>';
              },
            },
            {
              data: "id",
              render: function (data) {
                return `<button class="btn btn-info btn-sm" onclick="verPago(${data})" title="Ver"><i class="fa fa-eye"></i></button>`;
              },
            },
          ],
          language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
          },
        });
      }
    },
    error: function () {
      document.getElementById("spinner-pagos").style.display = "none";
      Swal.fire({
        icon: "error",
        title: "Error",
        html: "<strong>No se pudo realizar la búsqueda</strong>",
        showConfirmButton: true,
        background: "#ffffff",
        color: "#721c24",
        iconColor: "#dc3545",
      });
    },
  });
}

// Validacion de Pago
function frmValidarpago() {
  document.getElementById("btnAccion").textContent = "Validar";
  document.getElementById("frmValidarpago").reset();
  document.getElementById("id").value = "";
}
function registrarValidacion(e) {
  e.preventDefault();

  const form = document.getElementById("frmValidarpago");
  form.classList.add("was-validated");

  if (!form.checkValidity()) {
    return;
  }

  const checks = document.querySelectorAll(".checkPago:checked");
  const idsSeleccionados = Array.from(checks).map((chk) => chk.value);
  if (idsSeleccionados.length === 0) {
    Swal.fire(
      "Advertencia",
      "Debes seleccionar al menos un pago para validar",
      "warning"
    );
    return;
  }

  const idEmpresa = document.getElementById("id_empresa").value;
  if (!idEmpresa) {
    Swal.fire(
      "Advertencia",
      "Debe buscar y seleccionar una empresa válida",
      "warning"
    );
    return;
  }

  document.getElementById("ids_pagos").value = idsSeleccionados.join(",");

  const url = base_url + "Pago/registrarValidacion";
  const formData = new FormData(form);

  const http = new XMLHttpRequest();
  http.open("POST", url, true);

  http.onreadystatechange = function () {
    if (this.readyState === 4) {
      if (this.status === 200) {
        try {
          const res = JSON.parse(this.responseText);
          Swal.fire({
            icon: res.icono,
            title:
              res.icono === "success"
                ? "¡Éxito!"
                : res.icono === "warning"
                ? "Atención"
                : "Error",
            html: `<strong>${res.msg}</strong>`,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            background: "#f4f6f9",
            color: "#333",
            iconColor:
              res.icono === "success"
                ? "#28a745"
                : res.icono === "warning"
                ? "#ffc107"
                : "#dc3545",
            customClass: {
              popup: "swal-custom-popup",
              title: "swal-custom-title",
              htmlContainer: "swal-custom-html",
            },
          });

          if (res.icono === "success") {
            setTimeout(() => {
              form.reset();
              form.classList.remove("was-validated");
              form
                .querySelectorAll(".is-valid, .is-invalid")
                .forEach((el) => el.classList.remove("is-valid", "is-invalid"));

              if (typeof tblPagos !== "undefined") {
                tblPagos.ajax.reload();
              }
            }, 2000);
          }
        } catch (error) {
          console.error(error);
          Swal.fire({
            icon: "error",
            title: "Error inesperado",
            text: "No se pudo procesar la respuesta del servidor",
            background: "#f8d7da",
            color: "#721c24",
          });
        }
      } else {
        Swal.fire({
          icon: "error",
          title: "Error de servidor",
          text: "No se pudo completar la solicitud",
          background: "#f8d7da",
          color: "#721c24",
        });
      }
    }
  };

  http.send(formData);
}

function buscarRucTabla() {
  const ruc = document.getElementById("ruc").value;
  if (ruc === "") {
    Swal.fire("Advertencia", "Ingrese un RUC válido", "warning");
    return;
  }

  const url = base_url + "Pago/buscarEmpresa";
  const formData = new FormData();
  formData.append("ruc", ruc);

  fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((res) => {
      console.log("Respuesta cruda:", res); // 👈 Muestra si la respuesta fue correcta
      if (!res.ok) {
        throw new Error("Error en la respuesta del servidor");
      }
      return res.json(); // 👈 Intenta convertir a JSON
    })
    .then((data) => {
      console.log("Respuesta JSON:", data); // 👈 Muestra el contenido recibido
      if (data && data.id) {
        document.getElementById("id_empresa").value = data.id;

        // Destruye y vuelve a cargar DataTable
        $("#tblPagos").DataTable().destroy();
        tblPagos = $("#tblPagos").DataTable({
          responsive: true,
          processing: true,
          serverSide: false,
          pageLength: 25,
          lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100],
          ],
          searching: false,
          ajax: {
            url: base_url + "Pago/listarPorEmpresa",
            type: "POST", // Usa type, no method aquí
            data: function (d) {
              d.id_empresa = data.id; // Enviar ID correctamente
            },
            dataSrc: "",
            error: function (xhr, error, thrown) {
              console.error("Error en DataTable AJAX:", xhr.responseText);
              Swal.fire(
                "Error",
                "No se pudo cargar la tabla de pagos",
                "error"
              );
            },
          },
          columns: [
            {
              data: "id",
              render: function (data) {
                return `<input type="checkbox" class="checkPago" value="${data}">`;
              },
              width: "30px",
            },
            {
              data: "estado_pago",
              render: function (data) {
                if (data == 1) {
                  return '<span class="badge badge-info">Activo</span>';
                } else {
                  return '<span class="badge badge-warning">Pagado</span>';
                }
              },
              width: "60px",
            },
            {
              data: "codigo_pago",
              width: "50px",
            },
            {
              data: "razon_social",
              render: function (data) {
                return `<span class="text-truncate-tooltip" data-toggle="tooltip" title="${data}">${data}</span>`;
              },
            },

            { data: "monto" },
            { data: "fecha_pago" },
            {
              data: "id",
              render: function (data, type, row) {
                return `<button type="button" onclick="verPago(${data})" >
              <i class="fa fa-eye"></i>
            </button>`;
              },
            },
          ],
          language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
          },

          responsive: true,
          bDestroy: true,
          iDisplayLength: 10,
          order: [[0, "desc"]],

          dom:
            "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        });
      } else {
        Swal.fire("No encontrado", "Empresa no encontrada", "error");
      }
    })
    .catch((error) => {
      console.error("Error en fetch:", error); // 👈 Muestra cualquier error que haya ocurrido
      Swal.fire("Error", "Ocurrió un error al buscar el RUC", "error");
    });
}

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();

  // O si estás usando DataTables con AJAX:
  $("#tblPagos").on("draw.dt", function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
});

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

//Registro de Conductor

function buscarExpediente() {
  const expediente = document.getElementById("expediente").value.trim();
  if (expediente === "") {
    Swal.fire("Advertencia", "Ingrese el número de expediente", "warning");
    return;
  }

  const url = base_url + "Conductor/buscarExpediente/" + expediente;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();

  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const data = JSON.parse(this.responseText);
      if (data && data.n_conductores > 0) {
        document.getElementById("ruc").value = data.ruc;
        document.getElementById("id_empresa").value = data.id_empresa;
        document.getElementById("id_validacion").value = data.id_vpago;
        document.getElementById("id_pago").value = data.id_pago;
        document.getElementById("n_conductores").value = data.n_conductores;

        // 👉 Cambiar el título por la razón social
        document.getElementById("tituloRegistro").textContent = data.razon_social;

      } else {
        Swal.fire("Advertencia", "Expediente inválido o sin cupo", "warning");
        document.getElementById("ruc").value = "";
        document.getElementById("id_empresa").value = "";
        document.getElementById("n_conductores").value = "";
        document.getElementById("id_validacion").value = "";
        document.getElementById("id_pago").value = "";

        // 👉 Volver al título original
        document.getElementById("tituloRegistro").textContent = "Registro de Conductores";
      }
    }
  };
}


function buscarDocumento() {
  const idEmpresa = document.getElementById("id_empresa").value;
  if (!idEmpresa) {
    Swal.fire(
      "Advertencia",
      "Primero debe ingresar y buscar el número de expediente",
      "warning"
    );
    return;
  }

  const tipo = document.getElementById("tipo_documento").value;
  const input = document.getElementById("numero_documento");
  const valor = input.value.trim();
  let regex;

  // Validación según tipo de documento
  if (tipo === "DNI") {
    regex = /^\d{8}$/;
  } else if (tipo === "CE") {
    regex = /^\d{9,11}$/;
  }

  // Limpia estilos previos
  input.classList.remove("is-valid", "is-invalid");
  input.setCustomValidity("");

  if (valor === "") return;

  if (regex.test(valor)) {
    input.classList.add("is-valid");
  } else {
    input.classList.add("is-invalid");
    const mensaje =
      tipo === "DNI"
        ? "El DNI debe tener exactamente 8 dígitos numéricos."
        : "El CE debe tener entre 9 y 11 dígitos numéricos.";
    input.setCustomValidity(mensaje);
    return; // ⛔ Salir si no cumple la validación
  }

  const numero = input.value;

  if (tipo === "" || numero === "") {
    limpiarCamposConductor();
    return;
  }

  const archivo = document.getElementById("foto").files[0];
  if (archivo && archivo.size > 2 * 1024 * 1024) {
    Swal.fire({
      icon: "error",
      title: "Imagen demasiado grande",
      html: "<strong>La imagen no debe superar los 2MB</strong>",
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true,
      background: "#f8d7da",
      color: "#721c24",
      iconColor: "#dc3545",
    });
    return;
  }

  const url = base_url + "Conductor/buscarDocumento";
  const formData = new FormData();
  formData.append("tipo", tipo);
  formData.append("numero", numero);

  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const data = JSON.parse(this.responseText);
      const fotoPreview = document.getElementById("foto-preview");

      if (data) {
        document.getElementById("id_record").value = data.id;
        document.getElementById("nombres").value = data.nombre.toUpperCase();
      document.getElementById("apellido_paterno").value = data.apellido_paterno.toUpperCase();
document.getElementById("apellido_materno").value = data.apellido_materno.toUpperCase();
        document.getElementById("licencia").value = data.licencia_conducir;
        document.getElementById("categoria").value = data.categoria;
        document.getElementById("fecha_expedicion").value =
          data.fecha_expedicion;
        document.getElementById("fecha_vencimiento").value =
          data.fecha_vencimiento;
        document.getElementById("estado_licencia").value = data.estado;

        if (data.foto && data.foto !== "") {
          fotoPreview.src = base_url + "Assets/img/conductores/" + data.foto;
          const hiddenFoto = document.getElementById("foto_oculta");
          if (hiddenFoto) hiddenFoto.value = data.foto;
        } else {
          fotoPreview.src = base_url + "assets/img/silueta.jpg";
        }

        Swal.fire({
          icon: "success",
          title: "¡Conductor encontrado!",
          html: "<strong>Datos cargados correctamente</strong>",
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
          background: "#f4f6f9",
          color: "#333",
          iconColor: "#28a745",
        });
      } else {
        if (fotoPreview) {
          fotoPreview.src = base_url + "assets/img/silueta.jpg";
        }

        Swal.fire({
          icon: "info",
          title: "Conductor no encontrado",
          html: "<strong>Verifique el tipo y número de documento ingresado</strong>",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          background: "#e9ecef",
          color: "#333",
          iconColor: "#17a2b8",
        });

        limpiarCamposConductor();
      }
    }
  };
  http.send(formData);
}

// Función para limpiar los campos del formulario del conductor
function limpiarCamposConductor() {
  document.getElementById("id_record").value = "";
  document.getElementById("nombres").value = "";
  document.getElementById("apellido_paterno").value = "";
  document.getElementById("apellido_materno").value = "";
  document.getElementById("licencia").value = "";
  document.getElementById("categoria").value = "";
  document.getElementById("fecha_expedicion").value = "";
  document.getElementById("fecha_vencimiento").value = "";
  document.getElementById("estado_licencia").value = "";
}

function registrarConductor(e) {
  e.preventDefault();

  const form = document.getElementById("frmConductor");

  // Activar validación de Bootstrap
  form.classList.add("was-validated");

  // Validación HTML5 integrada
  if (!form.checkValidity()) {
    // Si algún campo no es válido, detener ejecución
    return;
  }

  // Validación personalizada adicional
  const expediente = document.getElementById("expediente").value;
  const ruc = document.getElementById("ruc").value;
  const n_conductores = parseInt(
    document.getElementById("n_conductores").value || 0
  );

  if (!expediente || !ruc || n_conductores <= 0) {
    Swal.fire({
      icon: "error",
      title: "Error",
      html: "<strong>Debe validar un expediente válido con cupo disponible</strong>",
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true,
      background: "#f4f6f9",
      color: "#333",
      iconColor: "#dc3545",
      customClass: {
        popup: "swal-custom-popup",
        title: "swal-custom-title",
        htmlContainer: "swal-custom-html",
      },
    });
    return;
  }

  // Si todo es válido, enviamos
  const formData = new FormData(form);
  const url = base_url + "Conductor/registrar";

  const http = new XMLHttpRequest();
  http.open("POST", url, true);

  http.onreadystatechange = function () {
    if (this.readyState === 4) {
      if (this.status === 200) {
        try {
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

          if (res.status) {
            setTimeout(() => {
              location.reload();
            }, 2500);
          }
        } catch (error) {
          console.error("Error al parsear JSON:", error);
          Swal.fire({
            icon: "error",
            title: "Error inesperado",
            html: "<strong>La respuesta del servidor no es válida</strong>",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            background: "#f8d7da",
            color: "#721c24",
            iconColor: "#dc3545",
          });
        }
      } else {
        Swal.fire({
          icon: "error",
          title: "Error del servidor",
          html: "<strong>No se pudo completar la solicitud</strong>",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          background: "#f8d7da",
          color: "#721c24",
          iconColor: "#dc3545",
        });
      }
    }
  };

  http.send(formData);
}
//Listar Conductores

//Usuarios
function frmUsuario() {
  document.getElementById("title").textContent = "Nuevo Usuario";

  document.getElementById("claves").classList.remove("d-none");
  document.getElementById("frmUsuario").reset();
  document.getElementById("id").value = "";
  $("#nuevo_usuario").modal("show");
}

function registrarUsuario(e) {
  e.preventDefault();

  const frm = document.getElementById("frmUsuario");

  // Validación visual Bootstrap
  if (!frm.checkValidity()) {
    frm.classList.add("was-validated");
    return;
  }

  const url = base_url + "Usuarios/registrarUsers";
  const formData = new FormData(frm);

  const http = new XMLHttpRequest();
  http.open("POST", url, true);

  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      try {
        const res = JSON.parse(this.responseText);

        Swal.fire({
          icon: res.icono, // success, warning, error
          title:
            res.icono === "success"
              ? "¡Éxito!"
              : res.icono === "warning"
              ? "Atención"
              : "Error",
          html: `<strong>${res.msg}</strong>`,
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
          background: "#f4f6f9",
          color: "#333",
          iconColor:
            res.icono === "success"
              ? "#28a745"
              : res.icono === "warning"
              ? "#ffc107"
              : "#dc3545",
          customClass: {
            popup: "swal-custom-popup",
            title: "swal-custom-title",
            htmlContainer: "swal-custom-html",
          },
        });

        if (res.icono === "success") {
          setTimeout(() => {
            frm.reset();
            frm.classList.remove("was-validated");
            $("#nuevo_usuario").modal("hide");
            if (typeof tblUsuarios !== "undefined") {
              tblUsuarios.ajax.reload();
            }
          }, 2500);
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error inesperado",
          text: "No se pudo procesar la respuesta del servidor",
          background: "#f8d7da",
          color: "#721c24",
        });
        console.error(error);
      }
    } else if (this.readyState === 4) {
      Swal.fire({
        icon: "error",
        title: "Error de servidor",
        text: "No se pudo completar la solicitud",
        background: "#f8d7da",
        color: "#721c24",
      });
    }
  };

  http.send(formData);
}

function btnEditarUser(id) {
  document.getElementById("title").textContent = "Actualizar usuario";
  document.getElementById("btnAccion").textContent = "Modificar";

  const url = base_url + "Usuarios/editar/" + id;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();

  http.onreadystatechange = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        try {
          const res = JSON.parse(this.responseText);
          console.log("Respuesta del servidor:", res);

          document.getElementById("id").value = res.id;
          document.getElementById("usuarios").value = res.usuario;
          document.getElementById("dni").value = res.dni;
          document.getElementById("nombre").value = res.nombre;
          document.getElementById("apellido_paterno").value =
            res.apellido_paterno;
          document.getElementById("apellido_materno").value =
            res.apellido_materno;
          document.getElementById("correo").value = res.correo;
          document.getElementById("telefono").value = res.telefono;
          document.getElementById("direccion").value = res.direccion;
          document.getElementById("rol").value = res.rol;

          if (document.getElementById("area")) {
            document.getElementById("area").value = res.area;
          }
          if (document.getElementById("fecha")) {
            document.getElementById("fecha").value = res.fecha;
          }

          document.getElementById("grupo_claves").classList.add("d-none");
          document.getElementById("grupo_confirmar").classList.add("d-none");
          document.getElementById("claves").required = false;
          document.getElementById("confirmar").required = false;

          $("#nuevo_usuario").modal("show");
        } catch (error) {
          console.error("Error al procesar la respuesta JSON:", error);
          console.log("Respuesta recibida:", this.responseText);
        }
      } else {
        console.error("Error en la solicitud. Código de estado:", this.status);
        console.log("Respuesta recibida:", this.responseText);
      }
    }
  };
}

function btnEliminarUser(id) {
  Swal.fire({
    title: "¿Estás seguro de eliminar?",
    text: "El usuario no se eliminará permanentemente, solo cambiará a estado inactivo.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Usuarios/eliminar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          tblUsuarios.ajax.reload();
          Swal.fire({
            title: res.icono === "success" ? "Éxito" : "Error",
            text: res.msg,
            icon: res.icono,
          });
        }
      };
    }
  });
}

function btnReingresarUser(id) {
  Swal.fire({
    title: "¿Estás seguro de reingresar?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = base_url + "Usuarios/reingresar/" + id;
      const http = new XMLHttpRequest();
      http.open("GET", url, true);
      http.send();
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const res = JSON.parse(this.responseText);
          tblUsuarios.ajax.reload();
          Swal.fire({
            title: res.icono === "success" ? "Éxito" : "Error",
            text: res.msg,
            icon: res.icono,
          });
        }
      };
    }
  });
}

//Fin Usuarios

function btnRolesUser(id) {
  const http = new XMLHttpRequest();
  const url = base_url + "Usuarios/permisos/" + id;
  http.open("GET", url);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("frmPermisos").innerHTML = this.responseText;
      $("#permisos").modal("show");
    }
  };
}

function registrarPermisos(e) {
  e.preventDefault();
  const http = new XMLHttpRequest();
  const frm = document.getElementById("frmPermisos");
  const url = base_url + "Usuarios/registrarPermisos";
  http.open("POST", url);
  http.send(new FormData(frm));
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      try {
        const res = JSON.parse(this.responseText);
        $("#permisos").modal("hide");

        if (res == "ok") {
          Swal.fire({
            icon: "success",
            title: "¡Éxito!",
            html: "<strong>Permisos asignados correctamente</strong>",
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            background: "#f4f6f9",
            color: "#333",
            iconColor: "#28a745",
            customClass: {
              popup: "swal-custom-popup",
              title: "swal-custom-title",
              htmlContainer: "swal-custom-html",
            },
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error al asignar permisos",
            html: `<strong>${res.msg}</strong>`,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: "#f8d7da",
            color: "#721c24",
            iconColor: "#dc3545",
            customClass: {
              popup: "swal-custom-popup",
              title: "swal-custom-title",
              htmlContainer: "swal-custom-html",
            },
          });
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error inesperado",
          html: "<strong>No se pudo procesar la respuesta del servidor</strong>",
          background: "#f8d7da",
          color: "#721c24",
          iconColor: "#dc3545",
        });
        console.error(error);
      }
    }
  };
}

function modificarClave(e) {
  e.preventDefault();

  const clave_actual = document.querySelector("#clave_actual").value;
  const nueva_clave = document.querySelector("#clave_nueva").value;
  const confirmar_clave = document.querySelector("#clave_confirmar").value;
  const formClave = document.querySelector("#frmCambiarPass");

  if (clave_actual === "" || nueva_clave === "" || confirmar_clave === "") {
    Swal.fire({
      icon: "warning",
      title: "Advertencia",
      html: "<strong>Todos los campos son requeridos</strong>",
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true,
      background: "#f4f6f9",
      color: "#333",
      iconColor: "#ffc107",
      customClass: {
        popup: "swal-custom-popup",
        title: "swal-custom-title",
        htmlContainer: "swal-custom-html",
      },
    });
  } else if (nueva_clave !== confirmar_clave) {
    Swal.fire({
      icon: "warning",
      title: "Advertencia",
      html: "<strong>Las contraseñas no coinciden</strong>",
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true,
      background: "#f4f6f9",
      color: "#333",
      iconColor: "#ffc107",
      customClass: {
        popup: "swal-custom-popup",
        title: "swal-custom-title",
        htmlContainer: "swal-custom-html",
      },
    });
  } else {
    const http = new XMLHttpRequest();
    const url = base_url + "Usuarios/cambiarPas";
    http.open("POST", url, true);
    http.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
        try {
          const res = JSON.parse(this.responseText);

          Swal.fire({
            icon: res.icono,
            title: res.icono === "success" ? "¡Éxito!" : "Error",
            html: `<strong>${res.msg}</strong>`,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            background: "#f4f6f9",
            color: "#333",
            iconColor:
              res.icono === "success"
                ? "#28a745"
                : res.icono === "warning"
                ? "#ffc107"
                : "#dc3545",
            customClass: {
              popup: "swal-custom-popup",
              title: "swal-custom-title",
              htmlContainer: "swal-custom-html",
            },
          });

          if (res.icono === "success") {
            formClave.reset();
            setTimeout(() => {
              $("#cambiarClave").modal("hide");
            }, 2500);
          }
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Error inesperado",
            html: "<strong>No se pudo procesar la respuesta del servidor</strong>",
            background: "#f8d7da",
            color: "#721c24",
          });
          console.error(error);
        }
      }
    };
    http.send(new FormData(formClave));
  }
}

if (document.getElementById("reportePrestamo")) {
  const url = base_url + "Configuracion/grafico";
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const data = JSON.parse(this.responseText);
      let nombre = [];
      let cantidad = [];
      for (let i = 0; i < data.length; i++) {
        nombre.push(data[i]["titulo"]);
        cantidad.push(data[i]["cantidad"]);
      }
      var ctx = document.getElementById("reportePrestamo");
      var myPieChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: nombre,
          datasets: [
            {
              label: "Libros",
              data: cantidad,
              backgroundColor: ["#dc143c"],
            },
          ],
        },
      });
    }
  };
}
function alertas(msg, icono) {
  Swal.fire({
    position: "top-end",
    icon: icono,
    title: msg,
    showConfirmButton: false,
    timer: 3000,
  });
}

(function () {
  "use strict";
  var forms = document.querySelectorAll(".needs-validation");

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
})();
document.getElementById("dni").addEventListener("input", function () {
  this.value = this.value.replace(/[^0-9]/g, "").slice(0, 9);
});

document.getElementById("telefono").addEventListener("input", function () {
  this.value = this.value.replace(/[^0-9]/g, "").slice(0, 9);
});

document.getElementById("ruc").addEventListener("input", function () {
  this.value = this.value.replace(/[^0-9]/g, "").slice(0, 11);
});
