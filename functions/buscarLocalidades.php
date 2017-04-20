<?php
/**
 * Created by PhpStorm.
 * User: elporfirio
 * Date: 06/04/17
 * Time: 11:50 AM
 */

if (!defined('_ROOT_')){
    define("_ROOT_", dirname( dirname( __FILE__ ) ).'/' );
}
require_once(_ROOT_. 'libs/Consultas.php');

$consultas = new Consultas();
$localidades = [];

if(isset($_GET['municipio'])){
    $localidades = $consultas->obtenerLocalidades(filter_var($_GET['municipio'], FILTER_SANITIZE_STRING));
} else if(isset($_GET['localidad'])) {
    $localidades = $consultas->obtenerLocalidades('', filter_var($_GET['localidad'], FILTER_SANITIZE_STRING));
}
//else {
//    $localidades = $consultas->obtenerLocalidades();
//}

echo json_encode($localidades, JSON_UNESCAPED_UNICODE);