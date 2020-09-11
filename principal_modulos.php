<?php 
session_start();
if($_SESSION['id'] == ""){
header("Location: sesion.php");  
}

if(!isset($_SESSION['id'])){ 
header('Location: index.php');  
exit(); 
} 
if($_SESSION["tiene_permiso"] == false or $_SESSION['tipo']==""){ 
header("Location: cerrar.php"); 
}   
else {
include ('conexionbd.php');    
$usuario = $_SESSION['usuario'];
$tipo_user=$_SESSION['tipo'];
$userquery='SELECT * FROM users , tipodusers WHERE users.usuario="'.$_SESSION['usuario'].'"  AND
users.tipo = tipodusers.tipo';  
//echo $userquery;
$rowuser=mysqli_query($conexion,$userquery);  
$reguser=mysqli_fetch_assoc($rowuser);
$usernomb=$reguser['nombre'];   
$usercargo=$reguser['cargo'];     
$id = $_SESSION['id'];		
//BOTON DE REGRESAR A INDEX SOLO VISIBLE PARA SISTEMAS 
if($tipo_user==8){
echo'<a href="index.php" class="fixed-bottom posicion_bot_atras"><i class="fas fa-arrow-circle-left fa-3x bot_sesion ml-3" title="Regresar" ></i></a>';   
}else{
echo'<i class="fas fa-arrow-circle-left fa-3x bot_sesion ml-3" title="Regresar" ></i>';
}
?>

<!DOCTYPE html>
<html lang="es" class="html_fondo">
    <head>
        <meta http-equiv="Expires" content="0">
        
        <meta http-equiv="Last-Modified" content="0">
        
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        
        <meta http-equiv="Pragma" content="no-cache">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="UTF-8">
        <meta name=”keywords” content=”depilacion,zonas”>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>DepilZONE</title>
        <link rel="icon" type="image/png" href="imagenes/intranet-04.png" />
        <!--CSS DE LA PAGINA PRINCIPAL-->
        <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.css"/>           
        <link rel="stylesheet" href="todocss.css" type="text/css" media=screen>
        <link rel="stylesheet" href="lib/fontawesome/css/all.css"/> 
        <link rel="stylesheet" href="css/reloj.css" type="text/css" media=screen>
        <link rel="stylesheet" href="css/useronline.css"/>
        <!--JAVASCRIPT DE LA PAGINA PRINCIPAL-->
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/popper.min.js"></script>
        <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="lib/fontawesome/js/all.js"></script>
        <script type="text/javascript" src="js/expirarSesion.js"></script>
        <script type="text/javascript" src="js/cierre.js"></script>
        <script type="text/javascript" src="js/reloj_clock.js"></script>
        <script type="text/javascript" src="js/menu_h.js"></script>
        <script src="js/custom.js"></script>  
        <script type="text/javascript" src="js/loader.js"></script>
        <!--INCLUDE DE LA PAGINA PRINCIPAL-->
        <?php include('loader.php'); ?>
        <?php include('modulos/anuncios/modal_add_anunc.php');?>      
        <!--cerrar navegador pasado 10 minutos inactividad--> 
        
       <script type="text/javascript" > 
        //expirar();
         
           
        // if(document.referrrer !== 'https://yourweb.com/url-de-tu-login'){
        // location.href == 'error.php';
        //     }
                  // cierre de sesion por cierre de navegador
                   /* window.addEventListener("beforeunload", function (event) {
                   window.alert('entre');            
                  // Cancel the event as stated by the standard.
                  event.preventDefault();
                  // Chrome requires returnValue to be set.
                  event.returnValue = 'dfd';       

                });*/
        
        </script> 
        
  <script type="text/javascript">
//AGREGADO POR ING. JUAN PEREZ					 
/*var idleTime = 0;
$(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 300000); // 30 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 55) { // 60 minutes
        window.location.reload();
    }
}
// FIN CODIGO AGREGADO POR ING. JUAN PEREZ */
</script>   
                               
<?php
	
	// ACTUALIZACION ESTADO DE CONEXION DE USUARIOS
	include('conexionbd.php');
//	$mysqli=new mysqli("localhost","root","","depilzon_agenda");
//	$mysqli->autocommit(FALSE);
/*	$ultima_conexion="select id from users  where TIMESTAMPDIFF(MINUTE,hora_conexion,NOW()) > 55 and conexion='1'";  
	$query_ultima= mysqli_query($conexion,$ultima_conexion);
	

	
	// $mysqli->begin_transaction();
    while($z = mysqli_fetch_array($query_ultima)){
                                                
            $id_u=$z['id'];            
			
	        $actualizar_conexion="UPDATE users set conexion= '0' where id='$id_u'";  
			$result_actualizar = mysqli_query($conexion,$actualizar_conexion);


	
	// CODIGO AGREGADO POR ING. JUAN PEREZ
 //Comprobamos si esta definida la sesión 'tiempo'.
    if(isset($_SESSION['tiempo']) ) {

        //Tiempo en segundos para dar vida a la sesión.
        $inactivo = 3300;//10 min en este caso.

        //Calculamos tiempo de vida inactivo.
        $vida_session = time() - $_SESSION['tiempo'];

            //Compraración para redirigir página, si la vida de sesión sea mayor a el tiempo insertado en inactivo.
            if($vida_session >= $inactivo)
            { 
				  
			
				
               echo " <script type='text/javascript'> 
				 window.top.location.href = 'sesion.php?inactivo=error';	
				  </script>";
				exit;

            }

    }

    $_SESSION['tiempo'] = time();


		
}
	
//ACTUALIZA ULTIMA HORA DE CONEXION	
$query_hora = "UPDATE users SET hora_conexion = CURRENT_TIMESTAMP()  WHERE id ='$id'";
$result_hora = mysqli_query($conexion,$query_hora);	
// FIN CODIGO AGREGADO POR ING. JUAN PEREZ */
?>                                                                 
                                
    </head>
  
    <!--<body onload="nobackbutton();" oncontextmenu='return false' onkeydown='return false'>-->
    <body  onload="nobackbutton();">
        <header class="cabecerainicial fixed-top">
            <div class="div_imagen_header">
            <center><img class="css-image mt-4 mt-sm-0" src="imagenes/intranet-02.png"></center>
            </div>
<!---------------------------------------------------------CONECTADOS----------------------------------------------------------------------------->            
            <div class="css-display-topright mr-1">
            <center>
            <div class="d-flex justify-content-center h-100">
			<div class="image_outer_container">
				<div class="green_icon"></div>
				<div class="image_inner_container">
					<img src="imagenes/userv.png">
				</div>
			</div>
		    </div>
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <?php echo $usuario.' - '.$usercargo?> 
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="cerrar.php"><i class="fa fa-reply"></i>&nbsp;&nbsp;  Cerrar Sesión</a>
              </div>
             </div>               
            </center>
            </div>           
<!----------------------------------------------- Barra de navegacion ------------------------------------------------------------>
             <nav class="navbar navbar-expand-md navbar-light" style="background-color:#607d8b">                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>
             <div class="collapse navbar-collapse menu_bar2 justify-content-center" id="navbarSupportedContent">     
                  <?php
 //---------------------MENU VERTICAL DINAMICO MULTINIVEL------------------------------------------------------------------------------------//  
               $hasil='SELECT `description` AS `description`,'
                . '`id` AS `menu_item_id`,'
                . '`parent_id` AS `menu_parent_id`,'
                . '`title` AS `menu_item_name`,'
                . '`status` AS `status`,'            
                . '`url` AS `url`,'
                . '`menu_order`, `target`,`icon`,`icon_interno`'
                . 'FROM menu_h LEFT JOIN menu_users ON menu_h.id = menu_users.id_menu '
                . 'WHERE((id_tipouser='.$tipo_user.' AND tipomenu="h")) ORDER BY menu_order';
               // echo $hasil;
    
               $query= mysqli_query($conexion,$hasil);
     

                $refs = array();
                $list = array();
                while($data =$query->fetch_assoc())
                {
                    $thisref = &$refs[ $data['menu_item_id'] ];
                    $thisref['menu_parent_id'] = $data['menu_parent_id'];
                    $thisref['menu_item_name'] = $data['menu_item_name'];
                    $thisref['url'] = $data['url'];
                    $thisref['target'] = $data['target'];
                    $thisref['icon'] = $data['icon'];
                    $thisref['description'] = $data['description'];
                    $thisref['status'] = $data['status'];
                    $thisref['icon_interno'] = $data['icon_interno'];
                    //$thisref['autonomo'] = $data['autonomo'];
                    if ($data['menu_parent_id'] == 0)
                    {
                        $list[ $data['menu_item_id'] ] = &$thisref;
                    }
                    else
                    {
                        $refs[ $data['menu_parent_id'] ]['children'][ $data['menu_item_id'] ] = &$thisref;
                    }
                }
    
    
                function create_list( $arr ,$urutan)

                {

                if($urutan==0){
                     $html = "<ul class='navbar-nav'>";
                }else
                {
                    // $html = "\n<ul class='collapse list-unstyled' id='homeSubmenu'>\n";
                }
                foreach ($arr as $key=>$v)
                {
                    if (array_key_exists('children', $v))
                    {
                        $html .= "<li class='nav-item dropdown menu_style'>";
                        $html .= '<a class="nav-link dropdown-toggle px-md-2 px-lg-4 px-xl" target="'.$v['target'].'" href="#" title="'.$v['description'].'" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" >
                                        <i class="'.$v['icon'].'"></i>
                                        <strong class="d-none d-md-inline">&nbsp;&nbsp;'.$v['menu_item_name'].'</strong>
                                </a>';
                     $html .='<div class="dropdown-menu">';    
                     foreach ($v['children'] as $sub)
                      {    
                         //print_r($v['children']);
                       //  print_r($sub['menu_item_name']);
                       $html .='
                                        <a  class="dropdown-item togglee" href="'.$sub['url'].'" target="'.$sub['target'].'" title="'.$sub['description'].'" style="color:#444242!important;" onclick="carga_show_hide()">
                                        <span class="fa-layers fa-lg">
                                        <i class="'.$sub['icon'].'""></i>
                                        '.$sub['icon_interno'].'
                                        </span>
                                        <strong>&nbsp;&nbsp;'.$sub['menu_item_name'].'</strong></a>
                                  ';   
                     }
                    $html .="</div>";    

                       //$html .= create_list($v['children'],1);
                        $html .= "</li>";
                    }else{
                        if ($v['menu_item_name']=='Inicio'){
                            $html .='<li class="nav-item togglee menu_style active_menu">
                             <a class="nav-link px-md-2 px-lg-4 px-xl" target="'.$v['target'].'" href="'.$v['url'].'" title="'.$v['description'].'"><i class="'.$v['icon'].'"></i><strong class="d-none d-md-inline">&nbsp;&nbsp;'.$v['menu_item_name'].'</strong></a>
                             </li>';  
                           // $html .= '<li><a target= "principal" title="'.$v['description'].'" href="'.$v['url'].'?pro='.$v['status'].'">';
                            }

                            else{
                            $html .= '<li class="nav-item togglee menu_style">
                            <a  class="nav-link px-md-2 px-lg-4 px-xl" href="'.$v['url'].'" target="'.$v['target'].'" onclick="carga_show_hide()" title="'.$v['description'].'" >';                        
                          /*  }*/
                            if($urutan==0)
                            {
                                $html .=    '<i class="'.$v['icon'].'"></i>';
                            }
                            if($urutan==1)
                            {
                               // $html .=    '<i class="fa fa-angle-double-right"></i>';/*hijos*/
                            }
                            $html .="<strong class='d-none d-md-inline'>&nbsp;&nbsp;".$v['menu_item_name']."</strong></a></li>\n";
                    }
                    }
                }
                $html .= "</ul>";
                return $html;
            }
            echo create_list( $list,0 );
//--------------------------------------------------------FIN DE MENU MULTINIVEL-------------------------------------------------//
             ?>
          
            </div>
             </nav>
             
             <script>
                $('.navbar-collapse .togglee').click(function() {
                    $(".collapse").collapse('hide');
                })
            </script>
        </header>

        <!-------------------------------------------------------- contenido --------------------------------------------------->
        <div class="container-fluid posic_coord">
            <iframe class="tam_iframe2" id="iframe" name="espacio"  src="modulos/anuncios_inicio/anuncioslide.php" frameborder="0"></iframe>
		</div> 
      
    </body>
</html>
<?php
   }
