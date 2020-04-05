<?php

require_once '../model/vehiculo.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';


$operation = json_decode(file_get_contents("php://input"))->operation;
if ($operation != 'Nuevo') {
    $id = json_decode(file_get_contents("php://input"))->id;

}

$placa = json_decode(file_get_contents("php://input"))->placa;
$marca = json_decode(file_get_contents("php://input"))->marca;
$color = json_decode(file_get_contents("php://input"))->color;
$empresa_id = json_decode(file_get_contents("php://input"))->empresa_id;
$estado = json_decode(file_get_contents("php://input"))->estado;
$anio_fabricacion = json_decode(file_get_contents("php://input"))->anio_fabricacion;

try {

    if ($operation == 'Nuevo') {

        $obj = new vehiculo();
        $obj->setPlaca($placa);
        $obj->setMarca($marca);
        $obj->setColor($color);
        $obj->setEmpresaId($empresa_id);
        $obj->setEstado($estado);
        $obj->setAnioFabricacion($anio_fabricacion);

        $result = $obj->create();
        if ($result) {
            Funciones::imprimeJSON(200, "Agregado Correcto", $result);
        } else {
            Funciones::imprimeJSON(203, "Error al momento de agregar", "");
        }


    } else {

        $obj = new vehiculo();
        $obj->setPlaca($placa);
        $obj->setMarca($marca);
        $obj->setColor($color);
        $obj->setEmpresaId($empresa_id);
        $obj->setEstado($estado);
        $obj->setAnioFabricacion($anio_fabricacion);
        $obj->setId($id);

        $result = $obj->update();

        if ($result) {
            Funciones::imprimeJSON(200, "Se actualizó correctamente", "");
        } else {
            Funciones::imprimeJSON(203, "Error al momento de actualizar", "");
        }


    }


} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
