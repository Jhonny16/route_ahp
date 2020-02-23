<?php

header('Access-Control-Allow-Origin: *');

require_once '../model/persona_criterio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

try {
    $objpc = new persona_criterio();

    $norma = $objpc->datos_normalizados();
    if($norma){
        Funciones::imprimeJSON(200, "", $norma);
    }



} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}