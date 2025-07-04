<?php include "Views/Templates/header.php"; ?>

<style>
    /* Reducir el ancho del formulario de perfil */
    #frmDatos {
        flex: 1 1 650px;
        /* controla el crecimiento y el ancho deseado */
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    body {
        background-color: #f0f2f5;
    }

    .perfil-header {
        max-width: 1200px;
        margin: 20px auto 10px auto;
        /* antes era 40px */
        padding: 20px;
        background-color: #007bff;
        color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
    }

    .perfil-header i {
        font-size: 2.5rem;
        margin-right: 15px;
    }


    .perfil-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        max-width: 1200px;
        margin: 20px auto;
    }

    .sidebar-perfil {
        flex: 0 0 300px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        padding: 20px;
    }

    .sidebar-perfil img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #007bff;
        margin-bottom: 15px;
    }

    .sidebar-perfil h4 {
        font-weight: 700;
        margin-bottom: 5px;
    }

    .sidebar-perfil p {
        color: #666;
        margin-bottom: 10px;
    }

    .content-perfil {
        flex: 1;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
        padding: 15px;
        /* reducido de 30px */
        font-size: 0.9rem;
        /* tamaño de fuente más pequeño */
    }

    .content-perfil h5 {
        border-left: 4px solid #007bff;
        padding-left: 8px;
        font-weight: bold;
        margin-top: 0px;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .content-perfil label {
        font-weight: 500;
        font-size: 0.85rem;
    }

    .content-perfil .form-control {
        font-size: 0.9rem;
        padding: 0.375rem 0.6rem;
        /* reducir espacio interior */
        height: calc(1.8em + 0.75rem + 2px);
        /* más compacto */
    }


    .form-control {
        background-color: #f9f9f9;
        border: 1px solid #ccc;
    }

    .btn-guardar {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        font-size: 26px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }
</style>

<!-- Header perfil -->
<div class="perfil-header">
    <i class="fas fa-user-circle"></i>
    <div>
        <h4 class="mb-0">Perfil de Usuario</h4>
        <small>Complete el formulario con sus datos personales</small>
    </div>
</div>
<!-- Cuerpo del perfil -->
<div class="perfil-container"> 
    <!-- Sidebar -->
    <div class="sidebar-perfil text-center position-relative bg-white p-3 rounded shadow-sm">
        <div class="position-relative d-inline-block">
            <!-- Imagen de perfil -->
            <img src="<?php echo base_url . 'Assets/img/users/' . $data['perfil'] . '?t=' . time(); ?>"
                alt="Foto de perfil"
                class="rounded-circle shadow"
                style="width: 130px; height: 130px; object-fit: cover; border: 4px solid #007bff; transition: 0.3s ease;">

            <!-- Botón cambiar foto -->
            <a href="javascript:void(0);" onclick="document.getElementById('inputFoto').click();"
                class="position-absolute bg-warning text-dark d-flex justify-content-center align-items-center shadow-sm"
                title="Cambiar Foto"
                style="top: -5px; left: -5px; width: 28px; height: 28px; border-radius: 50%; font-size: 13px;">
                ✎
            </a>

            <!-- Botón eliminar foto -->
            <a href="javascript:void(0);" onclick="eliminarFoto()"
                class="position-absolute bg-danger text-white d-flex justify-content-center align-items-center shadow-sm"
                title="Eliminar Foto"
                style="top: -5px; right: -5px; width: 28px; height: 28px; border-radius: 50%; font-size: 13px;">
                ✖
            </a>

            <!-- Input oculto para cambiar foto -->
            <form id="formFoto" enctype="multipart/form-data" style="display: none;">
                <input type="file" name="foto" id="inputFoto" accept="image/*" onchange="subirFoto()">
            </form>
        </div>

        <!-- Datos del usuario -->
        <h5 class="mt-3 font-weight-bold text-primary mb-1"><?php echo $data['nombre']; ?></h5>
        <p class="text-muted mb-2"><?php echo $data['apellido_paterno'] . ' ' . $data['apellido_materno']; ?></p>

        <hr class="my-2">

        <div class="text-center text-muted" style="font-size: 15px;">
            <p class="mb-2"><?php echo $data['correo']; ?></p>
            <p class="mb-2"><?php echo $data['telefono']; ?></p>
            <p class="mb-0"><?php echo $data['direccion']; ?></p>
        </div>
    </div>

    <!-- Contenido editable -->
    <div class="content-perfil">
        <h5>Datos Personales</h5>
        <form id="frmDatos" class="needs-validation" autocomplete="off" enctype="multipart/form-data" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>DNI</label>
                    <input type="text" class="form-control" name="dni" placeholder="Ingrese DNI" required pattern="[0-9]{8}" maxlength="8" value="<?php echo $data['dni']; ?>">
                    <div class="invalid-feedback">El DNI debe tener 8 digitos.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nombres</label>
                    <input type="text" class="form-control" name="nombre" required maxlength="20" value="<?php echo $data['nombre']; ?>">
                    <div class="invalid-feedback">El nombre es obligatorio.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Apellido Paterno</label>
                    <input type="text" class="form-control" name="apellido_paterno" required maxlength="15" value="<?php echo $data['apellido_paterno']; ?>">
                    <div class="invalid-feedback">Ingrese el apellido paterno.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Apellido Materno</label>
                    <input type="text" class="form-control" name="apellido_materno" required maxlength="15" value="<?php echo $data['apellido_materno']; ?>">
                    <div class="invalid-feedback">Ingrese el apellido materno.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" name="telefono" required pattern="[0-9]{9}" maxlength="9" value="<?php echo $data['telefono']; ?>">
                    <div class="invalid-feedback">Ingrese un número de 9 dígitos.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Dirección</label>
                    <input type="text" class="form-control" name="direccion" required maxlength="20" value="<?php echo $data['direccion']; ?>">
                    <div class="invalid-feedback">La dirección es obligatoria.</div>
                </div>
            </div>
            <button class="btn btn-success btn-guardar" onclick="actualizarDatos(event)" title="Guardar">
                Guardar
            </button>
        </form>

        <h5>Datos Laborales</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Área</label>
                <input type="text" class="form-control" value="<?php echo $data['area']; ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label>Rol</label>
                <input type="text" class="form-control" value="<?php echo $data['rol']; ?>" readonly>
            </div>
        </div>

        <h5>Datos de Usuario</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Usuario</label>
                <input type="text" class="form-control" value="<?php echo $data['usuario']; ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label>Fecha Registro</label>
                <input type="text" class="form-control" value="<?php echo date("d/m/Y h:i A", strtotime($data['fecha'])); ?>" readonly>
            </div>
        </div>

        <h5>Cambio de Contraseña</h5>
        <form id="frmCambiarPass">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Contraseña Actual</label>
                    <input type="password" class="form-control" id="clave_actual" name="clave_actual">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nueva Contraseña</label>
                    <input type="password" class="form-control" id="clave_nueva" name="clave_nueva">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="clave_confirmar" name="clave_confirmar">
                </div>
                <div class="col-12 text-center">
                    <button class="btn btn-primary" onclick="modificarClave(event)">
                        Actualizar Contraseña
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Botón de guardar -->


<script>
    document.getElementById("telefono").addEventListener("input", function() {
        this.value = this.value.replace(/[^0-9]/g, "").slice(0, 9);
    });
    document.getElementById("dni").addEventListener("input", function() {
        this.value = this.value.replace(/[^0-9]/g, "").slice(0, 9);
    });

    function editarPerfil() {
        document.getElementById("editarPerfil").classList.remove("d-none");
    }

    function actualizarDatos(e) {
        e.preventDefault();

        const frm = document.getElementById("frmDatos");

        // Validación HTML5
        if (!frm.checkValidity()) {
            frm.classList.add("was-validated");
            frm.reportValidity();
            return;
        }

        // Envío de datos si es válido
        const url = base_url + "Usuarios/actualizarDato";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    const res = JSON.parse(this.responseText);

                    // Mostrar alerta mejorada con diseño
                    Swal.fire({
                        icon: res.icono, // success, warning, error
                        title: res.icono === 'success' ? '¡Éxito!' : res.icono === 'warning' ? 'Atención' : 'Error',
                        html: `<strong>${res.msg}</strong>`, // permite usar HTML
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        background: '#f4f6f9',
                        color: '#333',
                        iconColor: res.icono === 'success' ? '#28a745' : res.icono === 'warning' ? '#ffc107' : '#dc3545',
                        customClass: {
                            popup: 'swal-custom-popup',
                            title: 'swal-custom-title',
                            htmlContainer: 'swal-custom-html'
                        }
                    });

                    if (res.icono === 'success') {
                        setTimeout(() => {
                            window.location.reload();
                        }, 2500);
                    }

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'No se pudo procesar la respuesta del servidor',
                        background: '#f8d7da',
                        color: '#721c24'
                    });
                    console.error(error);
                }
            }
        };
    }

    function subirFoto() {
        const form = document.getElementById('formFoto');
        const formData = new FormData(form);

        fetch('<?php echo base_url; ?>Usuarios/subirFoto', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.json())
            .then(data => {
                if (data.status === 'success') {



                    const img = document.querySelector('.sidebar-perfil img');
                    img.src = `<?php echo base_url; ?>Assets/img/users/${data.nombre}?t=${Date.now()}`;

                    Swal.fire('Foto actualizada', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo subir la foto.', 'error');
            });
    }


    function eliminarFoto() {
        Swal.fire({
            title: '¿Eliminar foto?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?php echo base_url; ?>Usuarios/eliminarFoto', {
                        method: 'POST'
                    })
                    .then(resp => resp.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Eliminada', data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
            }
        });
    }
</script>


<?php include "Views/Templates/footer.php"; ?>