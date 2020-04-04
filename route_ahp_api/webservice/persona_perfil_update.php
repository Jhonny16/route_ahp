<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 02/04/20
 * Time: 10:41 PM
 */


require_once '../model/persona.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';


$nombre_completo = json_decode(file_get_contents("php://input"))->nombre_completo;
$documento_identidad = json_decode(file_get_contents("php://input"))->documento_identidad;
$celular = json_decode(file_get_contents("php://input"))->celular;
$direccion = json_decode(file_get_contents("php://input"))->direccion;
$fn = json_decode(file_get_contents("php://input"))->fecha_nacimiento;
$persona_id = json_decode(file_get_contents("php://input"))->persona_id;
$sexo = json_decode(file_get_contents("php://input"))->sexo;
$param = json_decode(file_get_contents("php://input"))->param;
$password = json_decode(file_get_contents("php://input"))->password;


try {
    date_default_timezone_set("America/Lima");

        if($persona_id == 1){
            $fn = null;
        }else{
            if ($fn == "" or $fn==null){
                $fn == null;
            }
        }

        $objper = new persona();
        $objper->setNombreCompleto($nombre_completo);
        $objper->setDocumentoIdentidad($documento_identidad);
        $objper->setCelular($celular);
        $objper->setDireccion($direccion);
        $objper->setFechaNacimiento($fn);
        $objper->setSexo($sexo);
        $objper->setId($persona_id);
        $objper->setPassword($password);

        $result = $objper->update();
        if($param == 1){
            if($password == null or $password == ""){
                Funciones::imprimeJSON(203, "Usted ha seleccionado el check para cambiar password, 
                verifique que el casillero no este vacio. ", "");
                exit();
            }

            $result_password=$objper->update_password();
            if($result_password){
                Funciones::imprimeJSON(200, "Se Actualizo Correctamente", "");

            }else{
                Funciones::imprimeJSON(203, "Error al momento de actualizar", "");

            }

        }else {
            if ($result) {
                Funciones::imprimeJSON(200, "Se Actualizo Correctamente", "");
            } else {
                Funciones::imprimeJSON(203, "Error al momento de actualizar", "");
            }

        }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
