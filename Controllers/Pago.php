<?php
class Pago extends Controller
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

    public function Validar_pago()
    {
        $this->views->getView($this, "index3");
    }


    public function registrar()
    {
        ob_clean();
        header('Content-Type: application/json');

        $id = strClean($_POST['id']);
        $id_emp = intval(strClean($_POST['id_empresa']));
        $codigo = strClean($_POST['codigo_servicio']);
        $descripcion = strClean($_POST['descripcion']);
        $monto = strClean($_POST['monto']);
        $fecha = strClean($_POST['fecha']);
        $nombreRecibo = '';

        if ($id_emp <= 0) {
            echo json_encode(['msg' => 'Debe seleccionar una empresa válida', 'icono' => 'warning']);
            exit;
        }

        if (!empty($_FILES['recibo']['name'])) {
            $tipo = $_FILES['recibo']['type'];
            if ($tipo != 'application/pdf') {
                echo json_encode(['msg' => 'Solo se permiten archivos PDF', 'icono' => 'warning']);
                exit;
            }

            $nombreRecibo = uniqid() . ".pdf";
            move_uploaded_file($_FILES['recibo']['tmp_name'], "Assets/recibos/" . $nombreRecibo);
        }

        $data = $this->model->registrarPago($id_emp, $codigo, $descripcion, $monto, $fecha, $nombreRecibo);

        if (is_numeric($data) && $data > 0) {
            echo json_encode(['msg' => 'Pago registrado con éxito', 'icono' => 'success']);
        } else {
            echo json_encode(['msg' => 'Error al registrar el pago', 'icono' => 'error']);
        }

        exit;
    }



    public function buscarRuc($ruc)
    {
        $data = $this->model->getBuscarEmpresa($ruc);

        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(null);
        }
    }

    public function buscarEmpresa()
    {
        if (isset($_POST['ruc'])) {
            $ruc = $_POST['ruc'];
            $data = $this->model->getEmpresaPorRuc($ruc);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(null);
        }
        die();
    }

    public function listarPorEmpresa()
    {
        $id = isset($_POST['id_empresa']) ? $_POST['id_empresa'] : null;

        if (!$id) {
            error_log("ID EMPRESA NO ENVIADO");
            echo json_encode([]);
            return;
        }

        error_log("ID EMPRESA RECIBIDO: " . $id); // 👈 Para ver en el log

        $data = $this->model->getPagosPorEmpresa($id);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function verPago($id)
    {
        $data = $this->model->getPagoById($id);
        if (!empty($data['recibo'])) {
            $data['recibo_url'] = base_url . "Assets/recibos/" . $data['recibo'];
        } else {
            $data['recibo_url'] = '';
        }
        echo json_encode($data);
    }

    public function listarRecibo()
    {
        $data = $this->model->getRecibo();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }



    public function registrarValidacion()
    {
        $expediente = strClean($_POST['expediente']);
        $fecha = strClean($_POST['fecha_expediente']);
        $modalidad = strClean($_POST['modalidad']);
        $conductores = strClean($_POST['n_conductores']);
        $pagos = isset($_POST['ids_pagos']) ? explode(',', $_POST['ids_pagos']) : [];

        if (empty($pagos)) {
            echo json_encode([
                "msg" => "Faltan datos obligatorios",
                "icono" => "warning",
                "status" => false
            ]);
            return;
        }

        $errores = 0;
        foreach ($pagos as $id_pago) {
            $resp = $this->model->RegistrarValidacion($id_pago, $expediente, $fecha, $modalidad, $conductores);

            if ($resp !== "ok") {
                $errores++;
            }
        }

        // Actualizar estado de todos los pagos validados
        $this->model->actualizarEstadoPagos($pagos);

        if ($errores === 0) {
            echo json_encode([
                "msg" => "Pagos validados correctamente",
                "icono" => "success",
                "status" => true
            ]);
        } else {
            echo json_encode([
                "msg" => "Algunos pagos ya estaban validados",
                "icono" => "warning",
                "status" => false
            ]);
        }
    }


    public function Pagos()
    {
        $this->views->getView($this, "index2");
    }
}
