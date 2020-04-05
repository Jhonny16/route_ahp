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
    $resultado = $obj->c_precio();

    if($resultado){
        $suma = 0;
        for($i=0; $i<count($resultado); $i++){
            $suma = $suma + $resultado[$i]['precio'];
        }

        $suma_valor_intermendio = 0;

        for($i=0; $i<count($resultado); $i++){
            if($resultado[$i]['precio'] == 0){
                $resultado[$i]['valor_intermedio'] =  round( $resultado[$i]['precio']  ,3);

            }else{
                $resultado[$i]['valor_intermedio'] =  round( $suma/$resultado[$i]['precio']  ,3);
            }
            $suma_valor_intermendio = $suma_valor_intermendio + $resultado[$i]['valor_intermedio'];

        }

        for($i=0; $i<count($resultado); $i++){
            if($resultado[$i]['valor_intermedio'] == 0){
                $resultado[$i]['valor']  = round($resultado[$i]['valor_intermedio'],3);

            }else{
                $resultado[$i]['valor']  = round($resultado[$i]['valor_intermedio'] / $suma_valor_intermendio,3);

            }
            $obj->create_update($resultado[$i]['id'], 2, $resultado[$i]['valor']);

//            $resultado[$i]['valor'] =  round( $resultado[$i]['precio']/ $suma  ,3);
//            $obj->create_update($resultado[$i]['id'], 2, $resultado[$i]['valor']);
        }

        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}