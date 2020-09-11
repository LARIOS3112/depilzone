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
    if ( $result = $conexionJVG->query("select conexion,req_conf_con from users where id=$id") ) {            
        if( $result->num_rows > 0 ) {
            $jsondata["success"] = true;
            $jsondata["data"]["msg"] = "Correcto";               
            $jsondata["data"]["model"]= array();
            while($row = $result->fetch_object()) {
                $jsondata["data"]["model"]=$row;
            };                
        } else{
            $jsondata["success"] = true;
            $jsondata["data"] = array(
                'msg' => 'No se encontró ningún resultado.'
            );
            $jsondata["data"]["model"] = array();
        }
        $result->close();
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