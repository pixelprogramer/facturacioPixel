<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'OPTIONS') {
    die();
}

require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$app = new \Slim\Slim();
$yaml = new \Symfony\Component\Yaml\Yaml();

define("SPECIALCONSTANT", true);
//elementos
require 'App/librerias/conexion.php';
require 'App/servicios/jwtAuth.php';
require 'App/servicios/helper.php';
require 'App/librerias/upload.pixel.php';
require 'App/librerias/email/email.pixel.php';
require 'App/librerias/email/plantillas.pixel.php';
require 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
require 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/Xlsx.php';
require 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Reader/IReader.php';
require 'vendor/tecnickcom/tcpdf/examples/tcpdf_include.php';

//Componentes
require 'App/componentes/usuario.php';
require 'App/componentes/administradorSistemas/rol.php';
require 'App/componentes/administradorSistemas/menu.php';
require 'App/componentes/facturacion/factura.php';
$app->run();