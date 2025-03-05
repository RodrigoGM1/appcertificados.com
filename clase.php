<?php
/*
class ClaseSencilla{
    public $var = "Hola mundo\n";

    public function mostarVar(){
        echo $this->var;
    }
}

$instancias = new ClaseSencilla();

$asignada = $instancias;
$referencia =& $instancias;

$instancias->var = "asignar el valor";

$instancias = NULL;

class Prueba{
    static public function getNew(){
        return new static;
    }
}

class Hija extends Prueba{}

$obj1 = new Prueba();
$obj2 = new Hija();

// var_dump($obj1 !== $obj2);

$obj2->getNew();

class Foo{
    public $bar = "propiedad";

    public function bar(){
        return "metodo";
    }
}

$foo = new Foo();

echo $foo->bar(), PHP_EOL, $foo->bar;

class FooHija extends Foo{
    public $bar;

    public function __construct(){
        $this->bar = function(){
            return 42;
        };
    }
}

$obj = new FooHija();

echo ($obj->bar)(), PHP_EOL . "<br>";

class User{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name){
        $this->id = $id;
        $this->name = $name;
    }
}

$user = new User(1132, "Rodrigo");

var_dump($user->id);
var_dump($user->name);

echo "<br>";

class Shape{
    public int $numeronew;
    public string $nombrenew;

    public function setNumero(int $numero) : void {
        $this->numeronew = $numero;
    }

    public function setNombre(string $name) : void {
        $this->$nombrenew = $name;
    }

    public function getNumero() : int {
        return $this->numeronew;
    }

    public function getNombre() : string {
        return $this->$nombrenew;
    }
}

$datos = new Shape();

$datos->setNumero(3);
$datos->setNombre("Rodrigo");

var_dump($datos->getNumero());
var_dump($datos->getNombre());

echo "<br><br>";

class Test{
    public readonly string $prop;

    public function __construct(string $prop){
        $this->prop = $prop;
    }
}

$test = new Test("hola");

var_dump($test->prop);

$test->prop = "foobar";

var_dump($test->prop);



class MyClase{
    const CONSTANTE = "valor constante";

    function mostrarConstante(){
        echo self::CONSTANTE . "\n";
    }
}

echo MyClase::CONSTANTE . "\n";

$nombreClase = "MyClase";

echo $nombreClase::CONSTANTE . "\n";

$clase = new MyClase();

echo $clase::CONSTANTE;

*/