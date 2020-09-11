<!-- cambio de ruta de la carpeta "mensajes" a módulos -->
<?php
//include ('sesion.php');
session_start();
// [ 'cookie_lifetime' =>120,
// 'gc_maxlifetime' => 120, ]
if($_SESSION['id'] == ""){
   // header("Location: sesion.php");   
    echo'<div class="css-display-topright mr-2 mt-2">
               <div class="alert alert-warning alert-dismissible fade show posicion_alert" role="alert" >
               <strong><i class="fas fa-exclamation-circle fa-2x"></i>Su sesión ha expirado, inicie nuevamente.</strong>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
               </div></div>';
}
    if($_SESSION["tiene_permiso"] == false or ($_SESSION['tipo'] != 1 AND $_SESSION['tipo'] != 4)){ 
            header("Location: cerrar.php"); 
    }   else {

    date_default_timezone_get('');
    ini_set('date.timezone', 'america/lima');
    $fecha   = date("d/m/Y");
    $tiempo  = date("g:i A");

    $usuario = $_SESSION['usuario'];
    $_SESSION['time'];
    ?>

    <!DOCTYPE html>
    <html lang="es" class="html_fondo">
        <head>
            <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="UTF-8">
            <meta name=”keywords” content=”depilacion,zonas”>
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
           <!-- <meta http-equiv="refresh" content="10"> -->

            <title>DepilZONE</title>
            <link rel="icon" type="image/png" href="imagenes/intranet-04.png" />
            
            <!--Para implementar css en este archivo-->
            <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.css"/>
            <link rel="stylesheet" href="todocss.css" type="text/css" media=screen>
            <link rel="stylesheet" href="lib/fontawesome/css/all.css"/>
            <link rel="stylesheet" href="css/reloj.css" type="text/css" media=screen>
           
            <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
            <script type="text/javascript" src="lib/popper.min.js"></script>
            <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="lib/fontawesome/js/all.js"> </script>
            <script type="text/javascript" src="js/expirarSesion.js"></script>
            <script type="text/javascript" src="js/cierre.js"> </script>
           <script type="text/javascript" src="js/reloj_clock.js"></script>
            <script src="js/custom.js"></script>
            
            <?php include('loader.php'); ?>
            <script type="text/javascript" src="js/loader.js"></script>

            <script type="text/javascript">
                 expirar();
             /*   
                window.onunload = unloadPage;
                function unloadPage(){
                    alert("unload event detected!");
                }*/
            </script>
        </head>
        
        <body onclick="unloadPage" onload="nobackbutton();" oncontextmenu='return false' onkeydown='return false'>
           
            <header class="cabecerainicial fixed-top">
                <div class="div_imagen_header">
                    <center><img class="css-image" src="imagenes/intranet-02.png"></center>
                </div>
                
                <div class="css-display-topleft">
                   <div><div class="clock small" id="clock"> <div class="date">Dom, 29. Jul 2018</div> <div class="time"></div> </div> </a> 
                    <?php /* include ('reloj.html'); */ ?>
                </div> </div>
                
                <div class="css-display-topright mr-1">
                    <center>
                        <p class="font-weight-bold mb-1" style="color:#444242"><i class="fas fa-user fa-lg" style="color:#70db70"></i> <?php echo $usuario?></p> 
                        <i class="fas fa-sign-out-alt fa-2x bot_sesion" onclick ="location='cerrar.php'" title="Cerrar Sesión"></i>
                    </center>
                </div>
                 
                 <!----------------------------------------------- Barra de navegacion ------------------------------------------------------------>
                 <nav class="navbar navbar-expand-md navbar-light" style="background-color:#607d8b">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse menu_bar2 justify-content-center" id="navbarSupportedContent">
                        <ul class="navbar-nav"> 
                            <li class="nav-item togglee menu_style active_menu">
                                <a class="nav-link px-md-2 px-lg-4 px-xl-5" href="modulos/anuncios_inicio/anuncioslide.php" target="espacio" onclick="carga_show_hide()"><i class="fas fa-home fa-lg"></i><strong> Inicio</strong> </a>
                            </li>
                            
                            <li class="nav-item togglee menu_style">
                                <a class="nav-link px-md-2 px-lg-4 px-xl-5" href="modulos/preferen_dia/preferen_dia.php" target="espacio" onclick="carga_show_hide()"><i class="fas fa-list-alt fa-lg"></i><strong> Preferentes del Día</strong></a>
                            </li>
                            
                            <!--<li class="nav-item togglee menu_style">
                                <a class="nav-link px-md-2 px-lg-4 px-xl-5" href="modulos/confirm_diarias/confirm_diarias.php" target="espacio" onclick="carga_show_hide()"><i class="fas fa-clipboard-check fa-lg"></i><strong> Confirmaciones Diarias</strong></a>
                            </li>-->
                            
                            <li class="nav-item dropdown menu_style">
                                <a class="nav-link dropdown-toggle px-md-2 px-lg-4 px-xl-5" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown"><i class="fas fa-calendar-alt fa-lg"></i><strong> Agenda</strong></a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item togglee" href="agenda.php" target="espacio" style="color:#444242!important;" onclick="carga_show_hide()">
                                        <span class="fa-layers fa-lg">
                                            <i class="fas fa-calendar-alt"></i>
                                            <i class="fas fa-search" data-fa-transform="shrink-8 down-2.2 right-0.9" style="color:#4caae1"></i>
                                        </span>
                                        <strong> Buscar Citas</strong>
                                    </a>
                                    
                                    <a class="dropdown-item togglee" href="nuevaficha1.php" target="espacio" style="color:#444242!important;" onclick="carga_show_hide()">
                                        <span class="fa-layers fa-lg">
                                            <i class="fas fa-notes-medical"></i>
                                        </span>
                                        <strong> Agregar Cliente</strong>
                                    </a>
                                    
                                    <a class="dropdown-item togglee" href="modulos/buscar_hist/buscar_hist.php" target="espacio" style="color:#444242!important;" onclick="carga_show_hide()">
                                        <span class="fa-layers fa-lg">
                                            <i class="fas fa-clipboard-list"></i>
                                        </span>
                                        <strong> Historias Clínicas</strong>
                                    </a>

                                    <a class="dropdown-item togglee" href="modulos/mensaje/index.php" target="espacio" style="color:#444242!important;" onclick="carga_show_hide()">
                                        <span class="fa-layers fa-lg">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <strong> Notificar error</strong>
                                    </a>
                                    
                                </div>
                            </li>
                        </ul>
                    </div>
                 </nav>
                 
                 <script>
                    $('.navbar-collapse .togglee').click(function() {
                        $(".collapse").collapse('hide');
                    })
                </script>
            </header>
       
             <!-------------------------------------------------------- contenido --------------------------------------------------->
            <div class="container-fluid posic_coord" id="show">
               <iframe class="tam_iframe2" id="iframe" name="espacio" src="modulos/anuncios_inicio/anuncioslide.php" frameborder="0" ></iframe>
            </div>
        
        </body>
    </html>
    <?php
    }
    ?>

