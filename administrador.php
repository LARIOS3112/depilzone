<?php

session_start();
if ($_SESSION['id'] == ""){
    header("Location: sesion.php");    
}
if ($_SESSION["tiene_permiso"] == false or $_SESSION['tipo'] != 8 ) { 
        header("Location: cerrar.php"); 
}   
else {
$usuario= $_SESSION['usuario'];
include ('conexionbd.php');
$userquery='SELECT * FROM users , tipodusers WHERE users.usuario="'.$_SESSION['usuario'].'"';  
    echo $userquery;
$rowuser=mysqli_query($conexion,$userquery);  
$reguser=mysqli_fetch_assoc($rowuser);

$usernomb=$reguser['nombre'];   
$usercargo=$reguser['cargo'];     
//echo $usercargo;
    
date_default_timezone_get('');
ini_set('date.timezone', 'america/lima');
$fecha   = date("d/m/Y");
$tiempo  = date("H:i:s");

?>

<!DOCTYPE html>
<html lang="es" class="html_fondo">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="UTF-8">
        <meta name=”keywords” content=”depilacion,zonas”>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>DepilZONE</title>
        <link rel="icon" type="image/png" href="imagenes/intranet-04.png" />     
        <!--Para implementar css en este archivo-->
        <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.css"/>
        <link rel="stylesheet" href="todocss.css" type="text/css" media=screen> 
        <link rel="stylesheet" href="lib/fontawesome/css/all.css"/>
        <link rel="stylesheet" href="css/style.css"/> 
        <link rel="stylesheet" href="css/useronline.css"/> 
        <!--<script src="refrescar.js"></script>-->      
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/popper.min.js"></script>
        <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="lib/fontawesome/js/all.js"></script>
        <script src="js/custom.js"></script>
        <?php include('loader.php'); ?>
        <script type="text/javascript" src="js/loader.js"></script>
        <script type="text/javascript" src="js/expirarSesion.js"></script> 
        <!--cerrar navegador pasado 10 minutos inactividad-->     
        <script type="text/javascript" > 
        expirar();
        </script>      
    </head>
    
    <body onload="nobackbutton();" oncontextmenu='return false' onkeydown='return false'>
        <header class="cabecerainicial fixed-top">
            <div class="div_imagen_header">
                <center><img class="css-image mt-4 mt-sm-0" src="imagenes/intranet-02.png"></center>
            </div>          
            <div class="css-display-topleft">
                <?php include ('reloj.html'); ?>
            </div>           
            <div class="css-display-topright mr-5">
            <center>
            <div class="d-flex justify-content-center h-100">
			<div class="image_outer_container">
				<div class="green_icon"></div>
				<div class="image_inner_container">
					<img src="imagenes/user.png">
				</div>
			</div>
		</div>              
             <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php echo $usuario.' - '.$usercargo?>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Perfil</a>
                <a class="dropdown-item" href="cerrar.php">Cerrar Sesión</a>
              </div>
             </div>
                    <p class="font-weight-bold mb-1" style="color:#444242" data-toggle="dropdown"></p> 
                   <div class="dropdown-menu dropdown-menu-right feedback" name="Uconectados" id="feedback"></div>
                    <i class="fas fa-sign-out-alt fa-2x bot_sesion" onclick ="location='cerrar.php'" title="Cerrar Sesión"></i>
             </center>
         </div>
            
            
            <nav class="navbar navbar-expand navbar-light" id="menu_sist" style="background-color:#607d8b">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>


                
                <div class="collapse navbar-collapse menu_bar menu_admin" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto"></ul>
                     
                    <form class="form-inline my-2 my-lg-0">

                        
                           <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count" style="border-radius:10px;"></span> <span class="li_bubble" style="font-size:30px;" style="border-radius:30px;"></span></a>
                                <ul class="dropdown-menu" id="mensaje"></ul>
                             </li>
                            </ul>


                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-user-friends"></i> Conectados</button>
                            <div class="dropdown-menu dropdown-menu-right feedback" name="Uconectados" id="feedback"></div>
                        </div>
                    </form>

                </div>

                <!-- ------------------------------------menu coordinador---------------------------------------------- -->
                <div class="collapse menu_bar2 menu_coord d-none" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        
                        <!--<li class="nav-item togglee menu_style">
                            <a class="nav-link px-md-2 px-lg-4 px-xl" href="modulos/asig_diarias/asig_diarias.php" target="espacio"><i class="fas fa-user-check fa-lg"></i><strong> Asignaciones Diarias</strong></a>
                        </li>
                        
                        <li class="nav-item togglee menu_style">
                            <a class="nav-link px-md-2 px-lg-4 px-xl" href="asig_prefer.php" target="espacio"><i class="fas fa-clipboard-list fa-lg"></i><strong> Preferente</strong></a>
                        </li>-->
                        
                        <li class="nav-item dropdown menu_style">
                            <a class="nav-link dropdown-toggle px-md-2 px-lg-4 px-xl" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown"><i class="fas fa-calendar-alt fa-lg"></i><strong> Agenda</strong></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item togglee" href="agenda.php" target="espacio" style="color:#444242!important;">
                                    <span class="fa-layers fa-lg">
                                        <i class="fas fa-calendar-alt"></i>
                                        <i class="fas fa-search" data-fa-transform="shrink-8 down-2.2 right-0.9" style="color:#4caae1"></i>
                                    </span>
                                    <strong> Buscar Citas</strong>
                                </a>
                                
                                <a class="dropdown-item togglee" href="nuevaficha1.php" target="espacio" style="color:#444242!important;">
                                    <span class="fa-layers fa-lg">
                                        <i class="fas fa-notes-medical"></i>
                                    </span>
                                    <strong> Agregar Cliente</strong>
                                </a>
                                
                                <a class="dropdown-item togglee" href="modulos/buscar_hist/buscar_hist.php" target="espacio" style="color:#444242!important;">
                                    <span class="fa-layers fa-lg">
                                        <i class="fas fa-clipboard-list"></i>
                                    </span>
                                    <strong> Historias Clínicas</strong>
                                </a>
                                
                            </div>
                        </li>
                        
                        <li class="nav-item togglee menu_style">
                            <a class="nav-link px-md-2 px-lg-4 px-xl" href="asig_estadist.php" target="espacio"><i class="fas fa-chart-bar fa-lg"></i><strong> Estadísticas</strong></a>
                        </li>
                    </ul>
                    
                    <form class="form-inline my-2 my-lg-0">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-user-friends"></i> Conectados</button>
                            <div class="dropdown-menu dropdown-menu-right feedback" name="Uconectados" id="feedback"></div>
                        </div>
                    </form>
                </div>
         
                
                            
                
                <!-- ------------------------------------menu marketing---------------------------------------------- -->
                <div class="collapse menu_bar2 menu_mark d-none" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item togglee menu_style">
                            <a class="nav-link px-md-2 px-lg-4 px-xl" href="modulos/anuncios/anuncios.php" target="espacio"><i class="fas fa-bullhorn fa-lg"></i><strong> Anuncios</strong></a>
                        </li>
                        
                        <li class="nav-item togglee menu_style">
                            <a class="nav-link px-md-2 px-lg-4 px-xl" href="modulos/preferen_listas/preferen_listas.php" target="espacio"><i class="fas fa-list-alt fa-lg"></i><strong> Listas de Preferentes</strong></a>
                        </li>
                        
                        
                        <li class="nav-item dropdown menu_style">
                            <a class="nav-link dropdown-toggle px-md-2 px-lg-4 px-xl" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown"><i class="fas fa-calendar-alt fa-lg"></i><strong> Agenda</strong></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item togglee" href="modulos/buscar_hist/buscar_hist.php" target="espacio" style="color:#444242!important;" onclick="carga_show_hide()">
                                    <span class="fa-layers fa-lg">
                                        <i class="fas fa-clipboard-list"></i>
                                    </span>
                                    <strong> Historias Clínicas</strong>
                                </a>
                            </div>
                        </li>
                        
                        <li class="nav-item togglee menu_style">
                            <a class="nav-link px-md-2 px-lg-4 px-xl" href="modulos/estadist_mark/estadisticas.php" target="espacio"><i class="fas fa-chart-bar fa-lg"></i><strong> Estadisticas</strong></a>
                        </li>
                     
                    </ul>
                    
                    <form class="form-inline my-2 my-lg-0">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-user-friends"></i> Conectados</button>
                            <div class="dropdown-menu dropdown-menu-right feedback" name="Uconectados" id="feedback"></div>
                        </div>
                    </form>
                </div>
                
                
                
                
                <!-- ------------------------------------menu teleoperadora---------------------------------------------- -->
                <div class="collapse menu_bar2 menu_tel d-noner" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item togglee menu_style">
                            <a class="nav-link px-md-2 px-lg-4 px-xl" href="modulos/preferen_dia/preferen_dia.php" target="espacio"><i class="fas fa-list-alt fa-lg"></i><strong> Preferentes del Día</strong></a>
                        </li>
                        
                       <!-- <li class="nav-item togglee menu_style">
                            <a class="nav-link px-md-2 px-lg-4 px-xl" href="asignaciones.php" target="espacio"><i class="fas fa-list-alt fa-lg"></i><strong> Asignaciones</strong></a>
                        </li>-->

                        <li class="nav-item dropdown menu_style">
                            <a class="nav-link dropdown-toggle px-md-2 px-lg-4 px-xl" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown"><i class="fas fa-calendar-alt fa-lg"></i><strong> Agenda</strong></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item togglee" href="agenda.php" target="espacio" style="color:#444242!important;">
                                    <span class="fa-layers fa-lg">
                                        <i class="fas fa-calendar-alt"></i>
                                        <i class="fas fa-search" data-fa-transform="shrink-8 down-2.2 right-0.9" style="color:#4caae1"></i>
                                    </span>
                                    <strong> Buscar Citas</strong>
                                </a>
                                
                                <a class="dropdown-item togglee" href="nuevaficha1.php" target="espacio" style="color:#444242!important;">
                                    <span class="fa-layers fa-lg">
                                        <i class="fas fa-notes-medical"></i>
                                    </span>
                                    <strong> Agregar Cliente</strong>
                                </a>
                                
                                <a class="dropdown-item togglee" href="modulos/buscar_hist/buscar_hist.php" target="espacio" style="color:#444242!important;">
                                    <span class="fa-layers fa-lg">
                                        <i class="fas fa-clipboard-list"></i>
                                    </span>
                                    <strong> Historias Clínicas</strong>
                                </a>
                                
                            </div>
                        </li>
                    </ul>
                    
                    <form class="form-inline my-2 my-lg-0">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fas fa-user-friends"></i> Conectados</button>
                            <div class="dropdown-menu dropdown-menu-right feedback" name="Uconectados" id="feedback"></div>
                        </div>
                    </form>
                </div>
                
                
            </nav>
        </header>
        
        
        
        
        <div class="wrapper">
            
            <!-- Sidebar -->
            <nav id="sidebar" class="posic_admin_inicio">
               <!-- <div class="sidebar-header"><h3></h3></div>-->
                <ul class="list-unstyled components">
                    <!--<p>Dummy Heading</p>-->
                    
                    <li class="menu2 active">
                        <a href="administrador.php"><i class="fas fa-home"></i><strong class="d-none d-md-inline"> Inicio</strong></a>
                       <!-- <a href="slides.html" target="espacio"><i class="fas fa-home"></i><strong class="d-none d-sm-inline"> Inicio</strong></a>-->
                    </li>
                    
                    <li class="menu2">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-users"></i><strong class="d-none d-md-inline"> Usuarios</strong></a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li>
                                <a href="sistema.php" onclick="cargando()"><i class="fas fa-user-plus"></i><strong class="d-none d-md-inline"> Crear Usuario</strong></a>
                            </li>
                            <li>
                                <a href="usuarios.php" onclick="cargando()"><i class="fas fa-user-edit"></i><strong class="d-none d-md-inline"> Lista de Usuarios</strong></a>
                            </li>
                        </ul>
                    </li>
                    
                     <li class="menu2">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-users"></i><strong class="d-none d-md-inline"> Usuarios</strong></a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li>
                                <a href="sistema.php" onclick="cargando()"><i class="fas fa-user-plus"></i><strong class="d-none d-md-inline"> Crear Usuario</strong></a>
                            </li>
                            <li>
                                <a href="usuarios.php" onclick="cargando()"><i class="fas fa-user-edit"></i><strong class="d-none d-md-inline"> Lista de Usuarios</strong></a>
                            </li>
                        </ul>
                    </li>
                    
                    
                    <li class="menu2">
                        <a href="modulos/anuncios_inicio/anuncioslide.php" target="espacio" id="btn_coord" onclick="carga_show_hide()"><i class="fas fa-user-cog"></i><strong class="d-none d-md-inline"> Coordinador</strong></a>
                    </li>
                    
                    
                    <li class="menu2">
                        <a href="modulos/anuncios_inicio/anuncioslide.php" target="espacio" id="btn_mark"><i class="fas fa-bullhorn"></i><strong class="d-none d-md-inline"> Marketing</strong></a>
                    </li>
                    
                    <li class="menu2">
                        <a href="modulos/anuncios_inicio/anuncioslide.php" target="espacio" id="btn_tel"><i class="fas fa-phone"></i><strong class="d-none d-md-inline"> Teleoperadoras</strong></a>
                    </li>
                    
                    <!--<li class="menu2">
                        <a href="#"><i class="fas fa-cog"></i><strong class="d-none d-md-inline"> Configuración</strong></a>
                    </li>-->
                </ul>
            </nav>
            
            
            
            <!-- Page Content -->
            <div class="container-fluid posic_admin_inicio content">
                <!-------------------------------------------------------- division derecha --------------------------------------------------->
                <div class="row mt-2">
                    <iframe class="tam_iframe" id="iframe" name="espacio" src="modulos/anuncios_inicio/anuncioslide.php" frameborder="0" allowfullscreen></iframe>
                    
                    <!--<div class="col-12 col-sm-12 col-md-11 col-lg-11 col-xl-10">
                        <div><iframe class="tam_iframe" name="espacio" src="anuncioslide.php" frameborder="0" allowfullscreen></iframe></div>
                    </div>-->

                </div>
            </div>
        </div>
    </body> 
</html>
<?php 
}
?>

