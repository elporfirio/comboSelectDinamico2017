<?php
/**
 * Created by PhpStorm.
 * User: elporfirio
 * Date: 06/04/17
 * Time: 11:42 AM
 */

if (!defined('_ROOT_')) {
    define("_ROOT_", dirname(dirname(__FILE__)) . '/');
}
require_once(_ROOT_ . 'libs/Consultas.php');

$consultas = new Consultas();
$municipios = [];

if (isset($_GET['estado'])) {
    $municipios = $consultas->obtenerMunicipios(filter_var($_GET['estado'], FILTER_SANITIZE_STRING));
} else if (isset($_GET['municipio'])) {
    $municipios = $consultas->obtenerMunicipios('', filter_var($_GET['municipio'], FILTER_SANITIZE_STRING));
} else {
    $municipios = $consultas->obtenerMunicipios();
}

echo json_encode($municipios);