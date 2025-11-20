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

    private function generarHtmlEmpresas2($empresas)
{
    $filas = '';
    
    // Obtener la URL base de tu aplicación
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $baseUrl = "{$protocol}://{$host}";
    
    foreach ($empresas as $empresa) {
        // Determinar la ruta de la imagen
        $logo = $empresa->getLogo();
        $idUser = $empresa->getIdUserFk();
        
        // Si no hay logo específico, usar el formato logo_(iduser).png
        if (!$logo) {
            $logo = "logo_{$idUser}.png";
        }
        
        $rutaImagen = '/.imagenes/empresa/' . $logo;
        $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $rutaImagen;
        
        // Verificar si existe la imagen, si no usar predeterminada
        if (!file_exists($rutaCompleta)) {
            $rutaImagen = '/.imagenes/empresa/predeterminada.png';
        }
        
        // Usar URL completa para Dompdf
        $urlImagen = $baseUrl . $rutaImagen;
        
        $filas .= "
        <tr>
            <td>{$empresa->getId()}</td>
            <td>{$empresa->getIdUserFk()}</td>
            <td>
                <div style='display: flex; align-items: center; gap: 10px;'>
                    <img src='{$urlImagen}' style='width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;' alt='Logo {$empresa->getNombre()}'>
                    <span>{$empresa->getNombre()}</span>
                </div>
            </td>
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
            body { 
                font-family: Arial, sans-serif; 
                margin: 20px;
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 20px; 
            }
            th, td { 
                border: 1px solid #ddd; 
                padding: 10px; 
                text-align: left; 
                vertical-align: middle;
            }
            th { 
                background-color: #f2f2f2; 
                font-weight: bold; 
            }
            h1 { 
                text-align: center; 
                color: #333; 
                margin-bottom: 10px;
            }
            .fecha { 
                text-align: right; 
                color: #666; 
                font-size: 14px; 
                margin-bottom: 20px;
            }
            .total {
                margin-top: 20px; 
                text-align: center; 
                color: #666;
                font-style: italic;
            }
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
        <div class='total'>
            Total de empresas: " . count($empresas) . "
        </div>
    </body>
    </html>";
}

    private function generarHtmlEmpresas($empresas)
    {
        $filas = '';
    
    // Usar el nombre del servicio Docker en lugar de localhost
    $baseUrl = "http://php-apache";
    
    foreach ($empresas as $empresa) {
        // Determinar la ruta de la imagen
        $logo = $empresa->getLogo();
        $idUser = $empresa->getIdUserFk();
        
        // Si no hay logo específico, usar el formato logo_(iduser).png
        if (!$logo) {
            $logo = "logo_{$idUser}.png";
        }
        
        $rutaImagen = '/.imagenes/empresa/' . $logo;
        $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $rutaImagen;
        
        // Verificar si existe la imagen, si no usar predeterminada
        if (!file_exists($rutaCompleta)) {
            $rutaImagen = '/.imagenes/empresa/predeterminada.png';
        }
        
        // Usar URL con el nombre del servicio Docker
        $urlImagen = $baseUrl . $rutaImagen;

            $filas .= "
            <p>{$urlImagen}</p>
            <p>{$rutaCompleta}</p>
            <p>{$rutaImagen}</p>
                <div style='display: flex; align-items: center; gap: 10px;'>
                    <img src='{$rutaImagen}' style='width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;' alt='Logo {$empresa->getNombre()}'>
                </div>";
        }

        return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Listado de Empresas Aprobadas</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                margin: 20px;
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 20px; 
            }
            th, td { 
                border: 1px solid #ddd; 
                padding: 10px; 
                text-align: left; 
                vertical-align: middle;
            }
            th { 
                background-color: #f2f2f2; 
                font-weight: bold; 
            }
            h1 { 
                text-align: center; 
                color: #333; 
                margin-bottom: 10px;
            }
            .fecha { 
                text-align: right; 
                color: #666; 
                font-size: 14px; 
                margin-bottom: 20px;
            }
            .total {
                margin-top: 20px; 
                text-align: center; 
                color: #666;
                font-style: italic;
            }
        </style>
    </head>
    <body>
                $filas
    </body>
    </html>";
    }

    private function generarHtmlEmpresasCopia($empresas)
    {
        $filas = '';
        foreach ($empresas as $empresa) {
            // Determinar la ruta de la imagen
            $logo = $empresa->getLogo();
            $idUser = $empresa->getIdUserFk();

            // Si no hay logo específico, usar el formato logo_(iduser).png
            if (!$logo) {
                $logo = "logo_{$idUser}.png";
            }

            $rutaImagen = '/.imagenes/empresa/' . $logo;
            $rutaCompleta = $_SERVER['DOCUMENT_ROOT'] . $rutaImagen;

            // Verificar si existe la imagen, si no usar predeterminada
            if (!file_exists($rutaCompleta)) {
                $rutaImagen = '/.imagenes/empresa/predeterminada.png';
            }

            $filas .= "
        <tr>
            <td>{$empresa->getId()}</td>
            <td>{$empresa->getIdUserFk()}</td>
            <td>
                <div style='display: flex; align-items: center; gap: 10px;'>
                    <img src='{$rutaImagen}' style='width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;' alt='Logo {$empresa->getNombre()}'>
                </div>
            </td>
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
            body { 
                font-family: Arial, sans-serif; 
                margin: 20px;
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 20px; 
            }
            th, td { 
                border: 1px solid #ddd; 
                padding: 10px; 
                text-align: left; 
                vertical-align: middle;
            }
            th { 
                background-color: #f2f2f2; 
                font-weight: bold; 
            }
            h1 { 
                text-align: center; 
                color: #333; 
                margin-bottom: 10px;
            }
            .fecha { 
                text-align: right; 
                color: #666; 
                font-size: 14px; 
                margin-bottom: 20px;
            }
            .total {
                margin-top: 20px; 
                text-align: center; 
                color: #666;
                font-style: italic;
            }
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
                    <th>Logo</th>
                    <th>Nombre Empresa</th>
                    <th>Email Contacto</th>
                    <th>Teléfono Contacto</th>
                </tr>
            </thead>
            <tbody>
                $filas
            </tbody>
        </table>
        <div class='total'>
            Total de empresas: " . count($empresas) . "
        </div>
    </body>
    </html>";
    }
}
