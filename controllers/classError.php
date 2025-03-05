<?php

class ClassErrores{
    public $errores = [];

    public function setError(string $errores){
        $this->errores[] = $errores;
    }

    public function imprimirErrores() {
        foreach($this->errores as $error){
            echo $error . "<br>";
        }
    }
}