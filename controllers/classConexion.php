<?php

class ConexionBD{
    public $atributos = [];

    protected static $servidor = 'localhost';
    protected static $baseDatos = 'forraje1_base_elcorral';
    protected static $usuario = 'root';
    protected static $clave = 'root';

    protected static $nombreCarpeta;

    private function conexion() : PDO{
        try{
            $conexion = new PDO("mysql:host=". self::$servidor ."; dbname=". self::$baseDatos ."", self::$usuario, self::$clave);
        }catch(Exception $ex){
             echo $ex->getMessage();
        }

        return $conexion;
    }

    public function selecionarRegistro(string $tabla, string $condicion) {
        $sentencias = self::conexion();
        if($condicion == ""){
            $sentencias = $sentencias->prepare("SELECT * FROM ". $tabla ."");
            $sentencias->execute();
            $registro = $sentencias->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $sentencias = $sentencias->prepare("SELECT * FROM ". $tabla ." WHERE ". $condicion . "");
            $sentencias->execute();
            $registro = $sentencias->fetch(PDO::FETCH_LAZY);
        }

        return $registro;
    }

    public function inseratRegistro($valores){


        $conexion = self::conexion();
        $registros = [];
        $values = [];
        foreach($valores as $key => $valor){
            $registros[] = $key;
            $values[] = $valor;
        }

        foreach($registros as $registro){
            $string .= $registro. " ";
        }

        foreach($values as $value){
            $stringvalue .= "'".$value."'". " ";
        }


        var_dump($string);
        echo "<br>";
        var_dump($stringvalue);
    }

    public function paginador(int $pagina, int $regpagina, int $inicio) : float{
        $conexion = self::conexion();

        $sentencias = $conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tabla_carpetas LIMIT $inicio,$regpagina");
        $sentencias->execute();
        $resCarpetas = $sentencias->fetchAll(PDO::FETCH_ASSOC);
        $totalregistro = $conexion->query("SELECT FOUND_ROWS() AS total");
        $totalregistro = $totalregistro->fetch()['total'];
    
        $Numeropaginas = ceil($totalregistro / $regpagina);
        return $Numeropaginas;
    }


    private function convercionString($valor){

    }
}

