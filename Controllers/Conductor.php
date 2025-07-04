<?php
class Conductor extends Controller
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
    public function buscarExpediente($expediente)
    {
        $data = $this->model->getBuscarExpediente($expediente);
        echo json_encode($data);
        die(); // Termina la ejecución
    }



    public function buscarDocumento()
    {
        $tipo = $_POST['tipo'];
        $numero = $_POST['numero'];
        $data = $this->model->obtenerConductorPorDocumento($tipo, $numero);

        header('Content-Type: application/json');
        echo json_encode($data);
    }


    public function registrar()
    {
        if (
            empty($_POST['tipo_documento']) ||
            empty($_POST['numero_documento']) ||
            empty($_POST['nombres']) ||
            (empty($_POST['apellido_paterno']) && empty($_POST['apellido_materno'])) ||
            empty($_POST['licencia']) ||
            empty($_POST['categoria']) ||
            empty($_POST['fecha_expedicion']) ||
            empty($_POST['fecha_vencimiento']) ||
            empty($_POST['estado_licencia']) ||
            empty($_POST['id_empresa']) ||
            empty($_POST['id_validacion']) ||
            empty($_POST['id_record']) ||
            empty($_POST['expediente'])
        ) {
            echo json_encode(['status' => false, 'msg' => 'Todos los campos son obligatorios']);
            return;
        }
        if (strtolower($_POST['estado_licencia']) === 'vencido') {
            echo json_encode(['status' => false, 'msg' => 'No se puede registrar un conductor con licencia vencida']);
            return;
        }


        $expediente = trim($_POST['expediente']);
        $id_validacion = $_POST['id_validacion'];
        $numero_documento = trim($_POST['numero_documento']);



        // 1. Validar existencia del expediente y cupo disponible 


        $validacion = $this->model->getValidacionByExpediente($expediente, $id_validacion);

        if (empty($validacion)) {
            echo json_encode(['status' => false, 'msg' => 'El expediente no existe o no es válido']);
            return;
        }

        // Ya no necesitas hacer $validacion[0]
        // Puedes acceder directamente:
        if ($validacion['n_conductores'] <= 0) {
            echo json_encode(['status' => false, 'msg' => 'No hay cupo disponible para este expediente']);
            return;
        }




        // 2. Validar que el conductor no exista ya registrado
        $existe = $this->model->verificarConductorExiste($numero_documento);
        if ($existe) {
            echo json_encode(['status' => false, 'msg' => 'Este conductor ya está registrado']);
            return;
        }

        // 3. Procesar la imagen (si aplica)
        $foto = $_FILES['foto']['name'] ?? '';
        $nombre_foto = '';

        if (!empty($foto)) {
            $nombre_foto = date('YmdHis') . "_" . $foto;
            $ruta = "Assets/img/conductor/" . $nombre_foto;
            move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
        } else {
            // ✅ Aquí usamos el campo oculto si no se subió foto nueva
            $nombre_foto = $_POST['foto_oculta'] ?? '';
        }


        // 4. Preparar datos
        $data = [
            't_documento' => $_POST['tipo_documento'],
            'n_documento' => $numero_documento,
            'nombre' => $_POST['nombres'],
            'apellido_paterno' => $_POST['apellido_paterno'],
            'apellido_materno' => $_POST['apellido_materno'],
            'licencia_conducir' => $_POST['licencia'],
            'categoria' => $_POST['categoria'],
            'fecha_expedicion' => $_POST['fecha_expedicion'],
            'fecha_vencimiento' => $_POST['fecha_vencimiento'],
            'estado' => $_POST['estado_licencia'],
            'foto' => $nombre_foto,

            'id_empresa' => $_POST['id_empresa'],
            'id_record' => $_POST['id_record'], // el id record : es el numero que estamos llamando de los datos del conductor
            'id_vpago' => $id_validacion
        ];

        // 5. Insertar
        $insert = $this->model->registrarConductor($data);

        if ($insert) {
            // Actualiza el cupo y estado automáticamente si corresponde
            $this->model->actualizarValidacionPago($id_validacion);

            echo json_encode(['status' => true, 'msg' => 'Conductor registrado correctamente']);
        } else {
            echo json_encode(['status' => false, 'msg' => 'Error al registrar el conductor']);
        }
    }

    public  function Conductores()
    {
        $this->views->getView($this, "index2");
    }
}
