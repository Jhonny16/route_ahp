<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 26/03/20
 * Time: 11:27 AM
 */

require_once '../model/conductor_licencia.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$proccess = $_POST['proccess'];
$conductor_id = $_POST['conductor_id'];
$name_image = $_POST['name_image'];
$fecha_revalidacion = $_POST['fecha_revalidacion'];
$observacion = $_POST['observacion'];
$validate = $_POST['r_validate'];
$id= $_POST['license_id'];
$image = $_FILES['image'];
//$image['type'] != 'image/jpg' or $image['type'] != 'image/png' or
if($proccess == 'Create'){
    if ($image['type'] == 'image/jpeg' or $image['type'] == 'image/png') {
        $explode = explode('.', $image['name']);
        $extension = $explode[1];
        $name_encriptado = md5($image['tmp_name']) . '.' . $extension;
        $ruta = '../images/driver_license/' . $name_encriptado;

        move_uploaded_file($image['tmp_name'], $ruta);

        try {
            $obj = new conductor_licencia();
            $obj->setFechaRevalidacion($fecha_revalidacion);
            $obj->setDescripcion($name_image);
            $obj->setImagen($name_encriptado);
            $obj->setConductorId($conductor_id);

            $result = $obj->create();
            if ($result) {
                Funciones::imprimeJSON(200, "Licencia guardada: ", $image);

            } else {
                Funciones::imprimeJSON(200, "Error al guardar imagen: ", $image);

            }


        } catch (Exception $exc) {

            Funciones::imprimeJSON(500, $exc->getMessage(), "");
        }

    } else {
        Funciones::imprimeJSON(203, "Debe seleccionar un archivo tipo imagen(jpg,png) ", $image);
        exit();
    }
}else{
    try {
        $obj = new conductor_licencia();
        $obj->setObservacion($observacion);
        $obj->setValidate($validate);
        $obj->setId($id);

        $result = $obj->update();
        if ($result) {
            Funciones::imprimeJSON(200, "Licencia actualizada: ", "");

        } else {
            Funciones::imprimeJSON(200, "Error al actializar licencia: ", "");

        }


    } catch (Exception $exc) {

        Funciones::imprimeJSON(500, $exc->getMessage(), "");
    }
}




