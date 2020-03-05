<?php

require_once '../model/solicitud.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$empresa_id = json_decode(file_get_contents("php://input"))->empresa_id;
$parametro = json_decode(file_get_contents("php://input"))->parametro;
$fecha_inicio = json_decode(file_get_contents("php://input"))->fecha_inicio;
$fecha_fin = json_decode(file_get_contents("php://input"))->fecha_fin;


try {
    $obj = new solicitud();
    $obj->setEmpresaId($empresa_id);
    $resultado = $obj->lista_solicitudes_por_empresa($parametro, $fecha_inicio, $fecha_fin);
    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}