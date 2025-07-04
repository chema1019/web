<?php
class EmpresasModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getEmpresa()
    {
        $sql = "SELECT e.id, e.ruc, e.razon_social, e.telefono, e.direccion, e.fecha_vigencia, e.estado
            FROM empresas e";
        return $this->selectAll($sql);
    }
    public function getBuscarEmpresa($tipo, $valor)
    {
        // Validar el nombre de la columna
        if (!in_array($tipo, ['ruc', 'razon_social'])) {
            return []; // tipo inválido, evita inyección
        }

        $columna = "e." . $tipo; // columna segura ya validada

        $sql = "SELECT e.id, e.ruc, e.razon_social, e.telefono, e.direccion, e.fecha_vigencia, e.estado
            FROM empresas e
            WHERE $columna LIKE ?";

        $searchTerm = '%' . $valor . '%';
        return $this->selectAll($sql, [$searchTerm]);
    }
    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM empresas WHERE id = ?";
        return $this->select($sql, [$id]);
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
}
