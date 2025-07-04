<?php
class EmpresaModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registrarEmpresa($ruc, $razon_social, $direccion, $telefono, $correo, $autorizacion, $fecha_vigencia, $documento)
    {
        $sql = "INSERT INTO empresas (ruc, razon_social, direccion, telefono, correo, autorizacion, fecha_vigencia, documento)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $datos = [$ruc, $razon_social, $direccion, $telefono, $correo, $autorizacion, $fecha_vigencia, $documento];

        $data = $this->insert($sql, $datos);

        return $data ? "ok" : "error";
    }


    public function verificarRuc($ruc)
    {
        $sql = "SELECT id FROM empresas WHERE ruc = ?";
        return $this->select($sql, [$ruc]);
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
