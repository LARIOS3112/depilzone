<!-- CREA LOS NUMEROS DE HISTORIA AUTOMATICO-->

<!DOCTYPE html>

<?php
session_start();
include('conexionbd.php');


$valor = $_REQUEST['q'];

$sql = "SELECT count(id) id1
          FROM cliente 
         WHERE apellidoc LIKE '".$valor[0]."%' ";
$resultado = mysqli_query($conexion,$sql);
        
while($lista = mysqli_fetch_array($resultado)){
    $historia=$lista['id1']+1;
?>

<input type="text" class="form-control css-input" name="historia[]" value="<?php echo $valor[0]."-".$historia;?>" readonly> 
<?php 
}
?>
