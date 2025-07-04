<?php
class PagoModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registrarPago(int $id_emp, string $codigo_pago, string $descripcion, string $monto, string $fecha_pago, string $documento)
    {
        $sql = "INSERT INTO pagos (id_empresa, codigo_pago, descripcion, monto, fecha_pago, recibo) 
            VALUES (?, ?, ?, ?, ?, ?)";

        $datos = array($id_emp, $codigo_pago, $descripcion, $monto, $fecha_pago, $documento);

        $data = $this->insert($sql, $datos);
        return $data; // Devuelve el ID generado o false
    }

    public function getBuscarEmpresa($ruc)
    {
        $sql = "SELECT id, razon_social FROM empresas WHERE ruc = ?";
        return $this->select($sql, [$ruc]);
    }
    public function getPagoById($id)
    {
        $sql = "SELECT p.id, p.codigo_pago, p.descripcion, p.monto, p.fecha_pago, p.recibo,
                   e.razon_social, e.ruc
            FROM pagos p
            INNER JOIN empresas e ON e.id = p.id_empresa
            WHERE p.id = ?";
        return $this->select($sql, [$id]);
    }

    public function getPago()
    {
        $sql = "SELECT p.*, e.id AS id_empresa,  e.razon_social
                FROM pagos p
                INNER JOIN empresas e ON e.id = p.id_empresa

                WHERE p.estado = 1";  // Filtra solo clientes con estado 1

        $data = $this->selectAll($sql);
        return $data;
    }

    //  consulta de la base de datos
    // SELECT p.*, e.id AS id_empresa,  e.razon_social
    //            FROM validacion_pago vp
    //         INNER JOIN pagos  p ON p.id = vp.id_pago
    //        INNER JOIN empresas e ON e.id = p.id_empresa

    public function getEmpresaPorRuc($ruc)
    {
        $sql = "SELECT id, razon_social FROM empresas WHERE ruc = ?";
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

    public function getPagosPorEmpresa($id_empresa)
    {
        $sql = "SELECT p.id, p.codigo_pago, p.monto, p.fecha_pago, p.estado as estado_pago,
       e.razon_social
FROM pagos p
INNER JOIN empresas e ON e.id = p.id_empresa
WHERE p.estado = 1 AND e.id = ?";
        return $this->selectAll($sql, [$id_empresa]);
    }

    public function actualizarEstadoPagos($ids)
    {
        if (empty($ids)) {
            return false;
        }

        $in = implode(',', array_fill(0, count($ids), '?'));
        $sql = "UPDATE pagos SET estado = 0 WHERE id IN ($in)";
        return $this->update($sql, $ids);
    }

    public function RegistrarValidacion(int $id_pago, string $expediente, string $fecha_expediente, string $modalidad, string $n_conductores)
    {
        $verificar = "SELECT id FROM validacion_pago WHERE id_pago = ? AND estado = 1";
        $existe = $this->select($verificar, [$id_pago]);

        if (empty($existe)) {
            $sql = "INSERT INTO validacion_pago (id_pago, expediente, fecha_expediente, modalidad, n_conductores) VALUES (?, ?, ?, ?, ?)";
            $datos = array($id_pago, $expediente, $fecha_expediente, $modalidad, $n_conductores);
            $data = $this->insert($sql, $datos);
            if ($data) return "ok";
            else return "error";
        } else {
            return "existe";
        }
    }
}
