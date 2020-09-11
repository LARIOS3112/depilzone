<?php 
session_start(); 
if($_SESSION["tiene_permiso"]==false){
    header("location: sesion.php");
?>

<script type="text/javascript">
    alert("Datos incorrectos intente de nuevo");
</script>
<?php 
}

/*esta es la pagina a la que seran enviados los usuarios luego de iniciar sesion */
/*los tipos de usuarios son:
1. Administrador: Full acceso a todos los modulos.
2. Coordinador= 
3. Marketing=
4. Teleoperadora=  
5. Otro=
6. Especialista=
 
*/

$tipodeusuario  =$_SESSION['tipo'];


if($tipodeusuario == 1 or $tipodeusuario == 8 or $tipodeusuario == 9 or $tipodeusuario == 10){
    header("location: index.php");
}
if($tipodeusuario == 2){
     header("location: principal_modulos.php");
}
if($tipodeusuario == 3 or $tipodeusuario == 4){
    header("location: principal_modulos.php");
}
//if($tipodeusuario == 4){
//    header("location: teleoperadora.php");
//}
//if($tipodeusuario == 5){
//    echo"<label> soy teleoperadora</label>";
//}
if($tipodeusuario == 6){
  header("location: principal_modulos.php");
}
if($tipodeusuario == 7){
  header("location: principal_modulos.php");
}
?>