<?php
session_start();
include('conexionbd.php');
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="UTF-8">
        <meta name=”keywords” content=”depilacion,zonas”>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!--Para implementar css en este archivo-->
        <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.css"/> 
        <link rel="stylesheet" href="todocss.css" type="text/css" media=screen>
        <link rel="stylesheet" href="lib/fontawesome/css/all.css"/> 
        
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/popper.min.js"></script>
        <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="lib/fontawesome/js/all.js"></script>
        <script src="js/custom.js"></script>
    </head>
    <body onload="nobackbutton();">
        <div class="accordion scroll_tab_asi_pref" id="accordionExample">
            <?php
		
            date_default_timezone_get('');
            ini_set('date.timezone', 'america/lima');
            $idusers = $_SESSION['id']; 
		
            $hoy  = date("Y-m-d");
            $ayer = strtotime("-1 day", strtotime($hoy));
            $ayer = date("Y-m-d", $ayer);
            $sql="SELECT DISTINCT b.id_teleop, c.usuario, c.conexion 
                    FROM conf_diarias b, users c 
                   WHERE c.id = b.id_teleop 
                     AND b.fech_ing ='$ayer'"; //aqui va $ayer

            $resultado = mysqli_query($conexion,$sql);
            while($lista = mysqli_fetch_array($resultado)){
                $id_preferente = $lista['id_teleop'];
                $conex_us = $lista['conexion'];
                //extrae la matriz resultado en la variable
            ?>    
            <div class="card">
                <div class="card-header hover_collapse py-1 pl-0 bg-sistema">
                    <h5 class="my-0">
                        <button class="btn btn-link btn-block" id="color_<?php echo $id_preferente;?>" type="button" data-toggle="collapse" data-target="#pref<?php echo $id_preferente;?>" style="margin-left: 0px;" aria-expanded="true" aria-controls="collapseOne">
                            <h6 class="my-0 text-left">
                                <div class="fa-lg py-0">
                                    <span class="fa-layers fa-fw">
                                        <i class="fas fa-circle fa-lg"></i>
                                        <i class="fa-inverse fas fa-user-alt" data-fa-transform="shrink-4 left-2"></i>
                                        <i class="fa-inverse fas fa-phone" data-fa-transform="shrink-9 up-3.2 right-6"></i>
                                    </span> <span style="color: black"><?php echo $lista['usuario'];?></span> 
                                </div> 
                            </h6>
                        </button>
                    </h5>
                </div>
                     
                <div id="pref<?php echo $id_preferente;?>" class="collapse">
                    <div class="card-body pt-0">
                        <form action="asig_prefer.php" method="post">
                                
                            <div class="table-responsive mt-2">
                                <table class="table table-sm table-striped table-hover">
                                    <thead class="css-blue">
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Teléfono</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Fecha de cita</th>
                                            <th scope="col">Teleoperadora</th>
                                            <!--<th scope="col">Preferente</th>-->
                                            <!--<th scope="col"><center><i class="fas fa-check-square"></i></center></th>-->
                                            <th scope="col">Reasignar</th>
                                        </tr>
                                    </thead>
                                        
                                    <tbody>
                                        <?php 
                      
                $query = "SELECT DISTINCT a.historia, a.nombrec, a.apellidoc, a.telefonoc, a.correo, b.id, b.fecha, DATE_FORMAT(b.fecha, '%d/%m/%Y') fecha_format,
                                 c.id id1, d.conexion 
                            FROM cliente a, citas b, conf_diarias c, users d 
                           WHERE c.id_teleop = '$id_preferente' 
                             AND c.id_cita = b.id 
                             AND b.id_cliente = a.id 
                             AND d.id = '$id_preferente' 
                             AND c.fech_ing = '$ayer'";
                $resulta = mysqli_query($conexion,$query);
                while($datos = mysqli_fetch_array($resulta)){
                    $idlis = $datos['id1'];
                    $idsel = $datos['id1'];
                                        ?>
                                        <tr>
                                            <td class="text-capitalize"><?php echo $datos['nombrec']." ".$datos['apellidoc'] ;?></td>
                                            <td><?php echo $datos['telefonoc'] ;?></td>
                                            <td><?php echo $datos['correo'] ;?></td>
                                            <td><?php echo $datos['fecha_format'] ;?></td>
                                            <?php 
                    if($datos['conexion'] == 0){ 
                                            ?>
                                                 
                                            <td>
                                                <select name="preferente[]" class="custom-select custom-select-sm" id="b<?php echo $idsel;?>" disabled>
                                                    <?php  
                        include('conexionbd.php');
                        $query = "SELECT u.usuario,u.id 
                                    FROM users u, tipodusers t 
                                   WHERE u.tipo = t.tipo 
                                     AND t.tipo = 4 
                                     AND u.status = 'T'
                                     AND u.conexion = '1'
                                ORDER BY 1 ASC"; 
                        $result = mysqli_query($conexion,$query);
                        while($array = mysqli_fetch_array($result)){ 
                                                    ?>
                                                    <option value="<?php echo $array['id'];?>"><?php echo $array['usuario'];?></option>
                                                    <?php 
                        } 
                                                    ?>
                                                </select>
                                            </td>
                                                
                                            <td>
                                                <center>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="a<?php echo $idlis;?>" name="id[]" value="<?php echo $idlis;?>" onclick="cambio(<?php echo $idlis;?>,<?php echo $idsel; ?>)">
                                                        <label class="custom-control-label mb-3 ml-4" for="a<?php echo $idlis;?>"></label>
                                                    </div>
                                                </center>
                                            </td>
                                            <?php 
                    }
                    else{
                                            ?>
                                            <td></td>
                                            <td></td>
                                            <?php 
                    }
                                            ?>
                                        </tr>
                                        <?php   
                }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                if($lista['conexion'] == 0){
                            ?>
                                
                            <center>
                                <button type="submit" class="btn btn-primary" name="enviar" onclick="window.top.cargando()"><i class="fas fa-check"></i>  Aceptar</button>
                            </center>
                            <?php	
                } 
                            ?>
                        </form>
                    </div>
                </div>
            </div>
                
                
            <script>
                (function(){ 
                    var conex_us  = '<?php echo $conex_us;?>';
                    var id_pref   = '<?php echo $id_preferente;?>';
       
                    var color_conex = $('#color_'+id_pref); 
        
                    //alert(color_conex);
                    // color de usuario por conexion
                    if(conex_us == 1){
                        color_conex.addClass("btn-hover1");
                            
                        $( color_conex ).attr({title: "Conectado"});
                    }
        
                    else{
                        color_conex.addClass("btn-hover2");
                        color_conex.removeClass("btn-hover1");
                            
                        $( color_conex ).attr({title: "Desconectado"});
                    }
                })();
            </script>
                
                        
            <?php
            }
            
            if(isset($_POST['enviar'])){
                if (isset($_POST['id'])) {
                   $id_cit = $_POST['id']; 
                   $num    = count($id_cit);
                }
                else{
                    $id_cit="";
                    $num=0;
                
                }
                if (isset($_POST['preferente'])) {
                 $prefer = $_POST['preferente']; 

                 for($n=0;$n<$num;$n++){ 
                    $preferentec = $prefer[$n];
                    $id          = $id_cit[$n];
                                
                    $sql = "UPDATE conf_diarias SET id_teleop = '$preferentec', us_mod = '$idusers', fech_mod = CURRENT_TIMESTAMP() WHERE id = '$id'";
                    $respuesta = mysqli_query($conexion,$sql);     

                               }

                }
                else {
                    $prefer="";
                    ?>
                    <script type="text/javascript">
                        alert('Inserte el usuario');
                    </script>
                    <?php
                }
            }
             
            ?>
        </div>
    </body>
</html>

<script>
    // habilitar y desabilidar el select con un checkbox
    function cambio(id, idsel){
        if($('#a'+id).is(":checked")){
            $('#b'+idsel).prop('disabled', false);
        }
        else{
            $('#b'+idsel).prop('disabled', true);
        }
    }
</script>