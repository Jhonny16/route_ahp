<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 01/04/20
 * Time: 10:21 AM
 */

require_once '../model/licencia_certificado.php';
require_once '../model/tipo_licencias.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$proccess = $_POST['p_proccess'];

$empresa_id = $_POST['p_empresa_id'];

$tipo_licencia = $_POST['p_combo_licencias'];
$chofer = $_POST['p_combo_choferes'];
$vehiculo = $_POST['p_combo_vehiculos'];

$image = $_FILES['p_imagen'];
$name_image = $_POST['p_descripcion'];
$observacion = $_POST['p_observacion'];
$validacion = $_POST['p_validacion'];
$fechas = $_POST['p_fechas_licencias'];

$fecha_inicial = substr($fechas, 0, 10);
$fecha_final = substr($fechas, 13, 23);


if ($tipo_licencia == 2 and ($vehiculo == null or $vehiculo == "")) {
    Funciones::imprimeJSON(203, "Debe seleccionar un vehículo", $vehiculo);
    exit();

}
if ($tipo_licencia == 3 or $tipo_licencia == 4 and ($chofer == null or $chofer == "")) {
    Funciones::imprimeJSON(203, "Debe seleccionar un vehículo", $chofer);
    exit();

}
if ($tipo_licencia == 0) {
    Funciones::imprimeJSON(203, "Debe seleccionar tipo de licencia", $chofer);
    exit();
}

if ($proccess == 'create') {
    if ($image['type'] == 'image/jpeg' or $image['type'] == 'image/png') {


        try {

            $anio = 0;
            $type_licences = new tipo_licencias();
            $res_type = $type_licences->lista();

            for ($i = 0; $i < count($res_type); $i++) {
                if ($res_type[$i]['id'] == $tipo_licencia) {
                    $anio = $res_type[$i]['vigencia'];
                }
            }
            $date1 = new DateTime($fecha_inicial);
            $date2 = new DateTime($fecha_final);
            $diff = $date1->diff($date2);

            if (round($diff->days / 365, 0) == $anio) {

            } else {
                Funciones::imprimeJSON(203, "Tener en cuenta que la validez es por " . $anio . " años !!!.
            Verique el rango de fecha ingresada", $anio);
                exit();
            }


            $explode = explode('.', $image['name']);
            $extension = $explode[1];
            $name_encriptado = md5($image['tmp_name']) . '.' . $extension;
            $ruta = '../images/' . '' . $name_encriptado . '';

            $res = move_uploaded_file($image['tmp_name'], $ruta);

            $obj = new licencia_certificado();
            $obj->setDescripcion($name_image);
            $obj->setImagen($name_encriptado);
            $obj->setFechaInicial($fecha_inicial);
            $obj->setFechaRevalidacion($fecha_final);
            $obj->setTipoId($tipo_licencia);
            $obj->setChoferId($chofer);
            $obj->setVehiculoId($vehiculo);
            $obj->setEmpresaId($empresa_id);


            if ($res) {

                $result = $obj->create();
                if ($result) {
                    Funciones::imprimeJSON(200, "Archivo guardado. : ", $image);
                } else {
                    Funciones::imprimeJSON(203, "Error al guardar archivo : ", $image);

                }
            } else {
                Funciones::imprimeJSON(203, "No se guardó la imagen : ", $image);

            }


        } catch (Exception $exc) {

            Funciones::imprimeJSON(500, $exc->getMessage(), "");
        }

    } else {
        Funciones::imprimeJSON(203, "Debe seleccionar un archivo tipo imagen(jpg,png) ", $image);
        exit();
    }

} else {

    if ($empresa_id  == null or $empresa_id == "") {
        Funciones::imprimeJSON(203, "Debe seleccionar una empresa", $empresa_id);
        exit();
    }


    try {

        $anio = 0;
        $type_licences = new tipo_licencias();
        $res_type = $type_licences->lista();

        for ($i = 0; $i < count($res_type); $i++) {
            if ($res_type[$i]['id'] == $tipo_licencia) {
                $anio = $res_type[$i]['vigencia'];
            }
        }
        $date1 = new DateTime($fecha_inicial);
        $date2 = new DateTime($fecha_final);
        $diff = $date1->diff($date2);

        if (round($diff->days / 365, 0) == $anio) {

        } else {
            Funciones::imprimeJSON(203, "Tener en cuenta que la validez es por " . $anio . " años !!!.
            Verique el rango de fecha ingresada", $anio);
            exit();
        }


        $obj = new licencia_certificado();
        $obj->setObservacion($observacion);
        $obj->setDescripcion($name_image);
        $obj->setValidate($validacion);
        $obj->setTipoId($tipo_licencia);
        $obj->setChoferId($chofer);
        $obj->setVehiculoId($vehiculo);
        $obj->setEmpresaId($empresa_id);

        $result = $obj->update();
        if ($result) {
            Funciones::imprimeJSON(200, "Actualizado! ", $empresa_id);
        } else {
            Funciones::imprimeJSON(203, "Error al actualizar archivo : ", $image);

        }

    } catch (Exception $exc) {

        Funciones::imprimeJSON(500, $exc->getMessage(), $empresa_id);
    }


}



