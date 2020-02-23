<?php


header('Access-Control-Allow-Origin: *');

require_once '../model/usuario.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

//if (!isset($_POST["token"])) {
//    Funciones::imprimeJSON(500, "Debe especificar un token", "");
//    exit();
//}
//
//$token = $_POST["token"];
$id = $_GET['p_id'];

try {
    //if (validarToken($token)) {
        $obj = new usuario();

        $obj->setId($id);
        $resultado = $obj->user_get();

        $list = array();
        $datos = array(
            'codigo_usuario' => $resultado["id"],
            'nombre_usuario' => $resultado["nombre_usuario"],
            'tipo_usuario' => $resultado["tipo_usuario"]
        );
        for ($i = 0; $i < count($resultado); $i++) {

//        $datos = array(
//            "codigo_usuario" => $resultado[$i]["codigo_usuario"],
//            "nombre_usuario" => $resultado[$i]["nombre_usuario"]
//        );
//
//        $list[$i] = $datos;
        }
        Funciones::imprimeJSON(200, "", $datos);
    //}
} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}