?>   
<script>   
  $(document).ready(function() { $("div#clock").simpleClock(-5); }); (function ($) { $.fn.simpleClock = function ( utc_offset ) { var language = "es"; switch (language) { case "de": var weekdays = ["So.", "Mo.", "Di.", "Mi.", "Do.", "Fr.", "Sa."]; var months = ["Jan.", "Feb.", "Mär.", "Apr.", "Mai", "Juni", "Juli", "Aug.", "Sep.", "Okt.", "Nov.", "Dez."]; break; case "es": var weekdays = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"]; var months = ["Ene", "Feb", "Mar", "Abr", "Mayo", "Jun", "Jul", "Ago", "Sept", "Oct", "Nov", "Dic"]; break; case "fr": var weekdays = ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"]; var months = ["Jan", "Fév", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"]; break; default: var weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]; var months = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"]; break; } var clock = this; function getTime() { var date = new Date(); var nowUTC = date.getTime() + date.getTimezoneOffset()*60*1000; date.setTime( nowUTC + (utc_offset*60*60*1000) ); var hour = date.getHours(); if ( language == "en" ) { suffix = (hour >= 12)? 'p.m.' : 'a.m.'; hour = (hour > 12)? hour -12 : hour; hour = (hour == '00')? 12 : hour; } return { day: weekdays[date.getDay()], date: date.getDate(), month: months[date.getMonth()], year: date.getFullYear(), hour: appendZero(hour), minute: appendZero(date.getMinutes()), second: appendZero(date.getSeconds()) }; } function appendZero(num) { if (num < 10) { return "0" + num; } return num; } function refreshTime(clock_id) { var now = getTime(); clock = $.find('#'+clock_id); $(clock).find('.date').html(now.day + ', ' + now.date + '. ' + now.month + ' ' + now.year); $(clock).find('.time').html("<span class='hour'>" + now.hour + "</span>:<span class='minute'>" + now.minute + "</span>:<span class='second'>" + now.second + "</span>"); if ( typeof(suffix) != "undefined") { $(clock).find('.time').append('<strong>'+ suffix +'</strong>'); } } var clock_id = $(this).attr('id'); refreshTime(clock_id); setInterval( function() { refreshTime(clock_id) }, 1000); }; })(jQuery);

