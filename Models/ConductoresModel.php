<?php
class ConductoresModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getConductores()
    {
        $sql = "SELECT c.id, c.n_documento, c.nombre, c.apellido_paterno, c.apellido_materno, 
                   c.categoria, c.licencia_conducir, c.fecha_expedicion, c.fecha_vencimiento, 
                   c.estado, e.razon_social
            FROM conductores c
            INNER JOIN empresas e ON e.id = c.id_empresa";
        return $this->selectAll($sql);
    }

    public function buscarConductores($tipo, $valor)
    {
        $sql = "SELECT c.*, e.razon_social, e.ruc 
            FROM conductores c 
            INNER JOIN empresas e ON c.id_empresa = e.id";

        if ($tipo === 'dni') {
            $sql .= " WHERE c.t_documento = 'DNI' AND c.n_documento LIKE ?";
        } elseif ($tipo === 'ce') {
            $sql .= " WHERE c.t_documento = 'CE' AND c.n_documento LIKE ?";
        } elseif ($tipo === 'ruc') {
            $sql .= " WHERE e.ruc LIKE ?";
        } else {
            return []; // tipo no válido
        }

        $searchTerm = '%' . $valor . '%';
        return $this->selectAll($sql, [$searchTerm]);
    }
    public function obtenerConductor($id)
    {
        $sql = "SELECT v.expediente, e.ruc, c.id_vpago, c.id, c.id_empresa, c.t_documento, 
                   c.n_documento AS numero_documento, c.nombre, c.apellido_paterno, 
                   c.apellido_materno, c.licencia_conducir,c.licencia_conducir, c.categoria, 
                   c.fecha_expedicion, c.fecha_vencimiento, c.estado, c.foto
            FROM conductores c
             INNER JOIN empresas e ON c.id_empresa = e.id
              INNER JOIN validacion_pago v ON  c.id_vpago = v.id
            WHERE c.id = ?";
        return $this->select($sql, [$id]);
    }

    public function obtenerConductorPorId($id)
    {
        $sql = "SELECT c.nombre, c.n_documento, e.razon_social AS empresa, e.ruc
            FROM conductores c
            INNER JOIN empresas e ON c.id_empresa = e.id
           
            WHERE c.id = ?";
        return $this->select($sql, [$id]);
    }

    public function accionConductor($id_conductor, $expediente)
    {
        $sqlCheck = "SELECT v.id AS id_validacion, p.codigo_pago
                 FROM validacion_pago v
                 INNER JOIN pagos p ON p.id = v.id_pago
                 WHERE v.expediente = ? AND v.estado = 1 AND p.codigo_pago = '120'";

        $result = $this->select($sqlCheck, [$expediente]);

        if (!empty($result)) {
            $id_validacion = $result['id_validacion'];

            $sqlUpdateConductor = "UPDATE conductores SET estado = 'Baja' WHERE id = ?";
            $this->save($sqlUpdateConductor, [$id_conductor]);

            $this->actualizarValidacionPago($id_validacion);
            $insertado = $this->insertarNomina($id_conductor);

            return $insertado ? true : false;
        }

        return false;
    }


    public function insertarNomina($id_conductor)
    {
        $sql = "INSERT INTO bajas_nomina (id_conductor) VALUES (?)";
        return  $this->insert($sql, [$id_conductor]);
    }

    public function actualizarValidacionPago($id_validacion)
    {
        // Paso 1: Restar 1 al número de conductores
        $this->save("UPDATE validacion_pago SET n_conductores = n_conductores - 1 WHERE id = ? AND n_conductores > 0", [$id_validacion]);

        // Paso 2: Si ya no quedan, cambiar el estado a 0
        $this->save("UPDATE validacion_pago SET estado = 0 WHERE id = ? AND n_conductores <= 0", [$id_validacion]);

        return true;
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
