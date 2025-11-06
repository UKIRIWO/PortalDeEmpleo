<?php
header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DIAGNÓSTICO COMPLETO ===\n\n";

echo "Método: " . ($_SERVER['REQUEST_METHOD'] ?? 'NO DEFINIDO') . "\n";
echo "Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'NO DEFINIDO') . "\n";
echo "Content-Length: " . ($_SERVER['CONTENT_LENGTH'] ?? '0') . "\n\n";

echo "=== SUPER GLOBALS ===\n\n";
echo "POST: " . print_r($_POST, true) . "\n";
echo "FILES: " . print_r($_FILES, true) . "\n";
echo "GET: " . print_r($_GET, true) . "\n";
echo "SERVER: " . print_r($_SERVER, true) . "\n";

echo "\n=== RAW INPUT ===\n";
$input = file_get_contents('php://input');
echo "Longitud: " . strlen($input) . " bytes\n";
echo "Contenido: " . $input . "\n";

echo "\n=== ENV ===\n";
echo print_r($_ENV, true);

// Probar escritura de archivo
file_put_contents('test_diagnostico.log', date('Y-m-d H:i:s') . " - Test completo\n", FILE_APPEND);

echo "\n=== FIN DIAGNÓSTICO ===\n";