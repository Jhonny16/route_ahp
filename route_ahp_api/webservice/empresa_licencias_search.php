<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 02/04/20
 * Time: 12:35 AM
 */

header('Access-Control-Allow-Origin: *');

require_once '../model/licencia_certificado.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}




$empresa_id = json_decode(file_get_contents("php://input"))->empresa_id;
$chofer_id = json_decode(file_get_contents("php://input"))->chofer_id;
$vehiculo_id = json_decode(file_get_contents("php://input"))->vehiculo_id;
$tipo_licencia = json_decode(file_get_contents("php://input"))->tipo_id;


if ($tipo_licencia == 0 or $tipo_licencia == null){
    Funciones::imprimeJSON(203, "El campo tipo de licencia es obligatoria para la búsqueda", $tipo_licencia);
    exit();

}
if ($tipo_licencia == 2 and ($vehiculo_id == null or $vehiculo_id == "") ){
    Funciones::imprimeJSON(203, "Debe seleccionar un vehículo", $vehiculo_id);
    exit();

}
if ($tipo_licencia == 3 or $tipo_licencia == 4 and ($chofer_id == null or $chofer_id == "") ){
    Funciones::imprimeJSON(203, "Debe seleccionar un vehículo", $chofer_id);
    exit();

}
if($tipo_licencia == 0 ){
    Funciones::imprimeJSON(203, "Debe seleccionar tipo de licencia", $tipo_licencia);
    exit();
}

try {
    $obj = new licencia_certificado();
    $obj->setEmpresaId($empresa_id);
    $obj->setChoferId($chofer_id);
    $obj->setVehiculoId($vehiculo_id);
    $obj->setTipoId($tipo_licencia);

    $resultado = $obj->licencias_list();

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hubo resultados en la búsqueda","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}