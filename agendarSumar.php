<?php 
    session_start();
    //JVG: CODIGO NECESARIO PARA EL ARCHIVO agendar.php
    $tipodeusuario  =$_SESSION['tipo'];
    if(isset($_POST["id_cliente"])){
        $id_cliente = $_POST["id_cliente"];
    }
    if(isset($_POST["descrip"])){
        $descrip = $_POST["descrip"];
    }
    if(isset($_POST["tarjeta"])){
        $tarjeta = $_POST["tarjeta"];
    }
    if(isset($_POST["puntos"])){
        $puntos = intval($_POST["puntos"]);
    } 
    if(isset($_POST["puntos2"])){
        $puntos2 = intval($_POST["puntos2"]);
    }  
    $totalPuntos=intval($puntos)+intval($puntos2);
    $saldo=$totalPuntos*0.10;
    include 'conexionbd.php';
    $dbserver = $db_host;
    $dbuser = $db_usuario;
    $password = $db_password;
    $dbname = $db_nombre;    
    $database = new mysqli($dbserver, $dbuser, $password, $dbname);
    if($database->connect_errno) {
        die('No se pudo conectar a la base de datos');
    }  
    $query = "update tarjeta set puntos=(puntos + ($puntos2)) where id_cliente=$id_cliente";    
    if ($resQuery = mysqli_query($database, $query)) {
        $result["success"] = true;
        $result["data"]["msg"] = "Actualizado correctamente";
    }else{
        $result["success"] = false;
        $result["data"]["msg"] = "No se pudo actualizar puntos";
    } 
    $query = "INSERT INTO tarjeta_historial(tarjeta, id_cliente, descripcion, ingreso, total_puntos, saldo_total) values ('$tarjeta', $id_cliente, '$descrip', $puntos2,$totalPuntos, $saldo)";    
    if ($resQuery = mysqli_query($database, $query)) {
        $result["success"] = true;
        $result["data"]["msg"] = "Guardado correctamente";
    }else{
        $result["success"] = false;
        $result["data"]["msg"] = $database->error;
    } 

    echo json_encode($result);
    $database->close();
    exit();    
?>