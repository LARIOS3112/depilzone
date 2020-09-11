<?php
include('conexion.php');

$distintos='SELECT * FROM cliente_old';
$rtado = mysqli_query($conexion,$distintos);


while ( $clinic =$rtado->fetch_assoc()) {                                                                                                                                                                                                                                                                                                                               

        $clienteX=$clinic["id"];
        $historiaX=$clinic["historia"];
      
      if($historiaX==''){
        $historiaX=0;      
      }
    
    
       $sql = "UPDATE `cliente` SET `historia`='".$historiaX."' WHERE `id`=".$clienteX."";
       $resultado = mysqli_query($conexion, $sql);
       echo  $sql;
      //echo 'idcliente='.$resulidcliente;
 }