<?php
class Query extends Conexion
{
    private $pdo, $con, $sql, $datos;
    public function __construct()
    {
        $this->pdo = new Conexion();
        $this->con = $this->pdo->conect();
    }
    public function select(string $sql, array $params = [])
    {
        try {
            $this->sql = $sql;
            $resul = $this->con->prepare($this->sql);
            $resul->execute($params);
            $data = $resul->fetch(PDO::FETCH_ASSOC);
            return $data ?: null;
        } catch (PDOException $e) {
            // Puedes registrar el error en un archivo o mostrarlo temporalmente
            file_put_contents('log_error.txt', $e->getMessage());
            return null;
        }
    }


    public function selectAll(string $sql, array $params = [])
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute($params); // <-- aquí se pasan los parámetros
        $data = $resul->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function save(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);
        if ($data) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function insert(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);
        if ($data) {
            $res = $this->con->lastInsertId();;
        } else {
            $res = 0;
        }
        return $res;
    }
    public function update($sql, $params = [])
    {
        $query = $this->con->prepare($sql);
        return $query->execute($params);
    }
}
