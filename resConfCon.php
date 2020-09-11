<?php       
    session_start();
    include 'conexion.php';
    if($conexionJVG->connect_errno) {
        die('No se pudo conectar a la base de datos');
    }       
    if(!isset($_SESSION['id'])){ 
        header('Location: index.php');  
        exit(); 
    }else{
        $id=$_SESSION['id'];
    }     
    if ( $result = $conexionJVG->query("update users set conexion=1,req_conf_con=0 where id=$id") ) {  
        $jsondata["success"] = true;
        $jsondata["data"] = array(
            'msg' => 'No se actualizo.'
        );       
    } else{
        $jsondata["success"] = false;
        $jsondata["data"] = array(
            'msg' => $conexionJVG->error
        );
    };         
    echo json_encode($jsondata);	
    $conexionJVG->close();
    exit(); 
?>