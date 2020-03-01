<?php

require_once '../model/referencia.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';


if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$turno = json_decode(file_get_contents("php://input"))->turno;
$grado = json_decode(file_get_contents("php://input"))->grado;
$seccion = json_decode(file_get_contents("php://input"))->seccion;
$hora_entrada = json_decode(file_get_contents("php://input"))->hora_entrada;
$hora_salida = json_decode(file_get_contents("php://input"))->hora_salida;
$persona_id = json_decode(file_get_contents("php://input"))->persona_id;
$colegio_id = json_decode(file_get_contents("php://input"))->colegio_id;


try {


    $objper = new referencia();
    $objper->setTurno($turno);
    $objper->setGrado($grado);
    $objper->setSeccion($seccion);
    $objper->setHoraEntrada($hora_entrada);
    $objper->setHoraSalida($hora_salida);
    $objper->setPersonaId($persona_id);
    $objper->setColegioId($colegio_id);

    $result = $objper->create();
    if ($result) {
        Funciones::imprimeJSON(200, "Agregado Correcto", $result);
    } else {
        Funciones::imprimeJSON(203, "Error al momento de agregar", "");
    }


} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
