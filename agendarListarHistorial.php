<?php 
    session_start();
    //JVG: Codigo necesario para el archivo agendar.php
    include 'conexionbd.php';
    $dbserver = $db_host;
    $dbuser = $db_usuario;
    $password = $db_password;
    $dbname = $db_nombre;    
    $database = new mysqli($dbserver, $dbuser, $password, $dbname);
    if($database->connect_errno) {
        die('No se pudo conectar a la base de datos');
    }
    if(isset($_GET['id_cliente'])){
        $id_cliente=$_GET['id_cliente'];
    }
    $jsondata = array();      
    
    if ( $result = $database->query("select * FROM tarjeta_historial where id_cliente=$id_cliente") ) {            
        if( $result->num_rows > 0 ) {
            $jsondata["success"] = true;
            $jsondata["data"]["msg"] = "Correcto";               
            $jsondata["data"]["tarjeta_historial"]= array();
            while($row = $result->fetch_object()) {
                //se agrega el objeto a un array de objetos 
                $newRow= array();
                $newRow["id"] = $row->id;
                $newRow["descripcion"] = mb_detect_encoding($row->descripcion, 'UTF-8',true)?$row->descripcion:utf8_encode($row->descripcion);   
                $newRow["ingreso"] = $row->ingreso;  
                $newRow["total_puntos"] = $row->total_puntos;
                $newRow["saldo_total"] = $row->saldo_total;
                $jsondata["data"]["tarjeta_historial"][]=$newRow;
            };                
        }else{
            $jsondata["success"] = true;
            $jsondata["data"] = array(
                'msg' => 'No se encontró ningún resultado.'
            );
            $jsondata["data"]["tarjeta_historial"] = array();
        };
        $result->close();
    }else{
        $jsondata["success"] = false;
        $jsondata["data"] = array(
            'msg' => $database->error
        );
    };         
    echo json_encode($jsondata);	
    $database->close();
exit();
?>
