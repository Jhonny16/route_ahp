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
    $obj = new persona_criterio();
    $resultado = $obj->c_calidad_servicio();

    if($resultado){
        $suma = 0;
        for($i=0; $i<count($resultado); $i++){
            $suma = $suma + $resultado[$i]['calificacion'];
        }

        for($i=0; $i<count($resultado); $i++){
            if($resultado[$i]['calificacion'] == 0){
                $resultado[$i]['valor'] =  round( $resultado[$i]['calificacion'] ,3);

            }else{
                $resultado[$i]['valor'] =  round( $resultado[$i]['calificacion']/ $suma  ,3);

            }
            $obj->create_update($resultado[$i]['id'], 1, $resultado[$i]['valor']);
        }

        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}