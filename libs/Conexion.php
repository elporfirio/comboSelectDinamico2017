<?php

/**
 * Created by PhpStorm.
 * User: elporfirio
 * Date: 06/04/17
 * Time: 9:08 AM
 */
if (!defined('_ROOT_')) {
    define("_ROOT_", dirname(dirname(__FILE__)) . '/');
}

class Conexion
{
    private static $config;
    private static $conexion;

    private static function setConexion()
    {
        try {
            if (!self::$config = parse_ini_file(_ROOT_ . 'config.ini', true)) {
                throw new Exception('No se puede abrir el archivo de configuracion. ');
            };

            $dsn = self::$config['database']['driver']
                . ':host=' . self::$config['database']['servidor']
                . ((!empty(self::$config['database']['puerto'])) ? (';port=' . self::$config['database']['puerto']) : '')
                . ';dbname=' . self::$config['database']['basedatos'];

            self::$conexion = new PDO($dsn, self::$config['database']['usuario'], self::$config['database']['contrasena'], self::$config['options']);

            foreach (self::$config['attributes'] as $key => $option) {
                self::$conexion->setAttribute(constant("PDO::{$key}"), constant("PDO::{$option}"));
            }
        } catch (PDOException $e) {
            die("No se puede conectar a la base de datos: " . $e->getMessage());
        }
    }

    public static function getConexion()
    {
        self::setConexion();
        return self::$conexion;
    }

}