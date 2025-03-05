<?php

class ConexionBD{
    protected static $servidor = 'localhost';
    protected static $baseDatos = 'forraje1_base_elcorral';
    protected static $usuario = 'root';
    protected static $clave = 'root';

    public function conexion() : PDO{
        try{
            $conexion = new PDO("mysql:host=". self::$servidor ."; dbname=". self::$baseDatos ."", self::$usuario, self::$clave);
        }catch(Exception $ex){
             echo $ex->getMessage();
        }

        return $conexion;
    }

}

$con = new ConexionBD();

$con1 = $con->conexion();

var_dump($con1);

$sentencias = $con1->prepare("SELECT * FROM tabla_usuarios");
$sentencias->execute();
$registro = $sentencias->fetch(PDO::FETCH_LAZY);

var_dump($registro);