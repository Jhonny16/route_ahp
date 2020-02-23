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
$dni= json_decode(file_get_contents("php://input"))->dni;
$ap_paterno= json_decode(file_get_contents("php://input"))->ap_paterno;
$ap_materno= json_decode(file_get_contents("php://input"))->ap_materno;
$nombres= json_decode(file_get_contents("php://input"))->nombres;
$sexo= json_decode(file_get_contents("php://input"))->sexo;
$fn= json_decode(file_get_contents("php://input"))->fn;
$celular= json_decode(file_get_contents("php://input"))->celular;
$direccion= json_decode(file_get_contents("php://input"))->direccion;
$correo= json_decode(file_get_contents("php://input"))->correo;
$cambiar_password= json_decode(file_get_contents("php://input"))->cambiar_password;
$password= json_decode(file_get_contents("php://input"))->password;
$id = json_decode(file_get_contents("php://input"))->id;


try {
    $objeto = new persona();
    $objeto->setDni($dni);
    $objeto->setApPaterno($ap_paterno);
    $objeto->setApMaterno($ap_materno);
    $objeto->setNombres($nombres);
    $objeto->setSexo($sexo);
    $objeto->setFn($fn);
    $objeto->setCelular($celular);
    $objeto->setDireccion($direccion);
    $objeto->setCorreo($correo);
    $objeto->setClave($password);
    $objeto->setId($id);
    $res = $objeto->update_perfil($cambiar_password);

    if ($res == true) {
        Funciones::imprimeJSON(200, "Se actualizo el perfil", $res);
    } else {
        Funciones::imprimeJSON(203, "No actualizo perfil", $res);
    }


} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
