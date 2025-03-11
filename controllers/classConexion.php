<?php

class ConexionBD{
    protected static $servidor = 'localhost';
    protected static $baseDatos = 'forraje1_base_elcorral';
    protected static $usuario = 'root';
    protected static $clave = 'root';

    private function conexion() : PDO{
        try{
            $conexion = new PDO("mysql:host=". self::$servidor ."; dbname=". self::$baseDatos ."", self::$usuario, self::$clave);
        }catch(Exception $ex){
             echo $ex->getMessage();
        }

        return $conexion;
    }

    public function selecionarRegistro(string $tabla, string $condicion) {
        $conexion = self::conexion();
        if($condicion == NULL){
            $sentencias = $conexion->prepare("SELECT * FROM ". $tabla ."");
            $sentencias->execute();
            $registro = $sentencias->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $sentencias = $conexion->prepare("SELECT * FROM ". $tabla ." WHERE ". $condicion . "");
            $sentencias->execute();
            $registro = $sentencias->fetch(PDO::FETCH_LAZY);
        }

        return $registro;
    }

    public function inseratRegistro(string $tabla, array $array) {
        $conexion = self::conexion();

        $sentencias = $conexion->prepare("INSERT INTO ".$tabla."(".join(", ", self::conversionKey($array)).") VALUES ('". join("', '", self::conversionValues($array)) ."')");
        // var_dump($sentencias);
        $sentencias->execute();
    }

    public function actualizarRegistro(string $tabla, array $array, $id){
        $conexion = self::conexion();

        $valores = [];
        foreach($array as $key => $valor){
            $valores[] = $key." = '".$valor."'";
        }

        $sentencias = $conexion->prepare("UPDATE ".$tabla." SET ".join(", ", $valores)." WHERE id = ".$id."");
        // var_dump($sentencias);
        $sentencias->execute();
    }


    public function borrarRegistro(string $tabla, string $values, string $id){
        $conexion = self::conexion();

        if($values == ""){
            $sentencias = $conexion->prepare("DELETE FROM ".$tabla." WHERE id = ". $id ."");
            $sentencias->execute();
        }
        else{
            echo "Borrar los atribitos de registro";
        }
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

    private function conversionKey(array $array) {
        $keys = [];
        foreach($array as $key => $valor){
            if($valor == ""){
                continue;
            }
            $keys[] = $key;
        }
        return $keys;
    }

    private function conversionValues(array $array) {
        $keys = [];
        foreach($array as $key => $valor){
            if($valor == ""){
                continue;
            }
            $keys[] = $valor;
        }

        return $keys;
    }



    

}

