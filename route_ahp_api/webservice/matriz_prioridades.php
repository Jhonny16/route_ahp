<?php

header('Access-Control-Allow-Origin: *');

require_once '../model/criterio.php';
require_once '../model/persona_criterio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

try {
    $obj = new criterio();
    $objpc = new persona_criterio();

    $criterios = $obj->criterios_lista();
    $norma = $objpc->datos_normalizados();

    $nueva_matriz = [];

    for ($i = 0; $i < count($norma); $i++) {

        $nueva_matriz[$i]['persona_id'] = $norma[$i]['persona_id'];
        $nueva_matriz[$i]['criterio1'] = $norma[$i]['criterio1'] * $criterios[0]['valor'];
        $nueva_matriz[$i]['criterio2'] = $norma[$i]['criterio2'] * $criterios[1]['valor'];
        $nueva_matriz[$i]['criterio3'] = $norma[$i]['criterio3'] * $criterios[2]['valor'];
        $nueva_matriz[$i]['criterio4'] = $norma[$i]['criterio4'] * $criterios[3]['valor'];

        $m_final[$i]['persona_id'] = $nueva_matriz[$i]['persona_id'];
        $m_final[$i]['empresa'] = $norma[$i]['empresa'];
        $m_final[$i]['value'] = round((($nueva_matriz[$i]['criterio1'] +
                $nueva_matriz[$i]['criterio2'] +
                $nueva_matriz[$i]['criterio3'] +
                $nueva_matriz[$i]['criterio4'])),3);

        $objpc->add_value($nueva_matriz[$i]['persona_id'], $m_final[$i]['value']);
    }


    Funciones::imprimeJSON(200, "", $m_final);


} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}