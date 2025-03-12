<?php
    if($_SESSION['privilegios'] == 2){
        header("Location:../../controllers/cerrar.php");
    }

    $pagina = "certificado";
    include "../../templates/header.php"; 
    include "../../controllers/classConexion.php";
    include "../../controllers/classCarpeta.php";
    include "../../controllers/classError.php"; 

    $errores = new ClassErrores();
    $conexion = new ConexionBD();
    $archivo = new Carpeta();

    $form = isset($_GET['form']) ? $_GET['form']: '';
    $accion = isset($_GET['accion']) ? $_GET['accion']: '';
    $carpeta = $_GET['carpeta'];
    $nombreCarpeta = $_GET['nombreCarpeta'];



    $resultadoEvidencias = $conexion->selecionarRegistro("tabla_certificados", "");
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $regpagina = 15;
    $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;
    $Numeropaginas = $conexion->paginador($pagina, $regpagina, $inicio);
?>
<main class="main_inicio">
    <?php
        if($form == 1){ // Crear certifiacado
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $id = $_GET['id'];
                $direcionE = "../CertificadosDoc/".$nombreCarpeta."/".$_FILES['nombre_c']['name'];

                if($archivo->agregarArchivo($_FILES['nombre_c']['tmp_name'], $direcionE)){
                    $errores->setError("No se pudo subir el archivo");
                }
        
                if(!$errores->siExiste()){
                    $valores['nombre_c'] = $_FILES['nombre_c']['name'];
                    $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                    header("Location:subCertificados.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
                }        
            }
        }
    
        if($form == 2){ // Actualizacion
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $id = $_GET['id'];
                $direcionE = "../CertificadosDoc/".$nombreCarpeta."/".$_FILES['nombre_c']['name'];
                
                $selecionarEvi = $conexion->selecionarRegistro("tabla_certificados", "id = ". $id);
                $direcionEAn = "../CertificadosDoc/".$nombreCarpeta."/".$selecionarEvi['nombre_c'];
    
                if(file_exists($direcionEAn)){
                    $archivo->borrarArchivos($direcionEAn);
                    $archivo->agregarArchivo($_FILES['nombre_c']['tmp_name'], $direcionE);
                }
    
                if(!$errores->siExiste()){
                    $valores['nombre_c'] = $_FILES['nombre_c']['name'];;
                    $sentencias = $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                    header("Location:subCertificados.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
                }
    
            }
        }
    
        if($accion == 3){ // Eliminar
            $id = $_GET['id'];
            $selecionarEvi = $conexion->selecionarRegistro("tabla_certificados", "id = ". $id);
            $direcionEAn = "../CertificadosDoc/".$nombreCarpeta."/".$selecionarEvi['nombre_c'];
            
            if(file_exists($direcionEAn)){
                $archivo->borrarArchivos($direcionEAn);
            }else{
                $errores->setError("No se pudo borrar el archivo");
            }

            if(!$errores->siExiste()){
                $valores['nombre_c'] = "";
                $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                header("Location:subCertificados.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
            }
        }
    ?>
    <h1>Certificados <?php echo $nombreCarpeta; ?></h1>
    <br>

    <?php
        if($accion == 1){
            $id = $_GET['id'];
   ?>
        <form class="formActualizar" action="?carpeta=<?php echo $carpeta;?>&form=1&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <input class="evidenciaFecha" type="file" name="nombre_c">
            <button class="botonCarpetasA">Guardar</button>
        </form>
    <?php } elseif($accion == 2){ 
        $id = $_GET['id'];
        $evidenciaAnterior = $conexion->selecionarRegistro("tabla_certificados", "id = ". $id);
    ?>
        
        <form class="formActualizar" action="?carpeta=<?php echo $carpeta;?>&form=2&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <label class="labelCertificado"><?php echo $evidenciaAnterior['nombre_c']; ?></label>
            <input class="evidenciaFecha" type="file" name="nombre_c">
            <button class="botonCarpetasA">Guardar</button>
        </form>
    <?php } ?>

    <table class="tabla">
        <thead>
            <tr>
                <td>Fecha de recolección</td>
                <td>Evidencia</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($resultadoEvidencias as $resultadoEvidencia){ ?>
            <tr class="<?php if(($a % 2) == 0){echo"verdeObscuro";}else{echo"verdeClaro";} ?>">
                <td><?php echo $resultadoEvidencia['fecha']; ?></td>
                <?php if($resultadoEvidencia['nombre_c'] == ''){ ?>
                    <td class="noPaddin">
                        <a href="?carpeta=<?php echo $carpeta;?>&accion=1&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $resultadoEvidencia['id']; ?>" class="btnSubCertificado">Subir Certificado</a>
                    </td>
                <?php }else{ ?>
                    <td><?php echo $resultadoEvidencia['nombre_c']; ?></td>
                <?php } ?>
                <td class="centrarIconos">
                    <a target="_black" href="../CertificadosDoc/<?php echo $nombreCarpeta ?>/<?php echo $resultadoEvidencia['nombre_c']; ?>"><i class="fa-regular fa-eye estiloOjo"></i></a>
                    <a href="?accion=2&id=<?php echo $resultadoEvidencia['id']; ?>&carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                    <a href="?carpeta=<?php echo $carpeta;?>&accion=3&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $resultadoEvidencia['id']; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <nav class="paginador">
        <ul>
            <?php if($pagina == 1){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-backward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="evidencias.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $pagina-1; ?>"><i class="fa-solid fa-backward"></i></a></li>
            <?php } ?>
            <?php for($i=1; $i<=$Numeropaginas; $i++){ ?>
                <?php if($pagina == $i){ ?>
                    <li class="select"><a class="selectA" href="evidencias.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } else { ?>
                    <li><a class="selectV" href="evidencias.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            <?php } ?>
            <?php if($pagina == $Numeropaginas){ ?>
                <li><a class="selectV" href="#"><i class="fa-solid fa-forward"></i></a></li>
            <?php } else { ?>
                <li><a class="selectV" href="evidencias.php?carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>&pagina=<?php echo $pagina+1; ?>"><i class="fa-solid fa-forward"></i></a></li>
            <?php } ?>
        </ul>
    </nav>
</main>
<?php
    include "../../templates/footer.php"; 
?>