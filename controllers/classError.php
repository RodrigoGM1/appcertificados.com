<?php

class ClassErrores{
    public $errores = [];

    public function setError(string $errores){
        $this->errores[] = $errores;
    }

    public function imprimirErrores() {
        foreach($this->errores as $error){
            echo "<div class='errorIn'><p>" . $error . "</p></div><br>";
        }
    }

    public function siExiste() : bool {
        if(!empty($this->errores)){
            return true;
        }
        return false;
    }
}