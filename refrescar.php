<?php	
session_start();
//crear la conexion
include('conexionbd.php');
$consulta = "SELECT usuario 
               FROM users 
              WHERE conexion= '1' 
                AND status = 'T'";
//ejecutar consulta
$resultado = mysqli_query($conexion,$consulta);
//repasar resultados
while($fila = mysqli_fetch_array($resultado)){
    echo "<center><h5 class='dropdown-header' style='color: green'><strong>$fila[0]</strong></h5></center>";
}

/*<dt style='color: green'><center>$fila[0]</center></dt>*/
?>