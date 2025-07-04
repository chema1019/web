<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar | Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/font-awesome.min.css">
    <!-- Asegúrate de tener SweetAlert2 en tu HTML -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        .input-icon {
            position: absolute;

            top: 13px;
            /* sube el icono un poco */
            /* fijo para evitar desplazamiento */
            left: 14px;
            pointer-events: none;
            z-index: 2;
            font-size: 1rem;
            color: #aaa;
        }


        .form-control {
            padding-left: 2.25rem;
            /* espacio para el icono */
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
        }



        .invalid-feedback {
            opacity: 0;
            transition: opacity 0.5s ease;
            font-size: 0.875rem;
            min-height: 1.25rem;
            /* Mantiene el espacio */
        }

        .was-validated .form-control:invalid~.invalid-feedback,
        .form-control.is-invalid~.invalid-feedback {
            opacity: 1;
        }


        .btn {
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 500;
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
            background: linear-gradient(90deg, #0d6efd, #0a58ca);
            border: none;
        }

        .btn-outline-secondary {
            border-radius: 8px;
        }




        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .modal-header {
            border-bottom: none;
            padding: 1.5rem 1.5rem;
            background: linear-gradient(to right, #0d6efd, #0a58ca);
            color: white;
            border-radius: 16px 16px 0 0;
        }

        .login-box {
            max-width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .login-box h2 {
            margin-bottom: 25px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .logo-text {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-text span {
            font-size: 14px;
            color: #888;
            display: block;
        }

        .btn-login {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .extra-links a {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
        }

        .form-control {
            width: 100%;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            padding: 8px 10px;
            font-size: 1rem;
            border-radius: 8px;
            outline: none;
            box-shadow: none;
            transition: all 0.3s ease-in-out;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: #0d6efd;
            background-color: #fff;
            transform: scale(1.02);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
        }

        .input-group-text {
            background-color: #e9ecef;
            border: none;
            border-radius: 8px 0 0 8px;
            padding-left: 1rem;
            padding-right: 1rem;
            font-size: 1rem;
            color: #495057;
        }
    </style>
</head>

<body>

   <div class="login-box"> 
    <div class="logo-text">LOGO <span>empresa</span></div>
    <form id="frmLogin" class="needs-validation" novalidate onsubmit="frmLogin(event);">
        <h2>Iniciar Sesión</h2>

        <div class="mb-3">
            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingresar Usuario" required>
            <div class="invalid-feedback">Usuario incorrecto.</div>
        </div>

        <div class="mb-3">
            <input type="password" class="form-control" id="clave" name="clave" placeholder="Ingresar Contraseña" required>
            <div class="invalid-feedback">Contraseña inválida.</div>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="recordar">
            <label class="form-check-label" for="recordar">Recordar contraseña</label>
        </div>

        <button type="submit" class="btn btn-login w-100">INICIAR SESIÓN</button>

        <div class="extra-links mt-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#nuevo_usuario">Registrar cuenta</a>
        </div>
    </form>
</div>

<!-- Modal Registrar Usuario -->
<div class="modal fade" id="nuevo_usuario" tabindex="-1" aria-labelledby="nuevoUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow rounded-4 border-0">
            <div class="modal-header bg-gradient bg-primary text-white rounded-top">
                <h5 class="modal-title fw-bold" id="title">Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="frmUsuario" method="post" class="needs-validation" novalidate autocomplete="off">
                <div class="modal-body p-3">
                    <input type="hidden" id="id" name="id">
                    <div class="row g-4">

                        <!-- Lado izquierdo -->
                        <div class="col-md-6">
                            <div class="card border-light shadow-sm p-3">
                                <h6 class="text-dark mb-3">Información Personal</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="dni">DNI</label>
                                    <input type="text" class="form-control" id="dni" name="dni"
                                        placeholder="Ingrese DNI" pattern="[0-9]{8}" maxlength="8"
                                        title="Debe contener exactamente 8 dígitos numéricos" required oninput="validarDni()">
                                    <div class="invalid-feedback">Por favor ingrese un DNI válido (8 dígitos).</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        placeholder="Ingrese nombre" maxlength="20" required>
                                    <div class="invalid-feedback">El nombre es obligatorio.</div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <label class="form-label" for="apellido_paterno">Apellido Paterno</label>
                                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                                            placeholder="Apellido paterno" maxlength="15" required>
                                        <div class="invalid-feedback">Ingrese el apellido paterno.</div>
                                    </div>
                                    <div class="col">
                                        <label class="form-label" for="apellido_materno">Apellido Materno</label>
                                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                                            placeholder="Apellido materno" maxlength="15" required>
                                        <div class="invalid-feedback">Ingrese el apellido materno.</div>
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label class="form-label" for="correo">Correo</label>
                                    <input type="email" class="form-control" id="correo" name="correo"
                                        placeholder="Correo electrónico" required oninput="formatCorreo()"
                                        pattern="^[a-zA-Z0-9._%+-]+@(gmail|hotmail)\.com$"
                                        title="Solo se permite correos @gmail.com o @hotmail.com">
                                    <div class="invalid-feedback">Solo se permite correos @gmail.com o @hotmail.com</div>
                                </div>
                            </div>
                        </div>

                        <!-- Lado derecho -->
                        <div class="col-md-6">
                            <div class="card border-light shadow-sm p-3">
                                <h6 class="text-dark mb-3">Datos de Acceso</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="telefono">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono"
                                        placeholder="Teléfono" pattern="[0-9]{9}" maxlength="9" required oninput="validarTelefono()">
                                    <div class="invalid-feedback">Ingrese un número de 9 dígitos.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="direccion">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                        placeholder="Dirección" maxlength="20" required>
                                    <div class="invalid-feedback">La dirección es obligatoria.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="usuarios">Usuario</label>
                                    <input type="text" class="form-control" id="usuarios" name="usuarios"
                                        placeholder="Usuario" maxlength="12" required>
                                    <div class="invalid-feedback">Ingrese un nombre de usuario.</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label" for="claves">Contraseña</label>
                                        <input type="password" class="form-control" id="claves" name="claves"
                                            placeholder="Contraseña" minlength="8" required>
                                        <div class="invalid-feedback">Ingrese una contraseña de al menos 8 caracteres.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="confirmar">Confirmar</label>
                                        <input type="password" class="form-control" id="confirmar" name="confirmar"
                                            placeholder="Confirmar" minlength="8" required>
                                        <div class="invalid-feedback">Confirme la contraseña.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-primary" type="submit" id="btnAccion" onclick="registrarUser(event);">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



    <script>
        // Activar validación al hacer submit

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
    </script>







    <!-- JS Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS personalizado -->
    <script>
        const base_url = '<?php echo base_url; ?>';
    </script>
    <script src="<?php echo base_url; ?>Assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/logintss.js"></script>
</body>

</html>