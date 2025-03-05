<?php
    $servidor = 'localhost';
    $baseDatos = 'forraje1_base_elcorral';
    $usuario = 'root';
    $clave = 'root';

    try{
        $conexion = new PDO("mysql:host=$servidor; dbname=$baseDatos",$usuario,$clave);
    }catch(Exception $ex){
         echo $ex->getMessage();
    }

    var_dump($conexion);

    $sentencias = $conexion->prepare("SELECT * FROM tabla_usuarios");
    $sentencias->execute();
    $registro = $sentencias->fetch(PDO::FETCH_LAZY);

    var_dump($registro);