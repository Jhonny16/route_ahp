<?php


header('Access-Control-Allow-Origin: *');

require_once '../model/persona.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}
$token = $_SERVER["HTTP_TOKEN"];
$operation= json_decode(file_get_contents("php://input"))->operation;
if($operation == 'Nuevo'){
    $rol_id = json_decode(file_get_contents("php://input"))->rol_id;

}else{
    $id = json_decode(file_get_contents("php://input"))->id;
}

$dni = json_decode(file_get_contents("php://input"))->dni;
$nombres = json_decode(file_get_contents("php://input"))->nombres;
$ap_paterno= json_decode(file_get_contents("php://input"))->ap_paterno;
$ap_materno= json_decode(file_get_contents("php://input"))->ap_materno;
$sexo = json_decode(file_get_contents("php://input"))->sexo;
$fn = json_decode(file_get_contents("php://input"))->fn;
$celular = json_decode(file_get_contents("php://input"))->celular;
$direccion= json_decode(file_get_contents("php://input"))->direccion;
$correo = json_decode(file_get_contents("php://input"))->correo;
$estado= json_decode(file_get_contents("php://input"))->estado;
$zona_id= json_decode(file_get_contents("php://input"))->zona_id;
$fecha_registro= json_decode(file_get_contents("php://input"))->fecha_registro;



try {
//    if (validarToken($token)) {

    $datetime1 = new DateTime($fn);
    $datetime2 = new DateTime($fecha_registro);
    $interval = $datetime1->diff($datetime2);
    $dias = $interval->format('%R%a');
    $anio = (float)$dias / 365;
    if($anio < 18){
        Funciones::imprimeJSON(500, "La fecha de nacimiento del nuevo registro no supera los 18 años", "");
        exit();
    }

    if ($operation == 'Nuevo') {

        $objper = new persona();
        $objper->setDni($dni);
        $objper->setNombres($nombres);
        $objper->setApPaterno($ap_paterno);
        $objper->setApMaterno($ap_materno);
        $objper->setSexo($sexo);
        $objper->setFn($fn);
        $objper->setCelular($celular);
        $objper->setDireccion($direccion);
        $objper->setCorreo($correo);
        $objper->setEstado($estado);
        $objper->setZonaId($zona_id);
        $objper->setRolId($rol_id);
        $objper->setFechaRegistro($fecha_registro);

        $result = $objper->create();
        if ($result) {
            Funciones::imprimeJSON(200, "Agregado Correcto", $result);
        } else {
            Funciones::imprimeJSON(203, "Error al momento de agregar", "");
        }


    } else {

        $objper = new persona();

        $objper->setDni($dni);
        $objper->setNombres($nombres);
        $objper->setApPaterno($ap_paterno);
        $objper->setApMaterno($ap_materno);
        $objper->setSexo($sexo);
        $objper->setFn($fn);
        $objper->setCelular($celular);
        $objper->setDireccion($direccion);
        $objper->setCorreo($correo);
        $objper->setEstado($estado);
        $objper->setZonaId($zona_id);
        $objper->setId($id);

        $result = $objper->update();

        if ($result) {
            Funciones::imprimeJSON(200, "Se Actualizo Correctamente", "");
        } else {
            Funciones::imprimeJSON(203, "Error al momento de agregar", "");
        }


    }


} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}