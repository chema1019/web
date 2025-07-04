<?php
class Empresa extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
    public function registrar()
    {
        $id = strClean($_POST['id']);
        $ruc = strClean($_POST['ruc']);
        $razon_social = strClean($_POST['razon_social']);
        $direccion = strClean($_POST['direccion']);
        $telefono = strClean($_POST['telefono']);
        $correo = strClean($_POST['correo']);
        $autorizacion = strClean($_POST['autorizacion']);
        $fecha_vigencia = $_POST['fecha_vigencia'];
        $documento = $_FILES['documento'];
        $nombreDocumento = $documento['name'];
        $tmpDocumento = $documento['tmp_name'];
        $destino = "Assets/documentos/" . uniqid() . "_" . $nombreDocumento;

        if ($documento['type'] != 'application/pdf') {
            $res = ['msg' => 'Solo se permiten archivos PDF', 'icono' => 'warning'];
            echo json_encode($res);
            exit;
        }

        if (
            empty($ruc) || empty($razon_social) || empty($direccion) || empty($telefono) ||
            empty($autorizacion) || empty($correo) || empty($fecha_vigencia) || empty($nombreDocumento)
        ) {
            $res = array('msg' => 'Todos los campos son requeridos', 'icono' => 'warning');
        } else {
            if ($id == "") {
                // Verificar primero si ya existe el RUC
                $verificar = $this->model->verificarRuc($ruc);
                file_put_contents("debug_input.txt", print_r($_POST, true));

                if (!is_array($verificar)) {
                    file_put_contents("debug_error.txt", "verificarRuc devolvió un tipo inesperado: " . gettype($verificar));
                }

                if (is_array($verificar) && isset($verificar['id'])) {
                    $res = ['msg' => 'El RUC ya está registrado', 'icono' => 'warning'];
                } else {
                    move_uploaded_file($tmpDocumento, $destino);
                    $data = $this->model->registrarEmpresa($ruc, $razon_social, $direccion, $telefono, $correo, $autorizacion, $fecha_vigencia, $destino);

                    if ($data === "ok") {
                        $res = ['msg' => 'Empresa registrada con éxito', 'icono' => 'success', 'status' => true];
                    } else {
                        $res = ['msg' => 'Error al registrar', 'icono' => 'error', 'status' => false];
                    }
                }
            } else {
                $res = ['msg' => 'ID no permitido en esta operación', 'icono' => 'error'];
            }
        }

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die;
    }


    public function empresas()
    {
        $this->views->getView($this, "index2");
    }
}
