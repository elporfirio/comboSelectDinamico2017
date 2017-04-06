<?php
/**
 * Created by PhpStorm.
 * User: elporfirio
 * Date: 06/04/17
 * Time: 11:26 AM
 */
if (!defined('_ROOT_')) {
    define("_ROOT_", dirname(dirname(__FILE__)) . '/');
}
require_once(_ROOT_ . 'libs/Consultas.php');

$consultas = new Consultas();
$municipios = [];

if (isset($_GET['estado'])) {
    $estados = $consultas->obtenerEstados(filter_var($_GET['estado'], FILTER_SANITIZE_STRING));
} else {
    $estados = $consultas->obtenerEstados();
}

echo json_encode($estados);