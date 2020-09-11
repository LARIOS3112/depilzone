<?php
session_start();
ob_start();
include('conexionbd.php');

//<!-- <9/8/19 GR - modificacion para guardar DNI del cliente >-->

$id_us      = $_POST['id_us'];
$nombre     = $_POST['nombre'];
$seudoni    = $_POST['seudoni'];
$apellido   = $_POST['apellido'];
$dni        = $_POST['dni'];
$sexo       = $_POST['sexo'];
$prefijo    = $_POST['prefijo'];
$telef      = $_POST['telef'];
$prefijo2   = $_POST['prefijo2'];
$telef2     = $_POST['telef2'];
$distrito   = $_POST['distrito'];
$red_social = $_POST['red_social'];
$publicidad = $_POST['publicidad'];
$cumple     = $_POST['cumple'];

//$num_historia   = $_POST['num_historia'];
//$num_cliente= $_POST['num_cliente'];


$correo   = (isset($_POST["correo"]))?$_POST["correo"]:[];
$red_social   = (isset($_POST["red_social"]))?$_POST["red_social"]:[];
$otra_red = (isset($_POST["otra_red"]))?$_POST["otra_red"]:[]; 


$guardar = $_POST['guardar'];
$num     = count($guardar);

//FOR PARA AGREGAR CLIENTES SIMULTANEAMENTE

    for($i=0; $i<$num; $i++){
        $id_usX      = $id_us[$i];
        $nombrenX    = $nombre[$i];
        $seudoniX    = $seudoni[$i];
        $apellidoX   = $apellido[$i];
        $dniX        = $dni[$i];
        //$clienteX    = $num_cliente[$i];
        $sexoX       = $sexo[$i];
        $prefijoX    = $prefijo[$i];
        $telefX      = $telef[$i];
        $prefijo2X   = $prefijo2[$i];
        $telef2X     = $telef2[$i];
        $distritoX   = $distrito[$i];
        $red_socialX = $red_social[$i];
        $correoX     = $correo[$i];
        $otra_redX   = $otra_red[$i];
        $publicidadX = $publicidad[$i];
        $cumpleX     = $cumple[$i];
        $fechaaltaX  = date("Y-m-d");

        $sql1= "SELECT COUNT(id) cant FROM cliente where nombrec='$nombrenX' and apellidoc='$apellidoX' and telefonoc='$telefX'";
        $resultado1= mysqli_query($conexion, $sql1);
        $res=mysqli_fetch_array($resultado1);
        $cantidad=$res['cant'];

       /* $sql2= "SELECT COUNT(id) num FROM cliente where id='$clienteX'";
        $resultado2= mysqli_query($conexion, $sql2);
        $res2=mysqli_fetch_array($resultado2);
        $cantidad2=$res2['num'];*/
         //echo $cantidad2;
    }


 //CONDICIONES ANTES DE AGREGAR AL CLIENTE

    if ($cantidad>=1) 
    {
        echo "<script type='text/javascript'>
        window.location.href='nuevaficha1.php?errorcliente=error';  
             </script>";
         exit;
    }
    /*elseif($cantidad2>=1) {
        echo "<script type='text/javascript'> 
              window.location.href='nuevaficha1.php?errorcliente=error';  
             </script>";
         exit;
    }*/
    elseif($id_usX==null) {
         echo "<script type='text/javascript'>     
             window.top.location.href = 'sesion.php?errorsession=error'; 
             </script>";  
        exit;
    }
    else {
        $sql = "INSERT INTO cliente(fechaalta ,nombrec, seudoni,apellidoc, dni, id_sexo, prefijo, telefonoc, prefijo2, telefonoc2, correo, medio, medio1,
                                    direccion, status, us_ing, fech_ing, publicidad, cumple)    
                VALUES('$fechaaltaX', '".strtoupper($nombrenX)."', '".strtoupper($seudoniX)."', '".strtoupper($apellidoX)."', '$dniX','$sexoX', '$prefijoX', '$telefX', '$prefijo2X', '$telef2X', '$correoX', '$red_socialX', '$otra_redX',
                                    '$distritoX', 'A', '$id_usX', CURRENT_TIMESTAMP(), '$publicidadX', '$cumpleX')";
        $resultado = mysqli_query($conexion, $sql);
           //echo $sql;
        // seleccion ultimo id de cliente
        $sql2 = "SELECT id FROM cliente WHERE us_ing = '$id_usX' ORDER BY fech_ing DESC LIMIT 1";
        $resultado  = mysqli_query($conexion,$sql2);
        $lista      = mysqli_fetch_array($resultado);
        $id_cliente = $lista['id'];                                           
       
        header("location:agendar.php?id_cliente=".urldecode($id_cliente)."&id_usuario=".urldecode($id_usX)); 
        mysqli_close($conexion);
        ob_end_flush();
     }
?>
