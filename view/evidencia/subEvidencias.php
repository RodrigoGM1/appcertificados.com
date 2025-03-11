<?php
    if($_SESSION['privilegios'] == 2){
        header("Location:../../controllers/cerrar.php");
    }

    $pagina = "evidencia";
    include "../../templates/header.php";
    include "../../controllers/classError.php";
    include "../../controllers/classConexion.php";
    include "../../controllers/classCarpeta.php";

    $errores = new ClassErrores();
    $conexion = new ConexionBD();
    $archivo = new Carpeta(); 
?>

<main class="main_inicio">

    <?php
        $carpeta = $_GET['carpeta'];
        $nombreCarpeta = $_GET['nombreCarpeta'];
        $form = isset($_GET['form']) ? $_GET['form'] : '';
        $accion = isset($_GET['accion']) ? $_GET['accion'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if($form == 1){ // Creacion de la evidencia
            $fecha = '';
            $evidencia = '';
            $manifiesto = '';
            $nota = '';

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $fecha = $_POST['fecha'];

                if(!$fecha){
                    $errores->setError("Añada la fecha");
                }
                if(!$errores->siExiste()){
                    if($_FILES['n_evidencias']['name'] or $_FILES['manifiesto']['name'] or $_FILES['nota']['name']){
                        $manifiesto = $_FILES['manifiesto']['name'] ? $_FILES['manifiesto']['name'] : ''; 
                        $nota = $_FILES['nota']['name'] ? $_FILES['nota']['name'] : '';
                        $evidencia = $_FILES['n_evidencias']['name'] ? $_FILES['n_evidencias']['name'] : '';
                        $valores = $_POST;
                        $valores['id_carpeta'] = $carpeta;
                        $direcionM = "../EvidenciasSub/Manifiestos/".$nombreCarpeta."/".$manifiesto;
                        $direcionN = "../EvidenciasSub/Notas/".$nombreCarpeta."/".$nota;
                        $direcionE = "../EvidenciasDoc/".$nombreCarpeta."/".$evidencia;
                        
                        if($_FILES['manifiesto']['tmp_name']){
                            $archivo->agregarArchivo($_FILES['manifiesto']['tmp_name'], $direcionM);
                            $valores['manifiesto'] =  $manifiesto;
                        }
                        if($_FILES['nota']['tmp_name']){
                            $archivo->agregarArchivo($_FILES['nota']['tmp_name'], $direcionN);
                            $valores['nota'] = $nota;
                        }
                        if($_FILES['n_evidencias']['tmp_name']){
                            $archivo->agregarArchivo($_FILES['n_evidencias']['tmp_name'], $direcionE);
                            $valores['n_evidencias'] = $evidencia;
                        }
                        $conexion->inseratRegistro("tabla_certificados", $valores);
                        header("Location:subEvidencias.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
                    }                 
                    else{
                        $valores = [];
                        $valores['id_carpeta'] = $carpeta;
                        $valores['fecha'] = $_POST['fecha'];
                        $conexion->inseratRegistro("tabla_certificados", $valores);
                        header("Location:subEvidencias.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
                    }
                }
            }
        }

        if($form == 2){ // Actualizar evidencias
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $fecha = $_POST['fecha'];
                $id = $_GET['id'];
                          
                if($fecha){
                    $valores = $_POST;
                    $sentencias = $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                }
                
                if($_FILES['manifiesto']['name']){ // Manifiesto
                    $selecionarEvi = $conexion->selecionarRegistro("tabla_certificados", "id = ". $id);
                    $direcionM = "../EvidenciasSub/Manifiestos/".$nombreCarpeta."/".$_FILES['manifiesto']['name'];
                    $direcionMAn = "../EvidenciasSub/Manifiestos/".$nombreCarpeta."/".$selecionarEvi['manifiesto'];
                    
                    if($selecionarEvi['manifiesto'] == ""){
                        $valores['manifiesto'] = $_FILES['manifiesto']['name'];
                        $sentencias = $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                        $archivo->agregarArchivo($_FILES['manifiesto']['tmp_name'], $direcionM);
                    }
                    else {
                        $valores['manifiesto'] = $_FILES['manifiesto']['name'];
                        $sentencias = $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                        if(file_exists($direcionMAn)){
                            $archivo->borrarArchivos($direcionMAn);
                            $archivo->agregarArchivo($_FILES['manifiesto']['tmp_name'], $direcionM);
                        }
                    }
                }

                if($_FILES['nota']['name']){ // Nota
                    $selecionarEvi = $conexion->selecionarRegistro("tabla_certificados", "id = ". $id);
                    $direcionN = "../EvidenciasSub/Notas/".$nombreCarpeta."/".$_FILES['nota']['name'];
                    $direcionNAn = "../EvidenciasSub/Notas/".$nombreCarpeta."/".$selecionarEvi['nota'];

                    if($selecionarEvi['nota'] == ""){
                        $valores['nota'] = $_FILES['nota']['name'];
                        $sentencias = $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                        $archivo->agregarArchivo($_FILES['nota']['tmp_name'], $direcionN);
                    }
                    else {
                        $valores['nota'] = $_FILES['nota']['name'];
                        $sentencias = $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                        if(file_exists($direcionNAn)){
                            $archivo->borrarArchivos($direcionNAn);
                            $archivo->agregarArchivo($_FILES['nota']['tmp_name'], $direcionN);
                        }
                    }
                }

                if($_FILES['n_evidencias']['name']){ 
                    $selecionarEvi = $conexion->selecionarRegistro("tabla_certificados", "id = ". $id);
                    $direcionE = "../EvidenciasDoc/".$nombreCarpeta."/".$_FILES['n_evidencias']['name'];
                    $direcionEAn = "../EvidenciasDoc/".$nombreCarpeta."/".$selecionarEvi['n_evidencias'];  
                    if($selecionarEvi['n_evidencias'] == ""){
                        $valores['n_evidencias'] = $_FILES['n_evidencias']['name'];
                        $sentencias = $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                        $archivo->agregarArchivo($_FILES['n_evidencias']['tmp_name'], $direcionE);
                    }
                    else{
                        $valores['n_evidencias'] = $_FILES['n_evidencias']['name'];
                        $sentencias = $conexion->actualizarRegistro("tabla_certificados", $valores, $id);
                        if(file_exists($direcionEAn)){
                            $archivo->borrarArchivos($direcionEAn);
                            $archivo->agregarArchivo($_FILES['n_evidencias']['tmp_name'], $direcionE);
                        }
                    }
                }
                header("Location:subEvidencias.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
            }
        }

        if($accion == 2){ // Eliminar
            $id = $_GET['id'];
            
            $selecionarEvi = $conexion->selecionarRegistro("tabla_certificados", "id = ".$id);

            if($selecionarEvi['manifiesto']){
                $direcionMAn = "../EvidenciasSub/Manifiestos/".$nombreCarpeta."/".$selecionarEvi['manifiesto'];
                $archivo->borrarArchivos($direcionMAn);
            }
            if($selecionarEvi['nota']){
                $direcionNAn = "../EvidenciasSub/Notas/".$nombreCarpeta."/".$selecionarEvi['nota'];                
                $archivo->borrarArchivos($direcionNAn);
            }
            if($selecionarEvi['n_evidencias']){
                $direcionEAn = "../EvidenciasDoc/".$nombreCarpeta."/".$selecionarEvi['n_evidencias'];
                $archivo->borrarArchivos($direcionEAn);
            }
            $conexion->borrarRegistro("tabla_certificados", "", $id);
            header("Location:subEvidencias.php?carpeta=$carpeta&nombreCarpeta=$nombreCarpeta");
        }
        
        $resultadoEvidencias = $conexion->selecionarRegistro("tabla_certificados", "");
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $regpagina = 15;
        $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;
        $Numeropaginas = $conexion->paginador($pagina, $regpagina, $inicio);
    ?>

    <h1>Evidencias <?php echo $nombreCarpeta; ?></h1>
    <br>
    
    <?php if($errores->siExiste()){ $errores->imprimirErrores(); } ?>

    <?php if($accion == 1){ $evidenciaAnterior = $conexion->selecionarRegistro("tabla_certificados", "id = ". $id); ?>
        <h2>Cambiar evidencia</h2>
        <form class="formActualizar" action="?carpeta=<?php echo $carpeta;?>&form=2&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <input type="date" name="fecha" value="<?php echo $evidenciaAnterior['fecha']; ?>">
            
            <?php if($evidenciaAnterior['manifiesto']){ ?> 
                <input id="manifiestoAnteriro" type="file" name="manifiesto" class="inputOculto">
                <label class="labelEdicion" for="manifiestoAnteriro" class="manifiesto"><i class="fa-regular fa-file-image estiloOjo"></i></label>
            <?php } else { ?>
                <input id="manifiestoAnteriro" type="file" name="manifiesto" class="inputOculto">
                <label class="labelEdicion" for="manifiestoAnteriro" class="manifiesto">Manifiesto    <i class="fa-solid fa-upload"></i></label>
            <?php } ?>

            <?php if($evidenciaAnterior['nota']){ ?> 
                <input id="notaAnterior" type="file" name="nota" class="inputOculto">
                <label class="labelEdicion" for="notaAnterior" class="manifiesto"><i class="fa-solid fa-clipboard estiloOjo"></i></label>
            <?php } else { ?>
                <input id="notaAnterior" type="file" name="nota" class="inputOculto">
                <label class="labelEdicion" for="notaAnterior" class="manifiesto">Nota    <i class="fa-solid fa-upload"></i></label>
            <?php } ?>
            
            <?php if($evidenciaAnterior['n_evidencias']){ ?> 
                <input id="evidenciaAnterior" type="file" name="n_evidencias" class="inputOculto">
                <label class="labelEdicion" for="evidenciaAnterior" class="manifiesto"><i class="fa-regular fa-file-pdf estiloOjo"></i></label>
            <?php } else { ?>
                <input id="evidenciaAnterior" type="file" name="n_evidencias" class="inputOculto">
                <label class="labelEdicion" for="evidenciaAnterior" class="manifiesto">Evidencia    <i class="fa-solid fa-upload"></i></label>
            <?php } ?>

            <td class="relleno"><button class="botonCarpetasG">Guardar</button></td>
        </form>
    <?php } ?>

    <table class="tabla">
        <thead>
            <tr>
                <td>Fecha de recolección</td>
                <td>Manifiesto</td>
                <td>Notas</td>
                <td>Evidencia</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr class="formTabla">    
                <form action="?form=1&carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>" method="post" enctype="multipart/form-data">
                    <td><input type="date" name="fecha"></td>
                    <td>
                        <input id="manifiesto" type="file" name="manifiesto" class="inputOculto">
                        <label for="manifiesto" class="manifiesto">Manifiesto    <i class="fa-solid fa-upload"></i></label>
                    </td>
                    <td>
                        <input id="nota" type="file" name="nota" class="inputOculto">
                        <label for="nota" class="manifiesto">Nota    <i class="fa-solid fa-upload"></i></label>
                    </td>
                    <td>
                        <input id="evidencia" type="file" name="n_evidencias" class="inputOculto">
                        <label for="evidencia" class="manifiesto">Evidencia    <i class="fa-solid fa-upload"></i></label>
                    </td>
                    <td class="relleno"><button class="botonCarpetasG">Guardar</button></td>
                </form>
            </tr>
            <?php $a=0; foreach($resultadoEvidencias as $resultadoEvidencia){ ?>
                <tr class="<?php if(($a % 2) == 0){echo"verdeObscuro";}else{echo"verdeClaro";} ?>">

                    <td><?php echo $resultadoEvidencia['fecha']; ?></td>

                    <?php if($resultadoEvidencia['manifiesto'] == ''){ ?>
                    <td class="noPaddin">
                        <a href="?carpeta=<?php echo $carpeta;?>&accion=1&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $resultadoEvidencia['id']; ?>" class="btnSubCertificado">Subir Manifiesto</a>
                    </td>
                    <?php }else{ ?>
                        <td><?php echo $resultadoEvidencia['manifiesto']; ?></td>
                    <?php } ?>

                    <?php if($resultadoEvidencia['nota'] == ''){ ?>
                    <td class="noPaddin">
                        <a href="?carpeta=<?php echo $carpeta;?>&accion=1&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $resultadoEvidencia['id']; ?>" class="btnSubCertificado">Subir Nota</a>
                    </td>
                    <?php }else{ ?>
                        <td><?php echo $resultadoEvidencia['nota']; ?></td>
                    <?php } ?>

                    <?php if($resultadoEvidencia['n_evidencias'] == ''){ ?>
                    <td class="noPaddin">
                        <a href="?carpeta=<?php echo $carpeta;?>&accion=1&nombreCarpeta=<?php echo $nombreCarpeta; ?>&id=<?php echo $resultadoEvidencia['id']; ?>" class="btnSubCertificado">Subir Evidencia</a>
                    </td>
                    <?php }else{ ?>
                        <td><?php echo $resultadoEvidencia['n_evidencias']; ?></td>
                    <?php } ?>

                    <td class="centrarIconos">
                        <a href="?accion=1&id=<?php echo $resultadoEvidencia['id']; ?>&carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                        <a href="?accion=2&id=<?php echo $resultadoEvidencia['id']; ?>&carpeta=<?php echo $carpeta; ?>&nombreCarpeta=<?php echo $nombreCarpeta; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                    </td>
                </tr>
            <?php $a++;} ?>
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
<!-- Numero de lineas 360 -->