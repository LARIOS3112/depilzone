<?php
session_start();
include('../../conexionbd.php');

$rtado = "";

$cuenta = "SELECT count('id') cantidad FROM imagenes";
$resu   = mysqli_query($conexion, $cuenta);
$cant   = mysqli_fetch_array($resu);

$cantidad = $cant['cantidad'];
if($cantidad < 3){

    $info   = $_POST["info"];
    $titulo = $_POST["titulo"];
    $imagen = addslashes(file_get_contents($_FILES['files']['tmp_name']));
    
    //print($imagen);
    //$imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));

    $subir = "INSERT INTO imagenes(titulo, imagen, info) VALUES ('$titulo', '$imagen', '$info')";
    $rtado = mysqli_query($conexion, $subir);

    $minimo = "SELECT Min(id) minimo FROM imagenes";
    $numero = mysqli_query($conexion,$minimo);
    $num    = mysqli_fetch_array($numero);
    $minimo = $num['minimo'];

    $query = "UPDATE imagenes SET class = 'active' WHERE id = '$minimo'";
    $colocar = mysqli_query($conexion,$query);  
}

echo $rtado;
return $rtado;

?>