</script>
<script>
    
    var resReqConfCon=false;
    var modalReqConfCon=document.createElement('div');
    modalReqConfCon.setAttribute('style','position:absolute;width:100%;height:100%;z-index:1031;display:none;top:0;left:0;justify-content:center;align-items:center;flex-direction:column')
    var contentModal=document.createElement('div');
    contentModal.setAttribute('style','border-radius:10px;display:flex;flex-direction:column;justify-content:center;align-items:center;background-color:white;padding:10px')
    var titleModal=document.createElement('div');
    titleModal.innerText='SE REQUIERE CONFIRMACIÓN DE USUARIO CONECTADO';
    titleModal.setAttribute('style','font-weight:bold')
    var btnModal=document.createElement('button');
    btnModal.innerText="PRESENTE";
    btnModal.setAttribute('style','font-size:20px;background-color:dodgerblue;color:white')
    btnModal.addEventListener('click',()=>{
        btnModal.disabled=true;
        var ajax_url='resConfCon.php';
        var ajax_request= new XMLHttpRequest();             
        ajax_request.open('POST',ajax_url);
        ajax_request.send();
        ajax_request.onreadystatechange = function(){
            if(ajax_request.readyState==4){               
                var response = JSON.parse(ajax_request.responseText);  
                if(response.success){
                    console.log('confirmacion correcta')
                    modalReqConfCon.style.display='none';
                    btnModal.disabled=false;
                }else{      
                    console.log(response.data.msg)                
                };
            }; 
        } 
    })
    contentModal.appendChild(titleModal);
    contentModal.appendChild(btnModal);
    modalReqConfCon.appendChild(contentModal);
    document.getElementsByTagName('body')[0].appendChild(modalReqConfCon);   
    setInterval(() => {
        var ajax_url='reqConfCon.php';
        var ajax_request= new XMLHttpRequest();             
        ajax_request.open('POST',ajax_url);
        ajax_request.send();
        ajax_request.onreadystatechange = function(){
            if(ajax_request.readyState==4){               
                var response = JSON.parse(ajax_request.responseText);  
                if(response.success){
                    resReqConfCon=response.data.model.req_conf_con==0?false:true;
                    if(resReqConfCon){
                        modalReqConfCon.style.display='flex';
                        modalReqConfCon.style.backgroundColor='rgba(0,0,0,0.6)';

                    }else{
                        modalReqConfCon.style.display='none';
                    }
                }else{      
                    console.log(response.data.msg)                
                };
            }; 
        }        
       
    }, 5000);
</script>


