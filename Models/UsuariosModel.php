<?php
class UsuariosModel extends Query
{
    private $usuario, $nombre, $clave, $id, $estado;
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuario($usuario, $clave)
    {
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave' AND estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    // CORREGIDO
    public function actualizarFoto($id, $nombreFoto)
    {
        $sql = "UPDATE usuarios SET perfil = ? WHERE id = ?";
        return $this->save($sql, [$nombreFoto, $id]);
    }

    public function getUsers($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        return $this->select($sql, [$id]);
    }



    public function getUsuarios()
    {
        $sql = "SELECT * FROM usuarios";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function registrarUsuario($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $clave, $perfil)
    {
        $verificar = "SELECT id FROM usuarios WHERE usuario = ?";
        $existe = $this->select($verificar, [$usuario]);

        if (!empty($existe)) {
            return "existe";
        } else {
            $sql = "INSERT INTO usuarios (usuario, dni, nombre, apellido_paterno, apellido_materno, correo, telefono, direccion, clave, perfil)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $datos = array($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $clave, $perfil);
            $data = $this->save($sql, $datos);
            return $data ? "ok" : "error";
        }
    }

    public function registrarUsers($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $clave, $perfil, $rol)
    {
        $verificar = "SELECT id FROM usuarios WHERE usuario = ?";
        $existe = $this->select($verificar, [$usuario]);

        if (!empty($existe)) {
            return "existe";
        } else {
            $sql = "INSERT INTO usuarios (usuario, dni, nombre, apellido_paterno, apellido_materno, correo, telefono, direccion, clave, perfil, rol)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $datos = array($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $clave, $perfil, $rol);
            $data = $this->save($sql, $datos);
            return $data ? "ok" : "error";
        }
    }

    public function modificarDatoPersonal($dni, $nombre, $apellido_paterno, $apellido_materno, $telefono, $direccion, $id)
    {
        $sql = "UPDATE usuarios SET dni = ?, nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, direccion = ? WHERE id = ?";
        $datos = [$dni, $nombre, $apellido_paterno, $apellido_materno, $telefono, $direccion, $id];
        return $this->save($sql, $datos); // Método `save` que ejecuta query preparada
    }



    public function modificarUsuario($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $rol, $id)
    {
        $sql = "UPDATE usuarios SET usuario = ?, dni = ?, nombre = ?, apellido_paterno = ?, apellido_materno = ?, correo = ?, telefono = ?, direccion = ?, rol = ? WHERE id = ?";
        $datos = array($usuario, $dni, $nombre, $apellido_paterno, $apellido_materno, $correo, $telefono, $direccion, $rol, $id);
        $data = $this->save($sql, $datos);
        return $data == 1 ? "modificado" : "error";
    }

    public function editarUser($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function accionUser($estado, $id)
    {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql, $datos);
        return $data;
    }
    public function getPermisosUsuario($id_usuario)
    {
        $sql = "SELECT p.nombre FROM permisos p 
            INNER JOIN detalle_permisos dp ON p.id = dp.id_permiso 
            WHERE dp.id_usuario = $id_usuario";
        return $this->selectAll($sql);
    }

    public function getPermisos()
    {
        $sql = "SELECT * FROM permisos";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function getDetallePermisos($id)
    {
        $sql = "SELECT * FROM detalle_permisos WHERE id_usuario = $id";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function deletePermisos($id)
    {
        $sql = "DELETE FROM detalle_permisos WHERE id_usuario = ?";
        $datos = array($id);
        $data = $this->save($sql, $datos);
        return $data;
    }
    public function actualizarPermisos($usuario, $permiso)
    {
        $sql = "INSERT INTO detalle_permisos(id_usuario, id_permiso) VALUES (?,?)";
        $datos = array($usuario, $permiso);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
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
    public function actualizarPass($clave, $id)
    {
        $sql = "UPDATE usuarios SET clave = ? WHERE id = ?";
        $datos = array($clave, $id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }
}
