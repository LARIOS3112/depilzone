<?php 

    session_start();

    $tipodeusuario  =$_SESSION['tipo'];
    
    if( isset($_GET['id_tto']) ) {
        $id_tto=$_GET['id_tto'];
    }
    if( isset($_GET['id_sede']) ) {
        $id_sede=$_GET['id_sede'];
    }
    if( isset($_GET['fecha']) ) {
        $fecha=$_GET['fecha'];
    }
    if( isset($_GET['id_asistencia']) ) {
        $id_asistencia=$_GET['id_asistencia'];
    }
    if( isset($_GET['conf_ds']) ) {
        $conf_ds=$_GET['conf_ds'];
    }
    if(isset($_GET['tipo_cita'])){
        $tipo_cita=$_GET['tipo_cita'];
    }else{
        $tipo_cita="";
    }
    //en este caso se puso el proceso dentro de una funcion que se dispara si existe el parametro checkEliminados

    //aunque no es necesario esta forma, cuestion de como se haya aprendido.    

    include 'conexionbd.php';
    $dbserver = $db_host;
    $dbuser = $db_usuario;
    $password = $db_password;
    $dbname = $db_nombre;    
    $database = new mysqli($dbserver, $dbuser, $password, $dbname);
    if($database->connect_errno) {
        die('No se pudo conectar a la base de datos');
    }  
    $jsondata["success"] = true;
    $jsondata["data"]["msg"] = "Correcto"; 
    $jsondata["data"]["citas"]= array();
    if($id_sede==""){
        $whereSede="";
    }else{
        $whereSede=" AND c.id_sede = $id_sede ";
    } 
    if($id_asistencia==""){
        $whereAsistencia="";
      
    }else{
        $whereAsistencia=" AND c.id_asistencia = $id_asistencia ";       
    } 
    if($conf_ds!=""){
        $whereAsistencia=$whereAsistencia." and c.conf_ds=$conf_ds";
    }  
    if($tipo_cita!=""){
        $whereTipoCita=$whereAsistencia." and c.cod_cit_tipo=$tipo_cita";
    }else{
        $whereTipoCita="";
    }
    if($id_tto=='1'){
        //consulta solo depilacion   
      
        if ( $result_depilacion = $database->query("SELECT tratamientos.id tto_id,tratamientos.nombre tto_nombre, tratamientos.url tto_url,c.id id_ci, c.id_cliente id_cli, c.hora, DATE_FORMAT(c.hora, '%h:%i %p') hora_format, c.fecha, cli.historia, cli.nombrec, cli.apellidoc,
        cli.telefonoc, c.avisos, c.detalles,c.envio, GROUP_CONCAT(z.partes ORDER BY c.fecha ASC SEPARATOR ' - ') resumen, ct.color, ct.descripcion tipo_cita,
        clit.descripcion tipo_cli, clit.color color_cli, pgt.descripcion tipo_pag, pgt.color color_pgt, pgt.codigo id_color_pgt, c.conf_ds, c.conf_ss, c.id_asistencia,
        (select group_concat(users.nombre) from conf_diarias inner join users on users.id=conf_diarias.id_teleop where conf_diarias.check_asig=1 and conf_diarias.id_cita=c.id group by conf_diarias.id_teleop limit 1) asignado_a
        FROM citas c  LEFT JOIN cliente cli on cli.id=c.id_cliente left join tratamientos on tratamientos.id=c.id_tratamiento, tabla_zonas t, zonas z, cit_tipo  ct, cli_tipo clit, pag_tipo pgt
        WHERE c.id = t.id_cita                           
        AND c.cod_cit_tipo = ct.codigo
        AND c.cod_cli_tipo = clit.codigo
        AND c.cod_pago_tipo = pgt.codigo
        AND t.id_zona = z.id
        AND c.fecha = '$fecha'
        AND tratamientos.id=1
        $whereSede
        $whereAsistencia
        $whereTipoCita
        GROUP BY c.id 
        ORDER BY c.hora ASC") ) {            
            if( $result_depilacion->num_rows > 0 ) {        
                while($row = $result_depilacion->fetch_object()) {        
                    //se agrega el objeto a un array de objetos         
                    $newRow= array();        
                    $newRow["id_ci"] = $row->id_ci;        
                    $newRow["id_cli"] = $row->id_cli;        
                    $newRow["hora"] = $row->hora;        
                    $newRow["hora_format"] = $row->hora_format;        
                    $newRow["fecha"] = $row->fecha;        
                    $newRow["historia"] = mb_detect_encoding($row->historia, 'UTF-8',true)?$row->historia:utf8_encode($row->historia);        
                    $newRow["nombrec"] =  mb_detect_encoding($row->nombrec, 'UTF-8',true)?$row->nombrec:utf8_encode($row->nombrec);;        
                    $newRow["apellidoc"] = mb_detect_encoding($row->apellidoc, 'UTF-8',true)?$row->apellidoc:utf8_encode($row->apellidoc);        
                    $newRow["telefonoc"] = $row->telefonoc;        
                    $newRow["avisos"] = mb_detect_encoding($row->avisos, 'UTF-8',true)?$row->avisos:utf8_encode($row->avisos);        
                    $newRow["detalles"] =  mb_detect_encoding($row->detalles, 'UTF-8',true)?$row->detalles:utf8_encode($row->detalles);        
                    $newRow["color"] = $row->color;
                    $newRow["envio"] = $row->envio;        
                    $newRow["resumen"] =  mb_detect_encoding($row->resumen, 'UTF-8',true)?$row->resumen:utf8_encode($row->resumen); 
                    $newRow["asignado_a"] =  mb_detect_encoding($row->asignado_a, 'UTF-8',true)?$row->asignado_a:utf8_encode($row->asignado_a);        
                    $newRow["tipo_cita"] = $row->tipo_cita;        
                    $newRow["tipo_cli"] = $row->tipo_cli;        
                    $newRow["color_cli"] = $row->color_cli;        
                    $newRow["tipo_pag"] = $row->tipo_pag;        
                    $newRow["color_pgt"] = $row->color_pgt; 
                    $newRow["id_color_pgt"] = $row->id_color_pgt;        
                    $newRow["conf_ds"] = $row->conf_ds;        
                    $newRow["conf_ss"] = $row->conf_ss;        
                    $newRow["id_asistencia"] = $row->id_asistencia;
                    $newRow['tto_url']=$row->tto_url;
                    $newRow['tto_id']=$row->tto_id;
                    $jsondata["data"]["citas"][]=$newRow;
                };
            } 
        }else{
            $jsondata["success"] = false;
            $jsondata["data"] = array(
                'msg' => $database->error
            );
        }
    }elseif($id_tto=='2'){
        //consulta solo adelgaza
        if ( $result_adelgaza = $database->query("SELECT tratamientos.id tto_id,tratamientos.nombre tto_nombre, tratamientos.url tto_url, c.id id_ci, c.id_cliente id_cli, c.hora, DATE_FORMAT(c.hora, '%h:%i %p') hora_format, c.fecha, cli.historia, cli.nombrec, cli.apellidoc,
        cli.telefonoc, c.avisos, c.detalles,c.envio, GROUP_CONCAT(z.nombre ORDER BY c.fecha ASC SEPARATOR ' - ') resumen, ct.color, ct.descripcion tipo_cita,
        clit.descripcion tipo_cli, clit.color color_cli, pgt.descripcion tipo_pag, pgt.color color_pgt, pgt.codigo id_color_pgt, c.conf_ds, c.conf_ss, c.id_asistencia,
        (select group_concat(users.nombre) from conf_diarias inner join users on users.id=conf_diarias.id_teleop where conf_diarias.check_asig=1 and conf_diarias.id_cita=c.id group by conf_diarias.id_teleop limit 1) asignado_a
        FROM citas c  LEFT JOIN cliente cli on cli.id=c.id_cliente left join tabla_deta_adelgaza t on t.id_cita=c.id left join detalles_adelgaza z on z.id=t.id_deta_adelgaza inner join tratamientos on tratamientos.id=c.id_tratamiento, cit_tipo  ct, cli_tipo clit, pag_tipo pgt
        WHERE c.cod_cit_tipo = ct.codigo
        AND c.cod_cli_tipo = clit.codigo
        AND c.cod_pago_tipo = pgt.codigo
        AND c.fecha = '$fecha'
        AND tratamientos.id=2
        $whereSede
        $whereAsistencia
        $whereTipoCita
        GROUP BY c.id 
        ORDER BY c.hora ASC") ) {            
            if( $result_adelgaza->num_rows > 0 ) {        
                while($row = $result_adelgaza->fetch_object()) {        
                    //se agrega el objeto a un array de objetos         
                    $newRow= array();        
                    $newRow["id_ci"] = $row->id_ci;        
                    $newRow["id_cli"] = $row->id_cli;        
                    $newRow["hora"] = $row->hora;        
                    $newRow["hora_format"] = $row->hora_format;        
                    $newRow["fecha"] = $row->fecha;        
                    $newRow["historia"] =mb_detect_encoding($row->historia, 'UTF-8',true)?$row->historia:utf8_encode($row->historia);     
                    $newRow["nombrec"] =  mb_detect_encoding($row->nombrec, 'UTF-8',true)?$row->nombrec:utf8_encode($row->nombrec);;        
                    $newRow["apellidoc"] = mb_detect_encoding($row->apellidoc, 'UTF-8',true)?$row->apellidoc:utf8_encode($row->apellidoc);        
                    $newRow["telefonoc"] = $row->telefonoc;        
                    $newRow["avisos"] = mb_detect_encoding($row->avisos, 'UTF-8',true)?$row->avisos:utf8_encode($row->avisos);        
                    $newRow["detalles"] =  mb_detect_encoding($row->detalles, 'UTF-8',true)?$row->detalles:utf8_encode($row->detalles);        
                    $newRow["color"] = $row->color;  
                    $newRow["envio"] = $row->envio;      
                    $newRow["resumen"] =  mb_detect_encoding($row->resumen, 'UTF-8',true)?$row->resumen:utf8_encode($row->resumen);        
                    $newRow["tipo_cita"] = $row->tipo_cita;        
                    $newRow["tipo_cli"] = $row->tipo_cli;        
                    $newRow["color_cli"] = $row->color_cli;        
                    $newRow["tipo_pag"] = $row->tipo_pag;        
                    $newRow["color_pgt"] = $row->color_pgt; 
                    $newRow["id_color_pgt"] = $row->id_color_pgt;        
                    $newRow["conf_ds"] = $row->conf_ds;        
                    $newRow["conf_ss"] = $row->conf_ss;        
                    $newRow["id_asistencia"] = $row->id_asistencia;
                    $newRow['tto_url']=$row->tto_url;
                    $newRow['tto_id']=$row->tto_id;
                    $newRow["asignado_a"] =  mb_detect_encoding($row->asignado_a, 'UTF-8',true)?$row->asignado_a:utf8_encode($row->asignado_a);       

                    $jsondata["data"]["citas"][]=$newRow;
                };
            } 
        }else{
            $jsondata["success"] = false;
            $jsondata["data"] = array(
                'msg' => $database->error
            );
        }
    }elseif($id_tto==''){
        //consulta todo
        if ( $result_depilacion = $database->query("SELECT tratamientos.id tto_id,tratamientos.nombre tto_nombre, tratamientos.url tto_url,c.id id_ci, c.id_cliente id_cli, c.hora, DATE_FORMAT(c.hora, '%h:%i %p') hora_format, c.fecha, cli.historia, cli.nombrec, cli.apellidoc,
        cli.telefonoc, c.avisos, c.detalles,c.envio, GROUP_CONCAT(z.partes ORDER BY c.fecha ASC SEPARATOR ' - ') resumen, ct.color, ct.descripcion tipo_cita,
        clit.descripcion tipo_cli, clit.color color_cli, pgt.descripcion tipo_pag, pgt.color color_pgt, pgt.codigo id_color_pgt, c.conf_ds, c.conf_ss, c.id_asistencia,
        (select group_concat(users.nombre) from conf_diarias inner join users on users.id=conf_diarias.id_teleop where conf_diarias.check_asig=1 and conf_diarias.id_cita=c.id group by conf_diarias.id_teleop limit 1) asignado_a        
        FROM citas c  LEFT JOIN cliente cli on cli.id=c.id_cliente left join tratamientos on tratamientos.id=c.id_tratamiento, tabla_zonas t, zonas z, cit_tipo  ct, cli_tipo clit, pag_tipo pgt
        WHERE c.id = t.id_cita                           
        AND c.cod_cit_tipo = ct.codigo
        AND c.cod_cli_tipo = clit.codigo
        AND c.cod_pago_tipo = pgt.codigo
        AND t.id_zona = z.id
        AND c.fecha = '$fecha'
        AND tratamientos.id=1
        $whereSede
        $whereAsistencia
        $whereTipoCita
        GROUP BY c.id 
        ORDER BY c.hora ASC") ) {            
            if( $result_depilacion->num_rows > 0 ) {        
                while($row = $result_depilacion->fetch_object()) {      
                    $newRow= array();        
                    $newRow["id_ci"] = $row->id_ci;        
                    $newRow["id_cli"] = $row->id_cli;        
                    $newRow["hora"] = $row->hora;        
                    $newRow["hora_format"] = $row->hora_format;        
                    $newRow["fecha"] = $row->fecha;        
                    $newRow["historia"] =mb_detect_encoding($row->historia, 'UTF-8',true)?$row->historia:utf8_encode($row->historia);      
                    $newRow["nombrec"] =  mb_detect_encoding($row->nombrec, 'UTF-8',true)?$row->nombrec:utf8_encode($row->nombrec);;        
                    $newRow["apellidoc"] = mb_detect_encoding($row->apellidoc, 'UTF-8',true)?$row->apellidoc:utf8_encode($row->apellidoc);        
                    $newRow["telefonoc"] = $row->telefonoc;        
                    $newRow["avisos"] = mb_detect_encoding($row->avisos, 'UTF-8',true)?$row->avisos:utf8_encode($row->avisos);        
                    $newRow["detalles"] =  mb_detect_encoding($row->detalles, 'UTF-8',true)?$row->detalles:utf8_encode($row->detalles);        
                    $newRow["color"] = $row->color; 
                    $newRow["envio"] = $row->envio;        
                    $newRow["resumen"] =  mb_detect_encoding($row->resumen, 'UTF-8',true)?$row->resumen:utf8_encode($row->resumen);        
                    $newRow["tipo_cita"] = $row->tipo_cita;        
                    $newRow["tipo_cli"] = $row->tipo_cli;        
                    $newRow["color_cli"] = $row->color_cli;        
                    $newRow["tipo_pag"] = $row->tipo_pag;        
                    $newRow["color_pgt"] = $row->color_pgt; 
                    $newRow["id_color_pgt"] = $row->id_color_pgt;         
                    $newRow["conf_ds"] = $row->conf_ds;        
                    $newRow["conf_ss"] = $row->conf_ss;        
                    $newRow["id_asistencia"] = $row->id_asistencia;
                    $newRow['tto_url']=$row->tto_url;
                    $newRow['tto_id']=$row->tto_id;
                    $newRow["asignado_a"] =  mb_detect_encoding($row->asignado_a, 'UTF-8',true)?$row->asignado_a:utf8_encode($row->asignado_a);        

                    $jsondata["data"]["citas"][]=$newRow;
                };
            } 
        };
        if ( $result_adelgaza = $database->query("SELECT tratamientos.id tto_id,tratamientos.nombre tto_nombre, tratamientos.url tto_url, c.id id_ci, c.id_cliente id_cli, c.hora, DATE_FORMAT(c.hora, '%h:%i %p') hora_format, c.fecha, cli.historia, cli.nombrec, cli.apellidoc,
        cli.telefonoc, c.avisos, c.detalles,c.envio, GROUP_CONCAT(z.nombre ORDER BY c.fecha ASC SEPARATOR ' - ') resumen, ct.color, ct.descripcion tipo_cita,
        clit.descripcion tipo_cli, clit.color color_cli, pgt.descripcion tipo_pag, pgt.color color_pgt, pgt.codigo id_color_pgt, c.conf_ds, c.conf_ss, c.id_asistencia,
        (select group_concat(users.nombre) from conf_diarias inner join users on users.id=conf_diarias.id_teleop where conf_diarias.check_asig=1 and conf_diarias.id_cita=c.id group by conf_diarias.id_teleop limit 1) asignado_a 
        FROM citas c  LEFT JOIN cliente cli on cli.id=c.id_cliente left join tabla_deta_adelgaza t on t.id_cita=c.id left join detalles_adelgaza z on z.id=t.id_deta_adelgaza inner join tratamientos on tratamientos.id=c.id_tratamiento, cit_tipo  ct, cli_tipo clit, pag_tipo pgt
        WHERE c.cod_cit_tipo = ct.codigo
        AND c.cod_cli_tipo = clit.codigo
        AND c.cod_pago_tipo = pgt.codigo
        AND c.fecha = '$fecha'
        AND tratamientos.id=2
        $whereSede
        $whereAsistencia
        $whereTipoCita
        GROUP BY c.id 
        ORDER BY c.hora ASC") ) {            
            if( $result_adelgaza->num_rows > 0 ) {        
                while($row = $result_adelgaza->fetch_object()) {        
                    //se agrega el objeto a un array de objetos         
                    $newRow= array();        
                    $newRow["id_ci"] = $row->id_ci;        
                    $newRow["id_cli"] = $row->id_cli;        
                    $newRow["hora"] = $row->hora;        
                    $newRow["hora_format"] = $row->hora_format;        
                    $newRow["fecha"] = $row->fecha;        
                    $newRow["historia"] = mb_detect_encoding($row->historia, 'UTF-8',true)?$row->historia:utf8_encode($row->historia);           
                    $newRow["nombrec"] =  mb_detect_encoding($row->nombrec, 'UTF-8',true)?$row->nombrec:utf8_encode($row->nombrec);;        
                    $newRow["apellidoc"] = mb_detect_encoding($row->apellidoc, 'UTF-8',true)?$row->apellidoc:utf8_encode($row->apellidoc);        
                    $newRow["telefonoc"] = $row->telefonoc;        
                    $newRow["avisos"] = mb_detect_encoding($row->avisos, 'UTF-8',true)?$row->avisos:utf8_encode($row->avisos);        
                    $newRow["detalles"] =  mb_detect_encoding($row->detalles, 'UTF-8',true)?$row->detalles:utf8_encode($row->detalles);        
                    $newRow["color"] = $row->color; 
                    $newRow["envio"] = $row->envio;       
                    $newRow["resumen"] =  mb_detect_encoding($row->resumen, 'UTF-8',true)?$row->resumen:utf8_encode($row->resumen);        
                    $newRow["tipo_cita"] = $row->tipo_cita;        
                    $newRow["tipo_cli"] = $row->tipo_cli;        
                    $newRow["color_cli"] = $row->color_cli;        
                    $newRow["tipo_pag"] = $row->tipo_pag;        
                    $newRow["color_pgt"] = $row->color_pgt;
                    $newRow["id_color_pgt"] = $row->id_color_pgt;         
                    $newRow["conf_ds"] = $row->conf_ds;        
                    $newRow["conf_ss"] = $row->conf_ss;        
                    $newRow["id_asistencia"] = $row->id_asistencia;
                    $newRow['tto_url']=$row->tto_url;
                    $newRow['tto_id']=$row->tto_id;
                    $newRow["asignado_a"] =  mb_detect_encoding($row->asignado_a, 'UTF-8',true)?$row->asignado_a:utf8_encode($row->asignado_a);        
                    $jsondata["data"]["citas"][]=$newRow;
                };
            };
        };
    }

    echo json_encode($jsondata);	

    $database->close();
    exit();

?>

