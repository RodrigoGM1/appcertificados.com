<?php
    if($_SESSION['privilegios'] == 2){
        header("Location:../controllers/cerrar.php");
    }
    
    $pagina = "inicio";
    include "../templates/header.php";
    include "../controllers/classConexion.php";

    $conexion = new ConexionBD();
    $carpetas = $conexion->selecionarRegistro("tabla_carpetas", "");
?>
<main class="main_inicio">
    <h1>Carpetas</h1>
    <?php foreach($carpetas as $carpeta) { ?>
    <a href="verCertificado.php?carpeta=<?php echo $carpeta['id']; ?>&nombreCarpeta=<?php echo $carpeta['nombre_carpeta']; ?>" class="contenedor_carpeta">
        <i class="fa-regular fa-folder-open"></i>
        <p><?php echo $carpeta['nombre_carpeta']; ?></p>
    </a>
    <?php } ?>
</main>
<?php
    include "../templates/footer.php"; 
?>