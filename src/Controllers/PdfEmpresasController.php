<?php
namespace Controllers;

use Repositories\RepoEmpresa;
use Services\PdfService;

class PdfEmpresasController
{
    public function generarPdf()
    {
        $empresas = RepoEmpresa::findAll();
        $html = $this->generarHtmlEmpresas($empresas);
        PdfService::generarPdf($html, "empresas_aprobadas_" . date('Y-m-d') . ".pdf");
    }


    // Genera una tabla html con los datos de las empresas aprobadas
    private function generarHtmlEmpresas($empresas)
    {
        $filas = '';
        foreach ($empresas as $empresa) {
            $filas .= "
            <tr>
                <td>{$empresa->getId()}</td>
                <td>{$empresa->getIdUserFk()}</td>
                <td>{$empresa->getNombre()}</td>
                <td>{$empresa->getCorreoDeContacto()}</td>
                <td>{$empresa->getTelefonoDeContacto()}</td>
            </tr>";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Listado de Empresas Aprobadas</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                h1 { text-align: center; color: #333; }
                .fecha { text-align: right; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <h1>Listado de Empresas Aprobadas</h1>
            <div class='fecha'>Generado el: " . date('d/m/Y H:i:s') . "</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID User</th>
                        <th>Nombre Empresa</th>
                        <th>Email Contacto</th>
                        <th>Teléfono Contacto</th>
                    </tr>
                </thead>
                <tbody>
                    $filas
                </tbody>
            </table>
            <div style='margin-top: 20px; text-align: center; color: #666;'>
                Total de empresas: " . count($empresas) . "
            </div>
        </body>
        </html>";
    }
}