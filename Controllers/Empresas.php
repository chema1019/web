<?php
class Empresas extends Controller
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
    public function listarEmpresa()
    {
        $data = $this->model->getEmpresa();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function buscarEmpresa()
    {
        header('Content-Type: application/json');
        ob_clean(); // limpiar antes
        ob_start(); // iniciar buffer

        $tipo = $_GET['tipo'] ?? '';
        $valor = trim($_GET['valor'] ?? '');

        if (!in_array($tipo, ['ruc', 'razon_social']) || empty($valor)) {
            echo json_encode([]);
            ob_end_flush();
            return;
        }

        $data = $this->model->getBuscarEmpresa($tipo, $valor);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        ob_end_flush(); // cerrar buffer después de imprimir
    }
    public function obtenerEmpresa($id)
    {
        $data = $this->model->buscarPorId($id);
        echo json_encode($data);
    }
}
