<?php 
session_start();
include('conexionbd.php');
// Cierre  general de session
date_default_timezone_get('');
ini_set('date.timezone','America/Lima');

$usuario = $_SESSION['id'];

if(isset($_GET['expiro'])){
    $expiro = 0;
}

$horariod = date("H:i A");
$hoyd     = date('Y.m.d');
 		
$insert = "INSERT INTO desconectado (fecha, hora, usuario) VALUES('$hoyd', '$horariod', '$usuario')";//historial de desconexion en bd
$result = mysqli_query($conexion, $insert);

$online2 = "UPDATE users SET conexion = '0' WHERE id = '$usuario'"; // inserta 0 en el campo conexion de la BD para definirlo como desconectado
$onl2    = mysqli_query($conexion, $online2);
		
//Vaciamos y destruimos las variables de sesión
$_SESSION['usuario']       = NULL;
$_SESSION['clave']         = NULL;
$_SESSION['tipo']          = NULL;
$_SESSION["tiene_permiso"] = NULL;
$_SESSION["id"] = "";
    
/*unset($_SESSION['usuario']);
unset($_SESSION['clave']);
unset($_SESSION['tipo']);
unset($_SESSION["tiene_permiso"]); */


    
session_destroy();
//Redireccionamos al inicio de sesión
//header('location: sesion.php');
?> 
<script>         
 window.top.location.href = 'sesion.php';        
</script>