let tblUsuarios,
  tblConductor,
  tblClientes,
  tblMarcas,
  tblTipos,
  tblAlquiler,
  tblVehiculos,
  tblInstructor,
  t_moneda,
  t_combustible,
  myModal,
  detalleModal,
  detalleModalVehiculo,
  detalleModalCliente,
  detalleModalAlquiler,
  tbl,
  tblDoc,
  m_entrega;
document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("entrega")) {
    m_entrega = new bootstrap.Modal(document.getElementById("entrega"));
  }
  //fin validaciones
  let tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
  if (document.getElementById("myModal")) {
    myModal = new bootstrap.Modal(document.getElementById("myModal"));
  }
  if (document.getElementById("detalleModal")) {
    detalleModal = new bootstrap.Modal(document.getElementById("detalleModal"));
  }
  if (document.getElementById("detalleModalVehiculo")) {
    detalleModalVehiculo = new bootstrap.Modal(
      document.getElementById("detalleModalVehiculo")
    );
  }
  if (document.getElementById("detalleModalCliente")) {
    detalleModalCliente = new bootstrap.Modal(
      document.getElementById("detalleModalCliente")
    );
  }
  if (document.getElementById("detalleModalAlquiler")) {
    detalleModalAlquiler = new bootstrap.Modal(
      document.getElementById("detalleModalAlquiler")
    );
  }

  //Fin autocomple
  const buttons = [
    {
      //Botón para Excel
      extend: "excelHtml5",
      footer: true,
      title: "Reporte",
      filename: "Reporte",
      //Aquí es donde generas el botón personalizado
      text: '<span class="badge bg-success"><i class="fas fa-file-excel"></i></span>',
    },
    //Botón para PDF
    {
      extend: "pdfHtml5",
      download: "open",
      footer: true,
      title: "Reporte",
      filename: "Reporte",
      text: '<span class="badge bg-danger"><i class="fas fa-file-pdf"></i></span>',
      exportOptions: {
        columns: [0, 1, 2, 3, 5],
      },
    },
    //Botón para PDF
    {
      extend: "copyHtml5",
      footer: true,
      title: "Reporte",
      filename: "Reporte",
      text: '<span class="badge bg-primary"><i class="fas fa-copy"></i></span>',
      exportOptions: {
        columns: [0, ":visible"],
      },
    },
    //Botón para print
    {
      extend: "print",
      footer: true,
      filename: "Reporte",
      text: '<span class="badge bg-warning"><i class="fas fa-print"></i></span>',
    },
    //Botón para print
    {
      extend: "csvHtml5",
      footer: true,
      filename: "Reporte",
      text: '<span class="badge bg-success"><i class="fas fa-file-csv"></i></span>',
    },
    {
      extend: "colvis",
      text: '<span class="badge bg-info"><i class="fas fa-columns"></i></span>',
      postfixButtons: ["colvisRestore"],
    },
  ];
  const dom =
    "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-5'i><'col-sm-7'p>>";

  tblTipos = $("#tblaTipos").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: base_url + "Tipos/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "tipo" },
      { data: "estado" },
      { data: "editar" },
      { data: "eliminar" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom,
    buttons,
    resonsieve: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });
  tblConductor = $("#tblConductor").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: base_url + "Conductor/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "razon_social" },
      { data: "dni" },
      { data: "conductor" },
      { data: "categoria" },
      { data: "licencia" },
      { data: "expidicion" }, // o "expedicion"
      { data: "revalidacion" },
      { data: "estado" },
      { data: "estado_licencia" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    buttons: buttons,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });

  tblUsuarios = $("#tblUsuarios").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: base_url + "Usuarios/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "usuario" },
      { data: "empresa" },
      { data: "ruc" },
      { data: "correo" },
      { data: "estado" },
      { data: "editar" },
      { data: "eliminar" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom,
    buttons,
    resonsieve: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  }); //Fin de la tabla usuarios
  t_combustible = $("#t_combustible").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: "" + base_url + "Combustible/listar",
      dataSrc: "",
    },
    columns: [
      {
        data: "id",
      },

      {
        data: "nombre",
      },

      {
        data: "precio_combustible",
      },
      {
        data: "estado",
      },
      {
        data: "editar",
      },
      {
        data: "eliminar",
      },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom,
    buttons,
    resonsieve: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });

  t_moneda = $("#t_moneda").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: "" + base_url + "Administracion/listarMonedas",
      dataSrc: "",
    },
    columns: [
      {
        data: "id",
      },

      {
        data: "nombre",
      },

      {
        data: "monto_pista",
      },
      {
        data: "estado",
      },
      {
        data: "editar",
      },
      {
        data: "eliminar",
      },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom,
    buttons,
    resonsieve: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });

  tblClientes = $("#tblClientes").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: base_url + "Clientes/listar",
      dataSrc: function (json) {
        return json.data;
      },
    },
    columns: [
      { data: "id" },
      { data: "dni" },
      { data: "nombre_cliente" },
      { data: "apellido" },
      { data: "telefono" },
      { data: "direccion" },
      { data: "empresa" }, // Columna a ocultar
      { data: "estado" },
      { data: "accion" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom,
    createdRow: function (row, data, index) {
      //pintar una celda
      if (data.estado == 0) {
        $("td", row).eq(12).html('<span class="badge bg-dark">INACTIVO</span>');
      } else {
        $("td", row)
          .eq(12)
          .html('<span class="badge bg-success">Activo</span>');
      }
      if (data.estado == 2) {
        $("td", row).css({
          "background-color": "#FEA4AE",
        });
        $("td", row).eq(14).html("");
        $("td", row).eq(15).html("");
      }
    },
    buttons,
    responsive: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
    initComplete: function (settings, json) {
      // Ocultar columna "empresa" si no es admin
      if (json.id_usuario != 1) {
        tblClientes.column(6).visible(false);
      }
    },
  });

  //Fin de la tabla clientes
  tblMarcas = $("#tblMarcas").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: base_url + "Marcas/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "marca" },
      { data: "estado" },
      { data: "editar" },
      { data: "eliminar" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom,
    buttons,
    resonsieve: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  }); //Fin de la tabla marcas

  tblInstructor = $("#tblainstructo").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: base_url + "Instructor/listar",
      dataSrc: "",
    },
    columns: [
      {
        data: "id",
      },
      { data: "dni" },
      { data: "nombre" },
      { data: "telefono" },
      { data: "direccion" },
      { data: "placa" },
      { data: "estado" },
      { data: "acciones" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom: dom, // Asegúrate de que 'dom' esté definido
    buttons: buttons, // Asegúrate de que 'buttons' esté definido
    createdRow: function (row, data, index) {
      // Pintar una celda
      if (data.estado == 2) {
        $("td", row)
          .eq(12)
          .html('<span class="badge bg-dark">Alquilado</span>');
      } else {
        $("td", row)
          .eq(12)
          .html('<span class="badge bg-success">activo</span>');
      }
      if (data.estado == 2) {
        $("td", row).css({
          "background-color": "#ffff52",
        });
        $("td", row).eq(14).html("");
        $("td", row).eq(15).html("");
      }
    },
    buttons,
    responsive: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  }); // Fin de vehiculos

  //Fin de vehiculos
  tblDoc = $("#tblDoc").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: base_url + "Documentos/listar",
      dataSrc: "",
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "documento",
      },
      {
        data: "estado",
      },
      {
        data: "editar",
      },
      {
        data: "eliminar",
      },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom,
    buttons,
    resonsieve: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  }); //Fin de la tabla documentos

  tbl = $("#tabla").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    pageLength: 25,
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

  tblVehiculos = $("#tblaVehiculos").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    pageLength: 25,
    ajax: {
      url: base_url + "Vehiculos/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "placa" },
      { data: "empresa" },
      { data: "fecha_citv" },
      { data: "fecha_soat" },
      { data: "fecha_seguro" },
      { data: "fecha_fabricacion" },
      { data: "estado" },
      { data: "acciones" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom: dom,
    createdRow: function (row, data, index) {
      // Aplicar clase para resaltar fila si está definida
      if (data.resaltar) {
        $(row).addClass(data.resaltar);
      }
    },
    buttons: buttons,
    responsive: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });

  tblAlquiler = $("#tblAlquiler").DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: base_url + "Alquiler/listar",
      dataSrc: "",
    },
    columns: [
      {
        data: "id",
      },
      { data: "documento" },
      { data: "nombre_cliente" }, //nombre de la tabla cliente
      { data: "categoria" },
      { data: "nombre" }, // nombre de la tabla instructor
      { data: "placa" },
      { data: "modelo" },
      { data: "tipo" },

      { data: "tipo_pago" },
      { data: "monto_vehiculo" },
      { data: "monto_pista" },
      { data: "fecha_prestamo" },
      { data: "hora_salida" },
      { data: "hora_entrada" },

      { data: "estatus" },
      { data: "recibir" },
      { data: "accion" },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
    },
    dom,
    buttons,
    createdRow: function (row, data, index) {
      //pintar una celda
      if (data.estado == 1) {
        $("td", row)
          .eq(14)
          .html('<span class="badge bg-dark">Alquilado</span>');
        $("td", row).css({
          "background-color": "#FEA4AE",
        });
      } else {
        $("td", row)
          .eq(14)
          .html('<span class="badge bg-success">Devuelto</span>');
      }
    },
    resonsieve: true,
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  }); //Fin de la tabla alquiler

  $("#select_instructor").autocomplete({
    minLength: 2,
    source: function (request, response) {
      $("#loading_indicator").show();
      $.ajax({
        url: base_url + "Instructor/buscarInstructor/", // URL de búsqueda de instructor
        dataType: "json",
        data: { ins: request.term }, // Asegúrate de enviar el parámetro correcto
        success: function (data) {
          console.log(data); // Verifica el contenido de la respuesta
          response(data);
          $("#loading_indicator").hide();
        },
        error: function () {
          $("#loading_indicator").hide();
          alert("Error al obtener los datos.");
        },
      });
    },
    select: function (event, ui) {
      if (ui.item.id) {
        $("#id_ins").val(ui.item.id);
        $("#select_instructor").val(ui.item.label); // Asigna el nombre
      } else {
        alert("ID del instructor no válido");
      }
      return false; // Evitar que jQuery autocomplete sobrescriba el valor del campo
    },
  });

  $("#select_cliente").autocomplete({
    minLength: 2,
    source: function (request, response) {
      $("#loading_indicator").show();
      $.ajax({
        url: base_url + "Clientes/buscarCliente/",
        dataType: "json",
        data: { cli: request.term },
        success: function (data) {
          response(data);
          $("#loading_indicator").hide();
        },
        error: function () {
          $("#loading_indicator").hide();
          alert("Error al obtener los datos.");
        },
      });
    },
    select: function (event, ui) {
      // Verifica que el ID sea válido antes de asignar
      if (ui.item.id) {
        $("#id_cli").val(ui.item.id); // Asigna el ID del cliente
      } else {
        alert("ID del cliente no válido");
      }
      $("#select_cliente").val(ui.item.label); // Asigna el nombre al campo de texto

      // Solicitar los montos de alquiler
      $.ajax({
        url: base_url + "Alquiler/obtenerMontos",
        dataType: "json",
        type: "POST",
        data: { id_cli: ui.item.id },
        success: function (data) {
          if (
            data.monto_vehiculo !== undefined &&
            data.monto_pista !== undefined
          ) {
            $("#monto_vehiculo").val(data.monto_vehiculo);
            $("#monto_pista").val(data.monto_pista);
          } else {
            alert("No se encontraron los montos para el cliente seleccionado.");
          }
        },
        error: function () {
          alert("Error al obtener los montos de alquiler.");
        },
      });
    },
  });
  $("#select_vehiculo").autocomplete({
    minLength: 2,
    source: function (request, response) {
      $.ajax({
        url: base_url + "Vehiculos/buscarVehiculo/",
        dataType: "json",
        data: {
          veh: request.term,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    select: function (event, ui) {
      document.getElementById("id_veh").value = ui.item.id;
      document.getElementById("select_vehiculo").value = ui.item.placa;
    },
  });
});

function frmEmpresa() {
 
  document.getElementById("btnAccion").textContent = "Registrar";

  document.getElementById("frmEmpresa").reset();
  document.getElementById("id_empresa").value = "";
  $("#nueva_empresa").modal("show");
}
function registrarEmpresa(e) {
  e.preventDefault();


  const razon_social = document.getElementById("razon_social");
  const direccion = document.getElementById("direccion");
  const telefono = document.getElementById("telefono");
  const autorizacion = document.getElementById("autorizacion");
  const ruc = document.getElementById("ruc");
  const correo = document.getElementById("correo"); // corregido
  const fecha_vigencia = document.getElementById("fecha_vigencia");
  const documento = document.getElementById("documento");

  const file = documento.files[0];
  if (file && !["application/pdf"].includes(file.type)) {
    alertas("Solo se permiten archivos PDF", "warning");
    return;
  }

  if (
    razon_social.value === "" ||
    direccion.value === "" ||
    telefono.value === "" ||
    autorizacion.value === "" ||
    ruc.value === "" ||
    correo.value === "" ||
    fecha_vigencia.value === "" ||
    documento.files.length === 0
  ) {
    alertas("Todos los campos son requeridos", "warning");
    return;
  } else {
    const url = base_url + "Empresa/registrar";
    const frm = document.getElementById("frmEmpresa");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
     http.send(formData);
    http.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
        const res = JSON.parse(this.responseText);
        alertas(res.msg, res.icono);
        // Recarga o cierra modal aquí si deseas
      }
    };
  
  }
}
