<?php

date_default_timezone_set("America/Lima");

class Helper {

    public static $DIRECTORIO_PRINCIPAL = "muni_api";

    public static function export_pdf($htmlDatos, $usuario, $titulo) {

        $html = '';
        $html .= '
                    <html>
                        <head>
                            <meta charset="utf-8">
                            <table>
                                    <head>
                                        <tr>
                                            <th><img src="../../imagenes/pdf.png" style="width:2em"></th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ' . $titulo . '</th>
                                        </tr>
                                    </head>
                                </table> 
                                <hr style="color: #0056b2;" />
                                Fecha&nbsp;&nbsp;: Chiclayo,&nbsp;' . date("d") . ' de ' . date(" M ") . '  del ' . date(" Y ") . ' <br>
                                Hora&nbsp;&nbsp;&nbsp;:&nbsp;' . date('H:i:s') . ' <br>
                                Usuarios: &nbsp; ' . $usuario . '                                                                                                              
                        </head>
                        <body>';

        $html .= $htmlDatos;
        $html .= "</body>";
        $html .= "</html>";
        return $html;
    }

    public static function generarReporte($html_reporte, $tipo_reporte, $nombre_archivo) {
        if ($tipo_reporte == 1) {
            //Genera el reporte en HTML
            echo $html_reporte;
        } else if ($tipo_reporte == 2) {
            //Genera el reporte en PDF
            $archivo_pdf = "../reportes/" . $nombre_archivo . ".pdf";
            Helper::generaPDF($archivo_pdf, $html_reporte);
            header("location:" . $archivo_pdf);
        } else {
            //Genera el reporte en Excel
            header("Content-type: application/vnd.ms-excel; name='excel'");
            header("Content-Disposition: filename=" . $nombre_archivo . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo $html_reporte;
        }
    }

    public static function mensaje($mensaje, $tipo, $archivoDestino = "", $tiempo = 0) {
        $estiloMensaje = "";

        if ($archivoDestino == "") {
            $destino = "javascript:window.history.back();";
        } else {
            $destino = $archivoDestino;
        }

        $menuEntendido = '<div><a href="' . $destino . '">Entendido</a></div>';


        if ($tiempo == 0) {
            $tiempoRefrescar = 5;
        } else {
            $tiempoRefrescar = $tiempo;
        }

        switch ($tipo) {
            case "s":
                $estiloMensaje = "alert callout-success";
                $titulo = "Hecho";
                break;

            case "i":
                $estiloMensaje = "callout-info";
                $titulo = "Información";
                break;

            case "a":
                $estiloMensaje = "callout-warning";
                $titulo = "Cuidado";
                break;

            case "e":
                $estiloMensaje = "callout-danger";
                $titulo = "Error";
                break;

            default:
                $estiloMensaje = "callout-info";
                $titulo = "Información";
                break;
        }

        $html_mensaje = '
                    <html>
                        <head>
                            <title>Mensaje del sistema</title>
                            <meta charset="utf-8">
                            <meta http-equiv="refresh" content="' . $tiempoRefrescar . ';' . $destino . '">
                                
                            <link href="../util/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
                            <!-- Theme style -->
                            <link href="../util/lte/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    
    
                        </head>
                        <body>
                            <div class="containter">
                                <section class="content">
                                    <div class="callout ' . $estiloMensaje . '">
                                        <h4>' . $titulo . '!</h4>
                                        <p>' . $mensaje . '</p>
                                    </div>
                                    ' . $menuEntendido . '
                                </section>
                        </body>
                    </html>
                ';

        echo $html_mensaje;

        exit;
    }

    public static function imprimeJSON($estado, $mensaje, $datos) {
        //header("HTTP/1.1 ".$estado." ".$mensaje);
        header("HTTP/1.1 " . $estado);
        header('Content-Type: application/json');

        $response["estado"] = $estado;
        $response["mensaje"] = $mensaje;
        $response["datos"] = $datos;

        echo json_encode($response);
    }


    public static function generaPDF($file = '', $html = '', $paper = 'a4', $download = true) {
        require_once '../dompdf/autoload.inc.php';

        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new \Dompdf\Dompdf($options);
        //$dompdf->setOptions($options);
        $dompdf->setPaper($paper, "portrait");
        $dompdf->loadHtml(utf8_decode($html));
        ini_set("memory_limit", "512M");
        $dompdf->render();
        file_put_contents($file, $dompdf->output());
        if ($download) {
            $dompdf->stream($file);
        }
        //$dompdf->stream("holitas.pdf", array("Attachment" => false));
    }



    public static function limpiarString($string) {
        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            ".", " "), '', $string
        );
        return $string;
    }

}
