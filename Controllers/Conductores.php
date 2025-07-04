<?php
class Conductores extends Controller
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
    public function listar()
    {
        $data = $this->model->getConductores();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['editar'] = '<button class="btn btn-warning btn-sm" onclick="editarConductor(' . $data[$i]['id'] . ')"><i class="fa fa-pencil"></i></button>';
            $data[$i]['eliminar'] = '<button class="btn btn-danger btn-sm" onclick="eliminarConductor(' . $data[$i]['id'] . ')"><i class="fa fa-trash"></i></button>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function eliminar($id)
    {
        $expediente = $_POST['expediente'] ?? null;

        if (!$expediente) {
            echo json_encode(['success' => false, 'message' => 'Expediente no recibido']);
            return;
        }

        $success = $this->model->accionConductor($id, $expediente);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Conductor dado de baja']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar. Verifica que el expediente sea valido']);
        }
    }



    public function buscarConductores()
    {
        header('Content-Type: application/json');

        ob_start(); // inicia el buffer de salida

        $tipo = $_GET['tipo'] ?? '';
        $valor = trim($_GET['valor'] ?? '');

        if (!in_array($tipo, ['dni', 'ruc', 'ce']) || empty($valor)) {
            echo json_encode([]);
            ob_end_flush();
            return;
        }

        $data = $this->model->buscarConductores($tipo, $valor);

        foreach ($data as &$row) {
            switch ($row['estado']) {
                case 'Vigente':
                    $row['estado'] = '<span class="badge bg-primary mx-auto">ALTA</span>';
                    break;
                case 'Vencido':
                    $row['estado'] = '<span class="badge bg-warning text-dark mx-auto">VENCIDO</span>';
                    break;
                case 'Baja':
                    $row['estado'] = '<span class="badge bg-danger mx-auto">BAJA</span>';
                    break;
            }
        }




        $json = json_encode($data);

        ob_clean(); // limpia el buffer para evitar contenido extraño
        echo $json;

        ob_end_flush();
    }
    public function obtenerConductorPorId()
    {
        $id = $_POST['id'] ?? 0;
        $data = $this->model->obtenerConductor($id);

        header('Content-Type: application/json');
        echo json_encode($data);
    }


    public function obtenerConductor()
    {
        $id = $_GET['id'] ?? 0;
        $data = $this->model->obtenerConductorPorId($id);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    public function pdf()
    {
        require_once 'Libraries/pdf/fpdf.php';

        $tipo = $_GET['tipo'] ?? null;
        $valor = $_GET['valor'] ?? null;

        if ($tipo && $valor) {
            $data = $this->model->buscarConductores($tipo, $valor);
        } else {
            $data = $this->model->getConductores();
        }

        // Crear el PDF en orientación horizontal
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        // Título
        $pdf->Cell(0, 10, 'Lista de Conductores', 0, 1, 'C');
        $pdf->Ln(2);

        // Encabezado de la tabla
        $pdf->SetFillColor(200, 220, 255);
        $pdf->SetFont('Arial', 'B', 10);

        $pdf->Cell(10, 10, '#', 1, 0, 'C', true);
        $pdf->Cell(70, 10, utf8_decode('Razón Social'), 1, 0, 'C', true);
        $pdf->Cell(25, 10, utf8_decode('N° Doc'), 1, 0, 'C', true);
        $pdf->Cell(50, 10, utf8_decode('Conductor'), 1, 0, 'C', true);
        $pdf->Cell(20, 10, utf8_decode('Categoría'), 1, 0, 'C', true);
        $pdf->Cell(20, 10, utf8_decode('Licencia'), 1, 0, 'C', true);
        $pdf->Cell(30, 10, utf8_decode('Expedición'), 1, 0, 'C', true);
        $pdf->Cell(30, 10, utf8_decode('Revalidación'), 1, 0, 'C', true);
        $pdf->Cell(25, 10, utf8_decode('Estado'), 1, 1, 'C', true);
        if (empty($data)) {
            $pdf->Cell(0, 10, 'No se encontraron resultados para esta búsqueda.', 1, 1, 'C');
            $pdf->Output('I', 'conductores.pdf');
            return;
        }

        // Contenido
        $pdf->SetFont('Arial', '', 9);
        $contador = 1;
        foreach ($data as $row) {
            switch ($row['estado']) {
                case 'Vigente':
                    $row['estado'] = 'ALTA';
                    break;
                case 'Vencido':
                    $row['estado'] = 'VENCIDO';
                    break;
                case 'Baja':
                    $row['estado'] = 'BAJA';
                    break;
            }
            $pdf->Cell(10, 8, $contador++, 1, 0, 'C');
            $pdf->Cell(70, 8, utf8_decode($row['razon_social']), 1, 0);
            $pdf->Cell(25, 8, $row['n_documento'], 1, 0, 'C');
            $nombreCompleto = $row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno'];
            $pdf->Cell(50, 8, utf8_decode($nombreCompleto), 1, 0, 'C');

            $pdf->Cell(20, 8, $row['categoria'], 1, 0, 'C');
            $pdf->Cell(20, 8, $row['licencia_conducir'], 1, 0, 'C');
            $pdf->Cell(30, 8, $row['fecha_expedicion'], 1, 0, 'C');
            $pdf->Cell(30, 8, $row['fecha_vencimiento'], 1, 0, 'C');

            $pdf->Cell(25, 8, $row['estado'], 1, 1, 'C');
        }

        $pdf->Output('I', 'conductores.pdf');
    }
}
