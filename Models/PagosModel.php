<?php
class PagosModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getRecibo()
    {
        $sql = "SELECT p.id, p.codigo_pago, p.monto, p.fecha_pago, p.estado as estado_pago,
               e.razon_social, e.ruc
        FROM pagos p
        INNER JOIN empresas e ON e.id = p.id_empresa";
        return $this->selectAll($sql);
    }
    public function verificarPermisos($id_user, $permiso)
    {
        $tiene = false;
        $sql = "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'";
        $existe = $this->select($sql);
        if ($existe != null || $existe != "") {
            $tiene = true;
        }
        return $tiene;
    }
    public function buscarPagos($tipo, $valor)
    {
        $sql = "SELECT p.*, e.razon_social, e.ruc 
            FROM pagos p
            INNER JOIN empresas e ON p.id_empresa = e.id";

        if ($tipo === 'razon_social') {
            $sql .= " WHERE e.razon_social LIKE ?";
        } elseif ($tipo === 'ruc') {
            $sql .= " WHERE e.ruc LIKE ?";
        } else {
            return []; // tipo no válido
        }

        $searchTerm = '%' . $valor . '%';
        return $this->selectAll($sql, [$searchTerm]);
    }
}
