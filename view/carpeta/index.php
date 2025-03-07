<?php
    if($_SESSION['privilegios'] == 2){
        header("Location:../../controllers/cerrar.php");
    }
    
    $pagina = "carpeta";
    
    include "../../templates/header.php"; 
    include "../../controllers/bd.php";
    include "../../controllers/classError.php";
    include "../../controllers/classConexion.php";
    include "../../controllers/classCarpeta.php";

    $errores = new ClassErrores();
    $conexion = new ConexionBD();
    $carpetaObj = new Carpeta();

    $formulario = isset($_GET['form']) ? $_GET['form'] : '';
    $accion = isset($_GET['accion']) ? $_GET['accion'] : '';
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if($formulario == 1){ // Registro de una nueva carpeta
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $carpeta = $_POST['nombre_carpeta'];
            if(!$carpeta){
                $errores->setError("Añada un nombre a la carpeta");
            }

            $registroCarpetas = $conexion->selecionarRegistro("tabla_carpetas", "");
            foreach($registroCarpetas as $evaluar){
                if($carpeta == $evaluar['nombre_carpeta']){
                    $errores->setError("La carpeta ya existe");
                }
            }

            if(!$errores->siExiste()){
                $valores = $_POST;
                $conexion->inseratRegistro("tabla_carpetas", $valores);
                $carpetaObj->crear($carpeta);
                header("Location: index.php");
            }
        }
    }

    if($formulario == 2){ // Actualizar la carpeta
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(!$_POST['nombre_carpeta']){
                $errores->setError("Añade un nuevo nombre a la carpeta");
            }

            $resultados = $conexion->selecionarRegistro("tabla_carpetas", "");

            foreach($resultados as $resultado){
                if($_POST['nombre_carpeta'] == $resultado['nombre_carpeta']){
                    $errores->setError("La carpeta ya existe");
                }
            }

            if(!$errores->siExiste()){
                $valores = $_POST;
                $nuevaCarpeta = $_POST['nombre_carpeta'];
                $rec = $conexion->selecionarRegistro("tabla_carpetas", "id = ". $id);
                $viejaCarpeta = $rec['nombre_carpeta'];

                $carpetaObj->modificar($nuevaCarpeta, $viejaCarpeta);
                $conexion->actualizarRegistro("tabla_carpetas", $valores, $id);

                header("Location: index.php");
            }
        }
    }

    if($accion == 2){ // Eliminacion de la carpeta            
        $borrar = $conexion->selecionarRegistro("tabla_carpetas", "id = ". $id);
        $borrar = $borrar['nombre_carpeta'];
        $carpetaObj->eliminar($borrar);

        $conexion->borrarRegistro("tabla_carpetas", "", $id);

        header("Location: index.php");
    }

    $consultaCarpetas = $conexion->selecionarRegistro("tabla_carpetas", "");
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $regpagina = 10;
    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;
    $Numeropaginas = $conexion->paginador($pagina, $regpagina, $inicio);
?>
<main class="main_inicio">
    <?php
        
    ?>
    <h1>Gestion carpetas</h1>
    <br>

    <?php if($errores->siExiste()){ $errores->imprimirErrores(); } ?>

    <?php if($accion == 1){ $resultado = $conexion->selecionarRegistro("tabla_carpetas", "id = ". $id); ?>
        <h2>Cambiar nombre a la carpeta</h2>
        <form class="formActualizar" action="?form=2&id=<?php echo $resultado['id']; ?>" method="POST">
            <input type="text" name="nombre_carpeta" placeholder="<?php echo $resultado['nombre_carpeta']; ?>">
            <button class="botonCarpetasG">Guardar</button>
        </form>
    <?php } ?>

    <table class="tabla">
        <thead>
            <tr>
                <td>Nombre Carpeta</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr class="formTabla">    
                <form action="?form=1" method="POST">
                    <td><input type="text" name="nombre_carpeta"></td>
                    <td class="relleno"><button class="botonCarpetasG">Guardar</button></td>
                </form>
            </tr>
            <?php $i=0; foreach($consultaCarpetas as $consultaCarpeta){ ?>
            <tr class="<?php if(($i % 2) == 0){ echo "verdeObscuro"; }else{ echo "verdeClaro"; } ?> verdeObscuro">
                <td><?php echo $consultaCarpeta['nombre_carpeta']; ?></td>
                <td class="centrarIconos">
                    <a href="index.php?accion=1&id=<?php echo $consultaCarpeta['id']; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                    <a href="index.php?accion=2&id=<?php echo $consultaCarpeta['id']; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                </td>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
    <nav class="paginador">
        <ul>
            <?php if($pagina == 1){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-backward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="index.php?pagina=<?php echo $pagina-1; ?>"><i class="fa-solid fa-backward"></i></a></li>
            <?php } ?>
            <?php for($i=1; $i<=$Numeropaginas; $i++){ ?>
                <?php if($pagina == $i){ ?>
                    <li class="select"><a class="selectA" href="index.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } else { ?>
                    <li><a class="selectV" href="index.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            <?php } ?>
            <?php if($pagina == $Numeropaginas){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-forward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="index.php?pagina=<?php echo $pagina+1; ?>"><i class="fa-solid fa-forward"></i></a></li>
            <?php } ?>
        </ul>
    </nav>
</main>
<?php
    include "../../templates/footer.php"; 
?>