<script type='text/javascript'>

  $(document).ready(function() { $("div#clock").simpleClock(-5); }); (function ($) { $.fn.simpleClock = function ( utc_offset ) { var language = "es"; switch (language) { case "de": var weekdays = ["So.", "Mo.", "Di.", "Mi.", "Do.", "Fr.", "Sa."]; var months = ["Jan.", "Feb.", "Mär.", "Apr.", "Mai", "Juni", "Juli", "Aug.", "Sep.", "Okt.", "Nov.", "Dez."]; break; case "es": var weekdays = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"]; var months = ["Ene", "Feb", "Mar", "Abr", "Mayo", "Jun", "Jul", "Ago", "Sept", "Oct", "Nov", "Dic"]; break; case "fr": var weekdays = ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"]; var months = ["Jan", "Fév", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"]; break; default: var weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]; var months = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"]; break; } var clock = this; function getTime() { var date = new Date(); var nowUTC = date.getTime() + date.getTimezoneOffset()*60*1000; date.setTime( nowUTC + (utc_offset*60*60*1000) ); var hour = date.getHours(); if ( language == "en" ) { suffix = (hour >= 12)? 'p.m.' : 'a.m.'; hour = (hour > 12)? hour -12 : hour; hour = (hour == '00')? 12 : hour; } return { day: weekdays[date.getDay()], date: date.getDate(), month: months[date.getMonth()], year: date.getFullYear(), hour: appendZero(hour), minute: appendZero(date.getMinutes()), second: appendZero(date.getSeconds()) }; } function appendZero(num) { if (num < 10) { return "0" + num; } return num; } function refreshTime(clock_id) { var now = getTime(); clock = $.find('#'+clock_id); $(clock).find('.date').html(now.day + ', ' + now.date + '. ' + now.month + ' ' + now.year); $(clock).find('.time').html("<span class='hour'>" + now.hour + "</span>:<span class='minute'>" + now.minute + "</span>:<span class='second'>" + now.second + "</span>"); if ( typeof(suffix) != "undefined") { $(clock).find('.time').append('<strong>'+ suffix +'</strong>'); } } var clock_id = $(this).attr('id'); refreshTime(clock_id); setInterval( function() { refreshTime(clock_id) }, 1000); }; })(jQuery);





    
/*function cerrar() { 
ventana=window.self; 
ventana.opener=window.self; 
ventana.close(); }  */



 

/*



   
    window.onbeforeunload = cerrar_navegador;

    function cerrar_navegador(){
        return "seguro que quieres cerrar la pagina?"
    }
    var redirected = false;
    $(window).bind('beforeunload', function(e){
        if(redirected)
            window.open('sesion.php');
        return;
        
        var orgLoc = window.location.href;
        $(window).bind('focus.unloadev',function(e){
            if(redirected == true)
                return;
            $(window).unbind('focus.unloadev');
            window.setTimeout(function(){
                if(window.location.href!=orgLoc)
                    return;
                console.log('redirect...');
                window.location.replace('sesion.php');
            },100);
        
            redirected = true;
        });
        console.log('before2'); 
        return "okdoky2";
    });

    $(window).unload(function(e){console.log('unloading...');redirected=true;});*/

</script>

