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
    $resultado = $obj->c_atencion();

    if($resultado){
        $suma = 0;
        $menor = 0;
        $menor = $resultado[0]['atencion'];
        for($i=0; $i<count($resultado); $i++){
            $suma = $suma + abs($resultado[$i]['atencion']);



            if($resultado[$i]['atencion'] < $menor){
                $menor = $resultado[$i]['atencion'];
            }
        }
        $suma_intervalo = 0;
        for($i=0; $i<count($resultado); $i++){
            $resultado[$i]['intervalo'] = abs($resultado[$i]['atencion'] + abs($menor));
            $suma_intervalo = $suma_intervalo + $resultado[$i]['intervalo'];
        }

        for($i=0; $i<count($resultado); $i++){
            $resultado[$i]['valor'] =  round( $resultado[$i]['intervalo']/$suma_intervalo  ,3);

            $obj->create_update($resultado[$i]['id'], 4, $resultado[$i]['valor']);
        }


        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}