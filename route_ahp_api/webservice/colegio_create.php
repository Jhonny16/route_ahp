<?php

require_once '../model/colegio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';


if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$operation = json_decode(file_get_contents("php://input"))->operation;
if ($operation != 'Nuevo') {
    $id = json_decode(file_get_contents("php://input"))->id;

}

$numero = json_decode(file_get_contents("php://input"))->numero;
$nombre = json_decode(file_get_contents("php://input"))->nombre;
$direccion = json_decode(file_get_contents("php://input"))->direccion;
//$latitud = json_decode(file_get_contents("php://input"))->latitud;
//$longitud = json_decode(file_get_contents("php://input"))->longitud;
$director = json_decode(file_get_contents("php://input"))->director;


try {

    if ($operation == 'Nuevo') {
        $obj = new colegio();
        $obj->setNumero($numero);
        $obj->setNombre($nombre);
        $obj->setDireccion($direccion);
//        $obj->setLatitud($latitud);
//        $obj->setLongitud($longitud);
        $obj->setDirector($director);

        $result = $obj->create();
        if ($result) {
            Funciones::imprimeJSON(200, "Agregado Correcto", $result);
        } else {
            Funciones::imprimeJSON(203, "Error al momento de agregar", "");
        }
    }else{
        $obj = new colegio();
        $obj->setNumero($numero);
        $obj->setNombre($nombre);
        $obj->setDireccion($direccion);
//        $obj->setLatitud($latitud);
//        $obj->setLongitud($longitud);
        $obj->setDirector($director);
        $obj->setId($id);

        $result = $obj->update();
        if ($result) {
            Funciones::imprimeJSON(200, "Se actualizó correctamente\"", $result);
        } else {
            Funciones::imprimeJSON(203, "Error al momento de actualizar", "");
        }
    }



} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
