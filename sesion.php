<!--PAGINA DE INICIAL DE LA PAGINA WEB, ESTA PAGINA AL PRESIONAR EL BOTON "Ingresar" SE DIRIGE A LA PAG DE CONTROL INTROSESION.PHP-->
<!--MODIFICO ING. ANDREINA OLIVARES 14/03/2019-->
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
        <link rel="stylesheet" href="lib/alertifyjs/css/alertify.min.css" type="text/css">  
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/popper.min.js"></script>
        <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="lib/fontawesome/js/all.js"> </script>
        <script src="js/custom.js"></script>
        <script type="text/javascript" src="lib/alertifyjs/alertify.js"></script>
        <script type="text/javascript" src="lib/alertifyjs/alertify.min.js"></script>
        <script type="text/javascript" src="js/admin_sms.js"></script>

    </head>
    
    <body onload="nobackbutton();">
        <div class="loader" style="display: none;">
            <div class="icono_carga text-center"><i class="fas fa-circle-notch fa-spin fa-7x" style="color: white"></i><h2 class="">Cargando...</h2></div> 
        </div>
        
       <header class="cabecerainicial fixed-top">
            <div class="div_imagen_header">
                <center><img class="css-image mt-4 mt-sm-0" src="imagenes/intranet-02.png"></center>
            </div>
           
           <!-- en lo siguiente, la variable "$_GET['expiro']"" viene del archivo expirarSesion.js que no se esta usando actualmente. Esa variable es por si expiraba la
            sesion y se cerraba , le mostrara  en el inicio de sesion dicho aviso para que el usuario supiera lo que paso-->
           <?php
           if (isset($_GET['expiro'])) {
              $expiro = $_GET["expiro"];
               echo'<div class="css-display-topright mr-2 mt-2">
               <div class="alert alert-warning alert-dismissible fade show posicion_alert" role="alert" >
               <strong><i class="fas fa-exclamation-circle fa-2x"></i>Su sesión ha expirado, inicie nuevamente.</strong>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
               </div></div>';
           }
           ?>
           <!-- esta parte -->
           <nav class="navbar navbar-expand-lg navbar-light" style="background-color:#607d8b">
                <div class="menu_bar"></div>
            </nav>
        </header> 
        
        
        
        <div class="container posic_sesion">
            <form class="needs-validation css-sesion shadow-lg rounded" action="introsesion.php" method="POST" novalidate>
                <h3 class="titulo_sesion">Control de Acceso</h3>
                
                <div class="form-row">
                    <div class="form-group col-12">
                        <center><label class="col-form-label">Usuario</label></center>
                        <input type="text" class="form-control css-input" placeholder="Ingrese usuario" name="usuario" required autocomplete="off">
                        <div class="invalid-tooltip">Dato obligatorio.</div>
                    </div>
                    
                    <div class="form-group col-12">
                        <center><label class="col-form-label">Contraseña</label></center>
                        <input type="password" class="form-control css-input" placeholder="Ingrese contraseña" name="clave" required>
                        <div class="invalid-tooltip">Dato obligatorio.</div>
                    </div>
                </div>
                
                <center>
                    <button type="submit" class="btn btn-primary" name="enviar" title="Ingresar" ><i class="fas fa-check"></i> Ingresar</button>
                    <button type="reset" class="btn btn-primary" title="Limpiar"><i class="fas fa-redo-alt"></i> Limpiar</button> 
                </center>
            </form>
                        
        </div>
    </body>
    <footer class="cajabaja fixed-bottom">
        <div class="row division my-3">
            <div class="col-12 col-md-4">
                <!-- Designed by:<br> 
                <i class="fas fa-user"></i> Ing. Yuleska Sequeira<br>
                <i class="fas fa-user"></i> Ing. Maria Gabriela Rodriguez<br>
                <i class="fas fa-user"></i> Ing. Kinverly Navas<br>
                <i class="fas fa-phone"></i> Teléfono de contacto: +51917852281<br>
                <i class="fas fa-map-marked-alt"></i> Dirección: Lima, Perú
                -->
            </div>
            
            <div class="col-12 col-md-8 mt-2 mt-md mx-auto mx-md">
                <a href="https://www.facebook.com/depilacionlaserlima/" class="mr-1 mr-sm-5"><img class="rs" src="imagenes/fb.svg"></a>
                <a href="" class="mx-1 mx-sm-5"><img class="rs" src="imagenes/instagrama.svg"></a>
                <a href="https://depilzone.com.pe/contacto/" class="ml-1 ml-sm-5"><img class="rs" src="imagenes/gmail.svg"></a>
            </div>
        </div>
    </footer>    
</html>
<script>
  
    // validacion de formulario  
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function(){
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form){
                form.addEventListener('submit', function(event){
                    if (form.checkValidity() === false){
                        event.preventDefault();
                        event.stopPropagation();
                        
                    }
                    else{
                        cargando();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
   
    function cargando(){
        $(".loader").fadeIn();
    };
</script>
 <?php
//MENSAJE DE CIERRE DE SESSION
if (isset($_GET['errorsession'])) {
  echo "<script type='text/javascript'> 
               updatereg();         
        </script>";  
}    
//MENSAJE DE CLAVE ERRADA
if (isset($_GET['errorclave'])) {
  echo "<script type='text/javascript'> 
            errorsession();
        </script>";    
}

if (isset($_GET['accesonegado'])) {
  echo "<script type='text/javascript'> 
               accesoneg();         
        </script>";  
}  

 ?>