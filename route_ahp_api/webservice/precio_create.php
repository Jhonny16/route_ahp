<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 23/03/20
 * Time: 08:35 AM
 */
require_once '../model/precio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$empresa_id = json_decode(file_get_contents("php://input"))->empresa_id;
$colegio_id = json_decode(file_get_contents("php://input"))->colegio_id;
$precio = json_decode(file_get_contents("php://input"))->precio;
$fecha_inicial = json_decode(file_get_contents("php://input"))->fecha_inicial;
$fecha_final = json_decode(file_get_contents("php://input"))->fecha_final;
$descripcion = json_decode(file_get_contents("php://input"))->descripcion;

try {
    $obj = new precio();

    $obj->setEmpresaId($empresa_id);
    $obj->setColegioId($colegio_id);
    $obj->setCosto($precio);
    $obj->setFechaInicial($fecha_inicial);
    $obj->setFechaFinal($fecha_final);
    $obj->setDescripcion($descripcion);

    $resultado = $obj->create();
    if($resultado){
        Funciones::imprimeJSON(200, "Se ha creado un nuevo precio!  ",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hay datos","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}