<script type="text/javascript">
    $('.collapse .togglee').click(function() {
        $(".collapse").collapse('hide');
    })
    
    
    // refresca el espacio que muestra los usuarios conectados y desconectados
    $(document).ready(function() {
        setInterval( function(){
            $('.feedback').load('refrescar.php'); //actualizas el div
        }, 2000 );
    });
    //////////////////////////////////////// 
    
    
    
    // sidebar
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            // open or close navbar
            $('#sidebar').toggleClass('active');
            // close dropdowns
            $('.collapse.in').toggleClass('in');
            // and also adjust aria-expanded attributes we use for the open/closed arrows
            // in our CSS
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });
    });
            
    
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
    //////////////////////////////////////// 
            
    
    
    // activar opciones del menu
    $(function() {
        // elementos de la lista
        var menues = $(".navbar-nav .nav-item"); 

        // manejador de click sobre todos los elementos
        menues.click(function() {
            // eliminamos active de todos los elementos
            menues.removeClass("active_menu");
            // activamos el elemento clicado.
            $(this).addClass("active_menu");
        }); 
        
          
        var submenues = $(".dropdown-item"); 
        submenues.click(function() {
            // eliminamos active de todos los elementos
            submenues.removeClass("active_menu");
            // activamos el elemento clicado.
            $(this).addClass("active_menu");
        });
    });
    //////////////////////////////////////// 
    
    
    
    // activar opciones del sidebar
    $(function() {
        var menues2 = $(".list-unstyled .menu2"); 
        
        menues2.click(function() {
            menues2.removeClass("active");
            $(this).addClass("active");
        }); 
    
    });
    ////////////////////////////////////////
    
    
    
    
    
    // cambiar botones de confirmacion de cita
    $(function() {
        // elementos de la lista
        var menu_sist  = $("#menu_sist"); 
        var menu_admin = $(".menu_admin"); 
        
        var btn_coord  = $("#btn_coord"); 
        var menu_coord = $(".menu_coord"); 
        
        var btn_mark  = $("#btn_mark"); 
        var menu_mark = $(".menu_mark");  
        
        var btn_tel  = $("#btn_tel"); 
        var menu_tel = $(".menu_tel"); 
        
        
        
        // mostrar menu coordinador
        btn_coord.click(function() {
            
            // eliminamos active de todos los elementos
            menu_admin.addClass("d-none");
            menu_admin.removeClass("navbar-collapse");
            
            menu_mark.addClass("d-none");
            menu_mark.removeClass("navbar-collapse");
            
            menu_tel.addClass("d-none");
            menu_tel.removeClass("navbar-collapse");
            
            
            menu_coord.removeClass("d-none");
            menu_coord.addClass("navbar-collapse");
            
            menu_sist.addClass("navbar-expand-md");
            menu_sist.removeClass("navbar-expand");
            
           
        }); 
        
        
        // mostrar menu marketing
        btn_mark.click(function() {
            
            // eliminamos active de todos los elementos
            menu_admin.addClass("d-none");
            menu_admin.removeClass("navbar-collapse");
            
            menu_coord.addClass("d-none");
            menu_coord.removeClass("navbar-collapse");
            
            menu_tel.addClass("d-none");
            menu_tel.removeClass("navbar-collapse");
            
            
            menu_mark.removeClass("d-none");
            menu_mark.addClass("navbar-collapse");
            
            menu_sist.addClass("navbar-expand-md");
            menu_sist.removeClass("navbar-expand");
           
        });
        
        
        // mostrar menuteleoperadora
        btn_tel.click(function() {
            
            // eliminamos active de todos los elementos
            menu_admin.addClass("d-none");
            menu_admin.removeClass("navbar-collapse");
            
            menu_coord.addClass("d-none");
            menu_coord.removeClass("navbar-collapse");
            
            menu_mark.addClass("d-none");
            menu_mark.removeClass("navbar-collapse");
            
            
            menu_tel.removeClass("d-none");
            menu_tel.addClass("navbar-collapse");
            
            menu_sist.addClass("navbar-expand-md");
            menu_sist.removeClass("navbar-expand");
           
        }); 
        
   
    });
    
</script>

<script>
$(document).ready(function(){
 
 function load_unseen_notification(view = '')
 {
  $.ajax({
   url:"mensaje/fetch.php",
   method:"POST",
   data:{view:view},
   dataType:"json",
   success:function(data)
   {
    $('#mensaje').html(data.notification);
    if(data.unseen_notification > 0)
    {
     $('.count').html(data.unseen_notification);
    }
   }
  });
 }
 
 load_unseen_notification();
 
 $('#comment_form').on('submit', function(event){
  event.preventDefault();
  if($('#subject').val() != '' && $('#comment').val() != '')
  {
   var form_data = $(this).serialize();
   $.ajax({
    url:"mensaje/insert.php",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     $('#comment_form')[0].reset();
     load_unseen_notification();
    }
   });
  }
  else
  {
    alert('Campos Obligatorios');
  }
 });
 
 $(document).on('click', '.dropdown-toggle', function(){
  $('.count').html('');
  load_unseen_notification('yes');
 });
 
 setInterval(function(){ 
  load_unseen_notification();; 
 }, 5000);
 
});
    
    
</script>