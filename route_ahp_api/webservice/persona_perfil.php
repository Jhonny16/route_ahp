<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 31/03/20
 * Time: 08:39 PM
 */
require_once '../model/persona.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$persona_id = json_decode(file_get_contents("php://input"))->persona_id;


try {
    $obj = new persona();
    $obj->setId($persona_id);
    $resultado = $obj->perfil();
    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}