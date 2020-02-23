<?php



date_default_timezone_set("America/Lima");

class Help
{
    public static $DIRECTORIO_PRINCIPAL = "muni_api";

    public static function export_pdf($htmlDatos, $usuario, $titulo,$paciente) {

        $html = '';
        $html .= '
                    <html lang="en">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" charset="utf-8">
                            <table style="width: 100%">
                                    <head>
                                        <tr>
                                            <th><img src="../imagenes/LOGO_TRANS.png" style="width:2em">Clinica Nefrologia del Inka</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $titulo . '</th>
                                        </tr>
                                    </head>
                                </table> 
                                <hr style="color: #0056b2;" />
                                Fecha&nbsp;&nbsp; : Chiclayo,&nbsp;' . date("d") . ' de ' . date(" M ") . '  del ' . date(" Y ") . ' <br>
                                Hora&nbsp;&nbsp;&nbsp; :&nbsp;' . date('H:i:s') . ' <br>
                                Usuario : &nbsp; ' . $usuario . '<br>    
                                Paciente:  ' . $paciente . '    
                                   <br><br>
                                                                                                                                       
                        </head>
                        <body>';

        $html .= $htmlDatos;
        $html .= "</body>";
        $html .= "</html>";
        return $html;
    }

    public static function generarReporte($html_reporte, $tipo_reporte, $nombre_archivo)
    {
        if ($tipo_reporte == 1) {
            //Genera el reporte en HTML
            echo $html_reporte;
        } else if ($tipo_reporte == 2) {
            //Genera el reporte en PDF
            $base = "C:\\xampp7\\htdocs\\clinica_web\\reportes\\";
            $archivo_pdf = $base . $nombre_archivo . ".pdf";
            Help::generatePDF($archivo_pdf, $html_reporte);
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


    public static function cargarArchivo($nombreArchivo, $ruta)
    {
        try {
            if ($nombreArchivo != "") {
                move_uploaded_file($nombreArchivo, $ruta);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function eliminarArchivo($nombreArchivo)
    {
        try {
            if (file_exists($nombreArchivo)) {
                unlink($nombreArchivo);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function generatePDF($file = '', $html = '', $paper = 'a4', $download = true) {
        require_once '../dompdf/autoload.inc.php';

        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new \Dompdf\Dompdf($options);
        //$dompdf->setOptions($options);
        $dompdf->setPaper($paper, "landscape");
        $dompdf->loadHtml(utf8_decode($html));
        ini_set("memory_limit", "512M");
        $dompdf->render();
        file_put_contents($file, $dompdf->output());
        if ($download) {
            $dompdf->stream($file);
            return true;
        }

    }


}