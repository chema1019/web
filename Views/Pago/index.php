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
<div class="container mt-4">
    <div class="card">
        <div class="modal-header bg-primary text-white justify-content-between">
            <h5 class="mb-0">Registro de pago</h5>
        </div>

        <div class="card-body">
            <form id="frmPago" class="needs-validation" onsubmit="registrarPago(event)" autocomplete="off" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="id" id="id">

                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ruc">RUC</label>
                            <div class="input-group">
                                <input id="ruc" type="text" class="form-control" name="ruc" placeholder="RUC"
                                    pattern="^\d{11}$" required oninput="validarRuc()" inputmode="numeric">
                                <button class="btn btn-secondary" type="button" onclick="buscarRuc()">Buscar</button>
                                <div class="invalid-feedback">El RUC debe tener exactamente 11 dígitos numéricos.</div>
                            </div>
                            <input type="hidden" name="id_empresa" id="id_empresa">
                        </div>

                        <div class="form-group">
                            <label for="empresa">Nombre empresa</label>
                            <input type="text" name="empresa" id="empresa" class="form-control" placeholder="Nombre empresa" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="codigo_servicio">Código de servicio</label>
                            <select id="codigo_servicio" name="codigo_servicio" class="form-control" required onchange="llenarDatosServicio()">
                                <option value="" disabled selected>Seleccione Código</option>
                                <option value="110">110</option>
                                <option value="120">120</option>
                            </select>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Descripción" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="monto">Monto</label>
                            <input type="number" id="monto" name="monto" class="form-control" placeholder="Monto"
                                required step="0.01" min="0">
                            <div class="invalid-feedback">Ingrese un monto válido (mayor o igual a 0).</div>
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" id="fecha" name="fecha" class="form-control" required>
                            <div class="invalid-feedback">Seleccione una fecha válida.</div>
                        </div>

                        <div class="form-group">
                            <label for="recibo">Archivo de recibo</label>
                            <input type="file" id="recibo" name="recibo" class="form-control" required>
                            <div class="invalid-feedback">Debe subir un archivo de recibo.</div>
                        </div>
                    </div>
                </div>

                <!-- Botón -->
                <div class="text-end mt-3">
                    <button type="submit" id="btnAccion" class="btn btn-primary">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function frmPago() {
        document.getElementById("btnAccion").textContent = "";
        document.getElementById("frmPago").reset();
        document.getElementById("id").value = "";
    }

    function registrarPago(e) {
        e.preventDefault();
        const frm = document.getElementById("frmPago");
        const btnAccion = document.getElementById("btnAccion");

        // Validación nativa
        if (!frm.checkValidity()) {
            frm.classList.add("was-validated");
            return;
        }

        const recibo = document.getElementById("recibo");
        const file = recibo.files[0];

        if (file && file.type !== "application/pdf") {
            Swal.fire("Advertencia", "Solo se permiten archivos PDF", "warning");
            return;
        }

        if (document.getElementById("id_empresa").value === "") {
            Swal.fire("Advertencia", "Debe buscar una empresa válida", "warning");
            return;
        }

        btnAccion.disabled = true;

        const url = base_url + "Pago/registrar";
        const formData = new FormData(frm);

        const http = new XMLHttpRequest();
        http.open("POST", url, true);

        http.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                btnAccion.disabled = false;

                try {
                    const res = JSON.parse(this.responseText);

                    Swal.fire({
                        icon: res.icono,
                        title: res.icono === "success" ?
                            "¡Éxito!" :
                            res.icono === "warning" ?
                            "Atención" :
                            "Error",
                        html: `<strong>${res.msg}</strong>`,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        background: "#f4f6f9",
                        color: "#333",
                        iconColor: res.icono === "success" ?
                            "#28a745" :
                            res.icono === "warning" ?
                            "#ffc107" :
                            "#dc3545",
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
                            frm.querySelectorAll(".is-valid, .is-invalid").forEach((el) => {
                                el.classList.remove("is-valid", "is-invalid");
                            });

                            if (typeof tablaPagos !== "undefined") {
                                tablaPagos.ajax.reload();
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
                btnAccion.disabled = false;
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