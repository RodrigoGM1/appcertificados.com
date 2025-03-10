<?php
    $pagina = "usuario";
    include "../../templates/header.php"; 
    include "../../controllers/classError.php"; 
    include "../../controllers/classConexion.php"; 

    if($_SESSION['privilegios'] == 2){
        header("Location:../../controllers/cerrar.php");
    }

    $errores = new ClassErrores();
    $conexion = new ConexionBD();

    
?>
<main class="main_inicio">

    <?php
        $form = isset($_GET['form']) ? $_GET['form']: '';
        $accion = isset($_GET['accion']) ? $_GET['accion']: '';
        $id = isset($_GET['id']) ? $_GET['id']: '';

        $usuarios = $conexion->selecionarRegistro("tabla_usuarios", "");
        $tabPrivilegios = $conexion->selecionarRegistro("tabla_privilegios", "");
        $carpetas = $conexion->selecionarRegistro("tabla_carpetas", "");

        $usuario = '';
        $clave = '';
        $privilegio = '';
        $carpeta = '';
        if($form == 1){ // Creacion de usuario
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $usuario = $_POST['usuario'];
                $clave = $_POST['clave'];
                $privilegio = $_POST['idprivilegio'];
                $carpeta = $_POST['carpeta'];
    
                if(!$privilegio){
                    $errores->setError("Añada los privilegios para el usuario");
                }
    
                if($privilegio == 1){ // Creacion de usuario Admin
                    if(!$usuario){
                        $errores->setError("Añada un nombre para el usuario");
                    }
                    if(!$clave){
                        $errores->setError("Añada un contraseña para usuario");
                    }
                    if(!$errores->siExiste()){
                        $valores = $_POST;

                        $conexion->inseratRegistro("tabla_usuarios", $valores);
                        header("Location: index.php");
                    }
                }
    
                if($privilegio == 2){ // Creacion de usuario Normal
                    
                    if(!$usuario){
                        $errores->setError("Añade nombre del usuario");
                    }
                    if(!$clave){
                        $errores->setError("Añade una contraseña");
                    }
                    if(!$carpeta){
                        $errores->setError("Añade una carpeta");
                    }
                    if(!$errores->siExiste()){
                        $valoresUsuario = [];
                        $valoresCarpeta = [];

                        $valoresUsuario['usuario'] = $_POST['usuario'];
                        $valoresUsuario['clave'] = $_POST['clave'];
                        $valoresUsuario['idprivilegio'] = $_POST['idprivilegio'];

                        $valoresCarpeta['usuario'] = $_POST['usuario'];
                        $valoresCarpeta['carpeta'] = $_POST['carpeta'];
                        
                        var_dump($conexion->inseratRegistro("tabla_usuarios", $valoresUsuario));
                        var_dump($conexion->inseratRegistro("tabla_usuario_carpeta", $valoresCarpeta));
                        
                        header("Location: index.php");
                    }
                }
            }
        }

        if($form == 2){ // Actualizar usuario
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $valores = $_POST;
                $clave = $_POST['clave'];
                if(!$clave){
                    !$errores->siExiste("Añada un contraseña para usuario");
                }
                if(!$errores->siExiste()){
                    $conexion->actualizarRegistro("tabla_usuarios", $valores, $id);
                    header("Location: index.php");
                }
            }
        }

        if($accion == 2){ // Eliminacion usuario
            $id = $_GET['id'];
            //Falta modificaciones
            var_dump($resultado = $conexion->borrarRegistro("tabla_usuarios", "", $id));
            header("Location: index.php");
        }
    ?>

    <h1>Usuarios</h1>
    <div class="contemedotrUsuario">
        <h3>Administradores</h3>
        <a class="botonUsuarios">Nuevo Usuario</a>
    </div>

    <?php if($errores->siExiste()){ $errores->imprimirErrores(); } ?>

    <table class="tabla">
        <thead>
            <tr>
                <td>Usuario</td>
                <td>Privilegios</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; foreach($usuarios as $impUsuarios){ if($impUsuarios['idprivilegio'] == 1){ ?>
            <tr class="<?php if(($i % 2) == 0){ echo "verdeObscuro"; }else{ echo "verdeClaro"; } ?> verdeObscuro">
                <td><?php echo $impUsuarios['usuario']; ?></td>
                <td>Administrador</td>
                <td>
                    <a href="?accion=1&id=<?php echo $impUsuarios['id']; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                    <a href="?accion=2&id=<?php echo $impUsuarios['id']; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                </td>
            </tr>
            <?php $i++; } } ?>
        </tbody>
    </table>

    <div class="contemedotrUsuario">
        <h3>Usuario</h3>
    </div>
    <table class="tabla">
        <thead>
            <tr>
                <td>Usuario</td>
                <td>Privilegios</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; foreach($usuarios as $impUsuarios){ if($impUsuarios['idprivilegio'] == 2){ ?>
            <tr class="<?php if(($i % 2) == 0){ echo "verdeObscuro"; }else{ echo "verdeClaro"; } ?> verdeObscuro">
                <td><?php echo $impUsuarios['usuario']; ?></td>
                <td>Usuario</td>
                <td>
                    <a href="?accion=1&id=<?php echo $impUsuarios['id']; ?>"><i class="fa-regular fa-pen-to-square estiloOjo"></i></a>
                    <a href="?accion=2&id=<?php echo $impUsuarios['id']; ?>"><i class="fa-regular fa-trash-can estiloBasura"></i></a>
                </td>
            </tr>
            <?php $i++; } } ?>
        </tbody>
    </table>

    <div id="ventanaModal" class="modalDialogo">
        <div class="contenedorModal">
            <a href="#" title="Cerrar" class="cerrar">x</a>
            <h2>Datos del usuario</h2>
            <form action="?form=1" class="formUsuarios" method="post">
                <input class="inputUsuario" type="text" placeholder="Usuario" name="usuario">
                <input class="inputUsuario" type="password" placeholder="Contraseña" name="clave">
                <div class="contSelectUsuario">
                    <label>Privilegios</label>
                    <select class="inputUsuario" name="idprivilegio">
                        <option value="">-- Privilegios --</option>
                        <?php foreach($tabPrivilegios as $tabPrivilegio){ ?>
                        <option value="<?php echo $tabPrivilegio['id']; ?>"><?php echo $tabPrivilegio['nombreprivilegio']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br><br><br>
                <div class="contSelectUsuario">
                    <p>En caso de usuario asignar una carpeta</p>
                    <label>Carpetas</label>
                    <select class="inputUsuario" name="carpeta">
                        <option value="">-- Carpetas --</option>
                        <?php foreach($carpetas as $carpeta) { ?>
                            <option value="<?php echo $carpeta['id']; ?>"><?php echo $carpeta['nombre_carpeta']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button class="botonModal">Guardar</button>
            </form>
        </div>
    </div>
    <br>
    <?php
        if($accion == 1){
            $resUsuario = $conexion->selecionarRegistro("tabla_usuarios", "id = ".$id);
    ?>
        <div class="border">
            <div>
                <h2>Datos del usuario:</h2>
                <form action="?form=2&id=<?php echo $resUsuario['id']; ?>" class="formUsuarios" method="post">
                    <input class="inputUsuario" type="text" placeholder="Usuario" readonly="readonly"  value="<?php echo $resUsuario['usuario']; ?>">
                    <input class="inputUsuario" type="password" placeholder="Contraseña" name="clave" value="<?php echo $resUsuario['clave']; ?>">
                    <button class="botonModal">Guardar</button>
                </form>
            </div>
        </div>
    <?php } ?>
</main>
<?php
    include "../../templates/footer.php"; 
?>