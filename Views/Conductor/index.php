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
<div class="container mb-4" style="max-width: 1500px;">

  <div class="bg-primary text-white p-4 mb-4 rounded shadow">
    <h4 class="mb-0" id="tituloRegistro">Registro de Conductores</h4>
    <small>Complete el formulario para registrar un nuevo conductor.</small>
  </div>

  <form id="frmConductor" class="needs-validation" onsubmit="registrarConductor(event)" autocomplete="off" enctype="multipart/form-data" novalidate>
    <div class="row">

      <!-- Lado izquierdo -->
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <div class="mb-3">
              <label for="expediente">Expediente</label>
              <div class="input-group has-validation">
                <input type="text" class="form-control" id="expediente" name="expediente" placeholder="Expediente" required>
                <button class="btn btn-secondary" type="button" onclick="buscarExpediente()">Buscar</button>
                <div class="invalid-feedback">Ingrese un expediente válido</div>
              </div>
            </div>

            <label for="ruc">RUC</label>
            <div class="input-group">
              <input type="text" class="form-control" id="ruc" name="ruc" placeholder="RUC" readonly>
              <input type="hidden" name="id_empresa" id="id_empresa">
              <input type="hidden" name="n_conductores" id="n_conductores">
              <input type="hidden" name="id_validacion" id="id_validacion">
              <input type="hidden" name="id_pago" id="id_pago">
              <input type="hidden" name="foto_oculta" id="foto_oculta">
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header text-center py-2" style="font-size: 14px;">
            Foto Conductor
          </div>
          <div class="card-body text-center p-2">
            <img src="<?php echo base_url; ?>assets/img/silueta.jpg"
              class="img-fluid mx-auto"
              id="foto-preview"
              style="max-height: 262px; object-fit: contain;">
            <input type="file" class="form-control form-control-sm mt-2 d-none" name="foto" id="foto" onchange="mostrarFoto(event)">
          </div>
        </div>
      </div>

      <!-- Lado derecho -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <span>Datos del Conductor</span>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- Columna izquierda -->
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="tipo_documento">Tipo de documento</label>
                  <select id="tipo_documento" name="tipo_documento" class="form-control" required onchange="actualizarValidacionDocumento(); buscarDocumento()">
                    <option value="" disabled selected>Seleccione Documento</option>
                    <option value="DNI">DNI</option>
                    <option value="CE">CE</option>
                  </select>
                </div>

                <div class="form-group mb-3">
                  <label for="numero_documento">Número de documento</label>
                  <div class="input-group has-validation">
                    <input type="text" class="form-control" name="numero_documento" id="numero_documento" placeholder="Número de documento" required pattern="\d*" oninput="this.value = this.value.replace(/\D/g, '')">
                    <input type="hidden" name="id_record" id="id_record">
                    <button class="btn btn-secondary" type="button" onclick="buscarDocumento()">Buscar</button>
                    <div class="invalid-feedback">Ingrese un documento válido</div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="nombres">Nombres</label>
                  <input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombres" required readonly>
                </div>

                <div class="form-group">
                  <label for="apellido_paterno">Apellido paterno</label>
                  <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno" placeholder="Apellido paterno" required readonly>
                </div>

                <div class="form-group">
                  <label for="apellido_materno">Apellido materno</label>
                  <input type="text" class="form-control" name="apellido_materno" id="apellido_materno" placeholder="Apellido materno" required readonly>
                </div>
              </div>

              <!-- Columna derecha -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="licencia">Licencia de conducir</label>
                  <input type="text" class="form-control" name="licencia" id="licencia" placeholder="N° Licencia" required readonly>
                </div>

                <div class="form-group">
                  <label for="categoria">Categoría</label>
                  <input type="text" class="form-control" name="categoria" id="categoria" placeholder="Ej: A1, A2" required readonly>
                </div>

                <div class="form-group">
                  <label for="fecha_expedicion">Fecha de expedición</label>
                  <input type="date" class="form-control" name="fecha_expedicion" id="fecha_expedicion" required readonly>
                </div>

                <div class="form-group">
                  <label for="fecha_vencimiento">Fecha de vencimiento</label>
                  <input type="date" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento" required readonly>
                </div>

                <div class="form-group">
                  <label for="estado_licencia">Estado de licencia</label>
                  <select class="form-control" id="estado_licencia" disabled>
                    <option value="vigente">Vigente</option>
                    <option value="vencido">Vencido</option>
                  </select>
                  <input type="hidden" name="estado_licencia" id="estado_licencia_hidden" value="vigente">
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
              Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
    function actualizarValidacionDocumento() {
        const tipo = document.getElementById("tipo_documento").value;
        const input = document.getElementById("numero_documento");

        if (tipo === "DNI") {
            input.maxLength = 8;
            input.placeholder = "8 dígitos";
        } else if (tipo === "CE") {
            input.maxLength = 11;
            input.placeholder = "Hasta 11 dígitos";
        }

        input.value = ""; // limpia el campo al cambiar tipo
        input.classList.remove("is-valid", "is-invalid");
        input.setCustomValidity("");
    }

    function mostrarFoto(event) {
        const archivo = event.target.files[0];
        if (archivo) {
            const lector = new FileReader();
            lector.onload = function(e) {
                document.getElementById('foto-preview').src = e.target.result;
            };
            lector.readAsDataURL(archivo);
        }
    }
</script>
<?php include "Views/Templates/footer.php"; ?>