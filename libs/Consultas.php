<?php

/**
 * Created by PhpStorm.
 * User: elporfirio
 * Date: 06/04/17
 * Time: 9:59 AM
 */
if (!defined('_ROOT_')) {
    define("_ROOT_", dirname(dirname(__FILE__)) . '/');
}

require_once(_ROOT_. 'libs/Conexion.php');

class Consultas
{
    private $conexion;
    private $resultado;

    public function __construct()
    {
        $this->conexion = Conexion::getConexion();
    }

    public function obtenerEstados($estado = ''){
        $consulta = "SELECT * FROM estados";
        if($estado != ''){
            $consulta .= " WHERE clave_estado = :estado";
        }
        return $this->prepararConsulta($consulta, array('estado' => $estado));
    }

    public function obtenerMunicipios($estado = '', $municipio = ''){
        $consulta = "SELECT * FROM municipios";
        if($estado != ''){
            $consulta .= " WHERE clave_estado = :estado";
        } else if($municipio != ''){
            $consulta .= " WHERE clave_municipio = :municipio";
        }
        return $this->prepararConsulta($consulta, array('estado' => $estado, 'municipio' => $municipio));
    }

    public function obtenerLocalidades($municipio = '', $localidad = ''){
        $consulta = "SELECT * FROM localidades";
        if($municipio != ''){
            $consulta .= " WHERE clave_municipio = :municipio";
        } else if($localidad != ''){
            $consulta .= " WHERE clave_localidad = :localidad";
        }
        return $this->prepararConsulta($consulta, ['municipio' => $municipio, 'localidad' => $localidad]);
    }

    public function prepararConsulta($consulta, $valores = array()){
        $stmt = $this->conexion->prepare($consulta);
        if(count($valores) > 0){
            if(preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)){
                $campo = array_pop($campo);
                foreach($campo as $parametro){
                    $stmt->bindParam($parametro, $valores[substr($parametro,1)]);
                }
            }
        }
        return $this->ejecutarConsulta($stmt);
    }

    private function ejecutarConsulta($stmt){
        try{
            if(!$stmt->execute()){
                print_r($stmt->errorInfo());
            }

            $this->resultado = $stmt->fetchAll();
            $stmt->closeCursor();
            return $this->resultado;
        } catch (PDOException $e){
            echo "Error al ejecutar la sentencia: \n";
            print_r($e->getMessage());
            return false;
        }
    }
}