<?php
class Pagos extends Controller
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
    public function listarRecibo()
    {
        $data = $this->model->getRecibo();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    public function buscarPagos()
    {
        header('Content-Type: application/json');

        ob_start(); // inicia el buffer de salida

        $tipo = $_GET['tipo'] ?? '';
        $valor = trim($_GET['valor'] ?? '');

        if (!in_array($tipo, ['ruc', 'razon_social']) || empty($valor)) {
            echo json_encode([]);
            ob_end_flush();
            return;
        }

        $data = $this->model->buscarPagos($tipo, $valor);





        $json = json_encode($data);

        ob_clean(); // limpia el buffer para evitar contenido extraño
        echo $json;

        ob_end_flush();
    }
}
