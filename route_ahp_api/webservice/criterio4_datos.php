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
    $resultado = $obj->c_antiguedad_vehicular();

    if($resultado){
        $suma = 0;
        for($i=0; $i<count($resultado); $i++){
            $suma = $suma + $resultado[$i]['promedio_antiguedad'];
        }

        $suma_valor_intermendio = 0;

        for($i=0; $i<count($resultado); $i++){
            if($resultado[$i]['promedio_antiguedad'] == 0){
                $resultado[$i]['valor_intermedio'] =  round( $resultado[$i]['promedio_antiguedad']  ,3);

            }else{
                $resultado[$i]['valor_intermedio'] =  round( $suma/$resultado[$i]['promedio_antiguedad']  ,3);

            }
            $suma_valor_intermendio = $suma_valor_intermendio + $resultado[$i]['valor_intermedio'];
        }


        for($i=0; $i<count($resultado); $i++){
//            if($resultado[$i]['promedio_antiguedad'] == 0){
//                $resultado[$i]['valor'] =  round( $resultado[$i]['promedio_antiguedad'] ,3);
//
//            }else{
//                $resultado[$i]['valor'] =  round( $resultado[$i]['promedio_antiguedad']/ $suma  ,3);
//
//            }
            if($resultado[$i]['valor_intermedio'] == 0){
                $resultado[$i]['valor']  = round($resultado[$i]['valor_intermedio'],3);

            }else{
                $resultado[$i]['valor']  = round($resultado[$i]['valor_intermedio'] / $suma_valor_intermendio,3);

            }
            $obj->create_update($resultado[$i]['id'], 4, $resultado[$i]['valor']);
        }

        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}