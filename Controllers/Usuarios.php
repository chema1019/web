<?php
class Usuarios extends Controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function index()
    {
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }

        $this->views->getView($this, "colaboradores");
    }
    public function listar()
    {
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }

        $data = $this->model->getUsuarios();

        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 'Activo') {
                // ✅ Verificamos por el campo 'rol' (no por ID)
                if ($data[$i]['rol'] != 'Administrador') {
                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                    $data[$i]['acciones'] = '<div class="text-center">
                    <button class="btn btn-dark btn-sm mr-1" onclick="btnRolesUser(' . $data[$i]['id'] . ')" title="Asignar rol">
                        <i class="fas fa-user-shield"></i>
                    </button>
                    <button class="btn btn-primary btn-sm mr-1" onclick="btnEditarUser(' . $data[$i]['id'] . ')" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="btnEliminarUser(' . $data[$i]['id'] . ')" title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>';
                } else {
                    // Super administrador por ROL
                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                    $data[$i]['acciones'] = '<div class="text-center">
                    <span class="badge badge-primary p-1 rounded">Super Administrador</span>
                </div>';
                }
            } else {
                // Usuario inactivo
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div class="text-center">
                <button class="btn btn-success btn-sm" onclick="btnReingresarUser(' . $data[$i]['id'] . ')" title="Reingresar">
                    <i class="fas fa-undo-alt"></i>
                </button>
            </div>';
            }
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function validar()
    {
        $usuario = strClean($_POST['usuario']);
        $clave = strClean($_POST['clave']);
        if (empty($usuario) || empty($clave)) {
            $msg = array('msg' => 'Todo los campos son requeridos', 'icono' => 'warning');
        } else {
            $hash = hash("SHA256", $clave);
            $data = $this->model->getUsuario($usuario, $hash);
            if ($data) {
                $_SESSION['id_usuario'] = $data['id'];
                $_SESSION['usuario'] = $data['usuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['rol'] = $data['rol']; // Donde $data es el array con los datos del usuari
                $_SESSION['activo'] = true;
                $_SESSION['permisos'] = $this->model->getPermisosUsuario($data['id']);
                $msg = array('msg' => 'Procesando', 'icono' => 'success');
            } else {
                $msg = array('msg' => 'Usuario o contraseña incorrecta', 'icono' => 'warning');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function registrarUsers()
    {
        $usuario = strClean($_POST['usuarios']);
        $dni = strClean($_POST['dni']);
        $nombre = strClean($_POST['nombre']);
        $apellido_paterno = strClean($_POST['apellido_paterno']);
        $apellido_materno = strClean($_POST['apellido_materno']);
        $correo = strClean($_POST['correo']);
        $telefono = strClean($_POST['telefono']);
        $direccion = strClean($_POST['direccion']);
        $rol = strClean($_POST['rol']);
        $clave = strClean($_POST['claves']);
        $confirmar = strClean($_POST['confirmar']);
        $id = strClean($_POST['id']);
        $perfil = 'avatar.svg';


        if (
            empty($usuario) || empty($dni) || empty($nombre) || empty($apellido_paterno) || empty($apellido_materno) || empty($correo) ||
            empty($telefono) || empty($direccion)
        ) {
            $msg = array('msg' => 'Todos los campos son requeridos', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!empty($clave) && !empty($confirmar)) {
                    if ($clave != $confirmar) {
                        $msg = array('msg' => 'Las contraseñas no coinciden', 'icono' => 'warning');
                    } else {
                        $hash = hash("SHA256", $clave);
                        $data = $this->model->registrarUsers($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $hash, $perfil, $rol);


                        if ($data == "ok") {
                            $msg = array('msg' => 'Usuario registrado', 'icono' => 'success');
                        } else if ($data == "existe") {
                            $msg = array('msg' => 'El usuario ya existe', 'icono' => 'warning');
                        } else {
                            $msg = array('msg' => 'Error al registrar', 'icono' => 'error');
                        }
                    }
                }
            } else {

                $data = $this->model->modificarUsuario($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $rol, $id);

                if ($data == "modificado") {
                    $msg = array('msg' => 'Usuario modificado', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Error al modificar', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $usuario = strClean($_POST['usuarios']);
        $dni = strClean($_POST['dni']);
        $nombre = strClean($_POST['nombre']);
        $apellido_paterno = strClean($_POST['apellido_paterno']);
        $apellido_materno = strClean($_POST['apellido_materno']);
        $correo = strClean($_POST['correo']);
        $telefono = strClean($_POST['telefono']);
        $direccion = strClean($_POST['direccion']);
        $clave = strClean($_POST['claves']);
        $confirmar = strClean($_POST['confirmar']);
        $id = strClean($_POST['id']);
        $perfil = 'avatar.svg';


        if (
            empty($usuario) || empty($dni) || empty($nombre) || empty($apellido_paterno) || empty($apellido_materno) || empty($correo) ||
            empty($telefono) || empty($direccion)
        ) {
            $msg = array('msg' => 'Todos los campos son requeridos', 'icono' => 'warning');
        } else {
            if ($id == "") {
                if (!empty($clave) && !empty($confirmar)) {
                    if ($clave != $confirmar) {
                        $msg = array('msg' => 'Las contraseñas no coinciden', 'icono' => 'warning');
                    } else {
                        $hash = hash("SHA256", $clave);
                        $data = $this->model->registrarUsuario($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $hash, $perfil);


                        if ($data == "ok") {
                            $msg = array('msg' => 'Usuario registrado', 'icono' => 'success');
                        } else if ($data == "existe") {
                            $msg = array('msg' => 'El usuario ya existe', 'icono' => 'warning');
                        } else {
                            $msg = array('msg' => 'Error al registrar', 'icono' => 'error');
                        }
                    }
                }
            } else {


                $msg = array('msg' => 'Error al modificar', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data = $this->model->editarUser($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function actualizarDato()
    {
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
            exit;
        }

        // Obtener datos personales
        $dni = strClean($_POST['dni']);
        $nombre = strClean($_POST['nombre']);
        $apellido_paterno = strClean($_POST['apellido_paterno']);
        $apellido_materno = strClean($_POST['apellido_materno']); // Corregido
        $telefono = strClean($_POST['telefono']);
        $direccion = strClean($_POST['direccion']);
        $id = $_SESSION['id_usuario'];

        // Validación básica del lado del servidor
        if (
            empty($dni) || empty($nombre) || empty($apellido_paterno) || empty($apellido_materno)
            || empty($telefono) || empty($direccion)
        ) {
            $msg = array('msg' => 'Todos los campos son obligatorios', 'icono' => 'warning');
        } else {
            // Actualiza los datos en el modelo
            $data = $this->model->modificarDatoPersonal($dni, $nombre, $apellido_paterno, $apellido_materno, $telefono, $direccion, $id);

            if ($data == 1) {
                $msg = array('msg' => 'Datos personales actualizados con éxito', 'icono' => 'success');
            } else {
                $msg = array('msg' => 'Error al actualizar los datos', 'icono' => 'error');
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function eliminar(int $id)
    {
        $data = $this->model->accionUser(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Usuario dado de baja', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al eliminar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id)
    {
        $data = $this->model->accionUser(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Usuario restaurado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al restaurar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function permisos($id)
    {
        $id_user = $_SESSION['id_usuario'];
        $perm = $this->model->verificarPermisos($id_user, "roles");

        if (!$perm && $_SESSION['rol'] !== 'Administrador') {
            echo '<div class="card">
                <div class="card-body text-center">
                    <span class="badge badge-danger">No tienes permisos</span>
                </div>
              </div>';
            exit;
        }

        $data = $this->model->getPermisos();
        $asignados = $this->model->getDetallePermisos($id);

        // Marcar permisos asignados
        $datos = array();
        foreach ($asignados as $asignado) {
            $datos[$asignado['id_permiso']] = true;
        }

        echo '<input type="hidden" name="id_usuario" value="' . $id . '">';
        echo '<ul class="list-group shadow-sm">';

        foreach ($data as $row) {
            $checked = isset($datos[$row['id']]) ? 'checked' : '';
            echo '
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span class="text-capitalize font-weight-bold">' . htmlspecialchars($row['nombre']) . '</span>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="perm_' . $row['id'] . '" name="permisos[]" value="' . $row['id'] . '" ' . $checked . '>
            <label class="custom-control-label" for="perm_' . $row['id'] . '"></label>
        </div>
    </li>';
        }
        echo '</ul>
<div class="text-center">
    <button class="btn btn-primary btn-lg mt-4" onclick="registrarPermisos(event)">
        <i class="fa fa-check mr-1"></i> Actualizar
    </button>
</div>';


        die();
    }

    public function registrarPermisos()
    {
        $id_user = strClean($_POST['id_usuario']);
        $permisos = $_POST['permisos'];
        $this->model->deletePermisos($id_user);
        if ($permisos != "") {
            foreach ($permisos as $permiso) {
                $this->model->actualizarPermisos($id_user, $permiso);
            }
        }
        echo json_encode("ok");
        die();
    }
    public function cambiarPas()
    {
        if ($_POST) {
            $id = $_SESSION['id_usuario'];
            $clave = strClean($_POST['clave_actual']);
            $user = $this->model->editarUser($id);
            if (hash("SHA256", $clave) == $user['clave']) {
                $hash = hash("SHA256", strClean($_POST['clave_nueva']));
                $data = $this->model->actualizarPass($hash, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Contraseña modificado', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Error al modificar', 'icono' => 'warning');
                }
            } else {
                $msg = array('msg' => 'Contraseña actual incorrecta', 'icono' => 'warning');
            }
            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function salir()
    {
        session_destroy();
        header("location: " . base_url);
    }

    public function Perfil()
    {
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        $id_user = $_SESSION['id_usuario'];


        $data = $this->model->editarUser($id_user);
        $this->views->getView($this, "perfil", $data);
    }

    public  function colaboradores()
    {
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }




        $this->views->getView($this, "colaboradores");
    }
  public function subirFoto()
{
    if (!empty($_FILES['foto']['name'])) {
        $idUsuario = $_SESSION['id_usuario'];
        $archivo = $_FILES['foto'];

        $permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml', 'image/webp'];
        if (!in_array($archivo['type'], $permitidos)) {
            echo json_encode(['status' => 'error', 'message' => 'Tipo de archivo no permitido.']);
            return;
        }

        if ($archivo['size'] > 2 * 1024 * 1024) {
            echo json_encode(['status' => 'error', 'message' => 'La imagen no debe superar los 2MB.']);
            return;
        }

        $carpeta = 'Assets/img/users/';
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0775, true);
        }

        $usuario = $this->model->getUsers($idUsuario);
        if (!empty($usuario['perfil']) && $usuario['perfil'] !== 'avatar.svg') {
            $fotoAnterior = $carpeta . $usuario['perfil'];
            if (file_exists($fotoAnterior)) {
                unlink($fotoAnterior);
            }
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
       
        $nombreArchivo = 'perfil_' . $idUsuario . '_' . time() . '.' . $extension;
        $ruta = $carpeta . $nombreArchivo;

        if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
            error_log("NOMBRE A GUARDAR EN BD: " . $nombreArchivo);
            $this->model->actualizarFoto($idUsuario, $nombreArchivo);
            echo json_encode([
                'status' => 'success',
                'message' => 'Foto actualizada correctamente.',
                'nombre' => $nombreArchivo
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al mover la imagen.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió ninguna imagen.']);
    }
}


    public function eliminarFoto()
    {
        $idUsuario = $_SESSION['id_usuario'];
        $usuario = $this->model->getUsers($idUsuario);

        if ($usuario && $usuario['perfil'] != 'avatar.svg') {
            $ruta = 'Assets/img/users/' . $usuario['perfil'];
            if (file_exists($ruta)) {
                unlink($ruta);
            }
            $this->model->actualizarFoto($idUsuario, 'avatar.svg');
            echo json_encode(['status' => 'success', 'message' => 'Foto eliminada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'La foto ya es la predeterminada.']);
        }
    }
}
