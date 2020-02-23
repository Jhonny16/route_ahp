<?php

require_once '../model/persona.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
  Funciones::imprimeJSON(500, "Debe especificar un token", "");
  exit();
}
$token = $_SERVER["HTTP_TOKEN"];
$operation = json_decode(file_get_contents("php://input"))->operation;
if ($operation != 'Nuevo') {
  $id = json_decode(file_get_contents("php://input"))->id;

}

$nombre_completo = json_decode(file_get_contents("php://input"))->nombre_completo;
$documento_identidad = json_decode(file_get_contents("php://input"))->documento_identidad;
$celular = json_decode(file_get_contents("php://input"))->celular;
$direccion = json_decode(file_get_contents("php://input"))->direccion;
$estado = json_decode(file_get_contents("php://input"))->estado;
$fn = json_decode(file_get_contents("php://input"))->fn;
$sexo = json_decode(file_get_contents("php://input"))->sexo;


try {


  date_default_timezone_set("America/Lima");
  $fecha_registro = date('Y-m-d');

  if (strlen($documento_identidad) == 8) {
    $datetime1 = new DateTime($fn);
    $datetime2 = new DateTime($fecha_registro);
    $interval = $datetime1->diff($datetime2);
    $dias = $interval->format('%R%a');
    $anio = (float)$dias / 365;
    if ($anio < 18) {
      Funciones::imprimeJSON(500, "La fecha de nacimiento del nuevo registro no supera los 18 años", "");
      exit();
    }
  }


  if ($operation == 'Nuevo') {

    $objper = new persona();
    $objper->setNombreCompleto($nombre_completo);
    $objper->setDocumentoIdentidad($documento_identidad);
    $objper->setCelular($celular);
    $objper->setDireccion($direccion);
    $objper->setEstado($estado);
    $objper->setFechaNacimiento($fn);
    $objper->setSexo($sexo);
    $objper->setApoderadoId(null);
    $objper->setUsuario(true);
    $objper->setRolId(2);
    $objper->setEmpresa(null);
    $objper->setFechaRegistro($fecha_registro);

    $result = $objper->create();
    if ($result) {
      Funciones::imprimeJSON(200, "Agregado Correcto", $result);
    } else {
      Funciones::imprimeJSON(203, "Error al momento de agregar", "");
    }


  } else {

    $objper = new persona();
    $objper->setNombreCompleto($nombre_completo);
    $objper->setDocumentoIdentidad($documento_identidad);
    $objper->setCelular($celular);
    $objper->setDireccion($direccion);
    $objper->setEstado($estado);
    $objper->setFechaNacimiento($fn);
    $objper->setSexo($sexo);
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
