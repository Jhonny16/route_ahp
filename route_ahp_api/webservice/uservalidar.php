<?php

require_once '../model/usuario.php';
require_once '../util/funciones/Funciones.clase.php';

//$rol = json_decode(file_get_contents("php://input")) -> p_rol;
$dni = json_decode(file_get_contents("php://input")) -> p_dni;
$clave = json_decode(file_get_contents("php://input")) -> p_clave;

//Funciones::imprimeJSON(200, "Bienvenido a la aplicacion", $clave);
if ($dni === "" or $clave== ""){
    Funciones::imprimeJSON(500, "Falta completar los datos requeridos", "");
    exit();
}


try {
    $objSesion = new usuario();
    $objSesion->setDni($dni);
    $objSesion->setCodigo($dni);
    $objSesion->setClave($clave);
    $resultado = $objSesion->auth();

    if ($resultado["estado"] == 'A') {
        require_once 'tokengenerar.php';
        $token = generarToken(null,3600);
        $resultado["token"] = $token;

        Funciones::imprimeJSON(200, "Bienvenido a la aplicacion", $resultado);
    } else {
        Funciones::imprimeJSON(500, "No esta Activo", $resultado);
    }
} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
