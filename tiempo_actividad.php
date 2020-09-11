<?php
session_start();
$user=$_SESSION['usuario'];
//crear la conexion
include('conexionbd.php');
//antes de hacer los cálculos, compruebo que el usuario está logueado 
//utilizamos el mismo script que antes 

//echo $_SESSION["autentificado"];
if ($_SESSION["autentificado"] != "SI") { 
    //si no está logueado lo envío a la página de autentificación 
    //header("Location: index.php"); 
    ?>
        <script>
            //alert('Cierre de la Sesion N1!!!');
            window.top.location.href = '../siah/sesion.php';
        </script>
    <?php 	
} else { 
    
   
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["ultimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
    //echo  $ahora;
  //  echo  $tiempo_transcurrido ;
    
    
    
}
    //comparamos el tiempo transcurrido 
     
     if($tiempo_transcurrido >= 85) { //min
         
        $act_actividad="UPDATE users SET conexion = '0' WHERE usuario = '$user'";
        $act = mysqli_query($conexion,$act_actividad);
         
         
            
         
     //si pasaron 2 minutos o más 
      session_destroy();
         
         
         
       

      //session_destroy(); // destruyo la sesión 
      //header("Location: ../../../index.php"); //envío al usuario a la pag. de autenticación 
    ?>
        <script>
           //alert('Su sesion va a expirar por inactividad!');
           window.top.location.href = '../siah/sesion.php';
        </script>
    <?php  
    exit;
      //sino, actualizo la fecha de la sesión 
    }
      else { 
    $_SESSION["ultimoAcceso"] = $ahora; 
    //echo $ahora; exit;
   } 

//cerrar base de datos
mysqli_close($conexion); 

?>