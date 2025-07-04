<?php
function strClean($cadena)
{
    $cadena = trim($cadena);
    $cadena = stripslashes($cadena);
    $cadena = preg_replace(['/\s+/','/^\s|\s$/'], [' ', ''], $cadena);

    $prohibido = [
        '<script>', '</script>', '<script type=>', '<script src>',
        'SELECT * FROM', 'DELETE FROM', 'INSERT INTO', 'SELECT COUNT(*) FROM',
        'DROP TABLE', "OR '1'='1", 'OR ´1´=´1', 'IS NULL',
        'LIKE "', "LIKE '", 'LIKE ´', 'OR "a"="a', "OR 'a'='a", 'OR ´a´=´a',
        '--', '^', '[', ']', '=='
    ];

    $cadena = str_ireplace($prohibido, '', $cadena);
    return $cadena;
}

function tienePermiso($nombrePermiso)
{
    // Si el usuario es administrador, tiene acceso total
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') {
        return true;
    }

    // Si no hay permisos cargados, no tiene acceso
    if (!isset($_SESSION['permisos'])) {
        return false;
    }

    // Recorremos los permisos asignados
    foreach ($_SESSION['permisos'] as $permiso) {
        if ($permiso['nombre'] == $nombrePermiso) {
            return true;
        }
    }

    return false;
}

