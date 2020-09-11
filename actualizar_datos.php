<?php
session_start();
$id_us = $_SESSION['id'];
//$fechah = datetime("Y-m-d H:i:s");
////MODIFICADO POR ING. GABRIELA 03/05/2019, UPDATE
//<!-- <9/8/19 GR - modificacion para actualizar el DNI>-->
include ('conexionbd.php');
$id        = $_POST['id'];
$historia  = $_POST['historia'];
$nombre    = $_POST['nombre'];
$seudoni   = $_POST['seudoni'];
$apellido  = $_POST['apellido'];
$dni       = $_POST['dni'];
$prefijo   = $_POST['prefijo'];
$telefono  = $_POST['telefono'];
$prefijo2  = $_POST['prefijo2'];
$telefono2 = $_POST['telefono2'];
$correo    = $_POST['correo'];
$direccion = $_POST['direccion'];
$status    = $_POST['status'];
$id_sexo   = $_POST['id_sexo'];
$publicidad   = $_POST['publicidad'];
$cumple   = $_POST['cumple'];

if(isset($_POST['motivo_camb_status'])){
    $motivo = $_POST['motivo_camb_status'];
}else{
    $motivo = '';
}


$status_old = '';
$query_old = "Select status from cliente WHERE id = '$id'";
if($result_old = mysqli_query($conexion, $query_old)){
    
    $row=mysqli_fetch_array($result_old);
    $status_old = $row['status'];
}


if($status_old != $status){

    if($motivo !=''){

        $query = "UPDATE cliente SET historia = '$historia', nombrec = '$nombre', apellidoc = '$apellido', dni = '$dni', id_sexo = '$id_sexo', prefijo = '$prefijo', telefonoc = '$telefono', prefijo2 = '$prefijo2', telefonoc2 = '$telefono2', correo = '$correo', direccion = '$direccion', status = '$status', us_mod = '$id_us', fech_mod = CURRENT_TIMESTAMP(), publicidad = '$publicidad', cumple = '$cumple' 
                       WHERE id = '$id'";

        if($status == 'I'){
            $query2 = "UPDATE citas SET id_asistencia = 4, us_mod = '$usuario', tiempo_tto = 'definitivo', fech_mod = CURRENT_TIMESTAMP()  /*cliente inactivo */
                                WHERE id_cliente = '$id'
                                    AND id_asistencia = 1";
            $result2 = mysqli_query($conexion, $query2);

            $query_historial_status = "INSERT INTO cli_historial_status(id_usuario, id_cliente, status_anterior, status_nuevo, motivo, fecha) VALUES ('$id_us','$id','$status_old','$status','$motivo',CURRENT_TIMESTAMP())";
            $result_historial_status = mysqli_query($conexion, $query_historial_status);

        }
        elseif($status == 'F'){
            $query2 = "UPDATE citas SET id_asistencia = 3, us_mod = '$usuario', tiempo_tto = 'temporal',  fech_mod = CURRENT_TIMESTAMP()  /*cliente suspendido */
                                WHERE id_cliente = '$id'
                                    AND id_asistencia = 1";
                $result2 = mysqli_query($conexion, $query2);

                $query_historial_status = "INSERT INTO cli_historial_status(id_usuario, id_cliente, status_anterior, status_nuevo, motivo, fecha) VALUES ('$id_us','$id','$status_old','$status','$motivo',CURRENT_TIMESTAMP())";
                $result_historial_status = mysqli_query($conexion, $query_historial_status);
            }
            else{
                $query2 = "UPDATE citas SET id_asistencia = 1, us_mod = '$usuario', tiempo_tto = '', motivo_no_tto= '', fech_mod = CURRENT_TIMESTAMP() /*cliente activo */
                                    WHERE id_cliente = '$id'
                                        AND id_asistencia = 3 OR id_asistencia = 4";
                $result2 = mysqli_query($conexion, $query2);

                $query_historial_status = "INSERT INTO cli_historial_status(id_usuario, id_cliente, status_anterior, status_nuevo, motivo, fecha) VALUES ('$id_us','$id','$status_old','$status','$motivo',CURRENT_TIMESTAMP())";
                $result_historial_status = mysqli_query($conexion, $query_historial_status);
            }
    }


}else{
    $query = "UPDATE cliente SET historia = '$historia', nombrec = '$nombre', apellidoc = '$apellido', dni = '$dni', id_sexo = '$id_sexo', prefijo = '$prefijo', telefonoc = '$telefono', prefijo2 = '$prefijo2', telefonoc2 = '$telefono2', correo = '$correo', direccion = '$direccion', status = '$status', us_mod = '$id_us', fech_mod = CURRENT_TIMESTAMP(), publicidad = '$publicidad', cumple = '$cumple'
                       WHERE id = '$id'";
}

$result = mysqli_query($conexion,$query);



echo $result;
return $result;
?>
