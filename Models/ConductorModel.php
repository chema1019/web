<?php
class ConductorModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }
    // ✅ Buscar expediente (ya adaptado por ti, lo dejamos igual pero agregamos n_conductores y id)
    public function getBuscarExpediente($expediente)
    {
        $sql = "SELECT v.id AS id_vpago, v.expediente, v.n_conductores, 
                   e.id AS id_empresa, e.ruc,e.razon_social, p.id, v.id_pago, p.codigo_pago
            FROM validacion_pago v
            INNER JOIN pagos p ON p.id = v.id_pago
            INNER JOIN empresas e ON e.id = p.id_empresa
            WHERE v.expediente = ? AND p.codigo_pago = ? AND v.estado = 1";

        return $this->select($sql, [$expediente, '110']);
    }


    // ✅ Buscar conductor por tipo y número de documento
    public function obtenerConductorPorDocumento($tipo, $numero)
    {
        $sql = "SELECT * FROM record_conductores WHERE t_documento = ? AND n_documento = ?";
        return $this->select($sql, [$tipo, $numero]);
    }

    // ✅ Insertar nuevo conductor
    public function registrarConductor(array $data)
    {
        // 1. Indicamos columnas en el mismo orden de la tabla:
        $sql = "INSERT INTO conductores (
                t_documento,
                n_documento,
                nombre,
                apellido_paterno,
                   apellido_materno,
                licencia_conducir,
                categoria,
                fecha_expedicion,
                fecha_vencimiento,
                estado,
                foto,
                id_record,
                id_empresa,
                id_vpago
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // 2. Creamos el arreglo de valores en el mismo orden:
        $array = [
            $data['t_documento'],        // corresponde a t_documento
            $data['n_documento'],        // corresponde a n_documento
            $data['nombre'],             // corresponde a nombre
            $data['apellido_paterno'],
            $data['apellido_materno'],         // corresponde a apellido (paterno + materno concatenados)
            $data['licencia_conducir'],  // corresponde a licencia_conducir
            $data['categoria'],          // corresponde a categoria
            $data['fecha_expedicion'],   // corresponde a fecha_expedicion
            $data['fecha_vencimiento'],  // corresponde a fecha_vencimiento
            $data['estado'],             // corresponde a estado (activo/baja)
            $data['foto'],               // corresponde a foto (nombre de archivo o NULL)
            $data['id_record'],          // corresponde a id_record
            $data['id_empresa'],         // corresponde a id_empresa (FK)
            $data['id_vpago']            // corresponde a id_vpago   (FK)
        ];

        return $this->insert($sql, $array);
    }
    public function getValidacionByExpediente($expediente, $id_validacion)
    {
        $sql = "SELECT * FROM validacion_pago WHERE expediente = ? AND id = ? AND estado = 1";
        return $this->select($sql, [$expediente, $id_validacion]);
    }

    public function verificarConductorExiste($numero_documento)
    {
        $sql = "SELECT id FROM conductores WHERE n_documento = ?";

        return $this->select($sql, [$numero_documento]);
    }



    public function actualizarValidacionPago($id_validacion)
    {
        // Paso 1: Restar 1 al número de conductores
        $this->save("UPDATE validacion_pago SET n_conductores = n_conductores - 1 WHERE id = ? AND n_conductores > 0", [$id_validacion]);

        // Paso 2: Si ya no quedan, cambiar el estado a 0
        $this->save("UPDATE validacion_pago SET estado = 0 WHERE id = ? AND n_conductores <= 0", [$id_validacion]);

        return true;
    }



    public function cerrarExpediente($id_validacion)
    {
        $sql = "UPDATE validacion_pago SET estado = 0 WHERE id = ?";
        return $this->update($sql, [$id_validacion]);
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
