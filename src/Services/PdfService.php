<?php

namespace Services;

use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../vendor/autoload.php';

class PdfService
{
    public static function generarPdf($html, $nombreArchivo = "documento.pdf")
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->setChroot(dirname($_SERVER['DOCUMENT_ROOT']));

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream($nombreArchivo, [
            "Attachment" => false
        ]);
        exit;
    }
}
