<?php
ob_start();
include('conexionbd.php');
//MODIFICADO POR ING. GABRIELA 03/05/2019, INFORMACION DEL CLIENTE AGREGA CAMPOS Y ACTUALIZAR
$id_cliente = $_GET['id_cliente'];
?>

<!DOCTYPE html>
<html lang="es">
    <head>

        <meta http-equiv="Expires" content="0">
        
        <meta http-equiv="Last-Modified" content="0">
        
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="UTF-8">
        <meta name=”keywords” content=”depilacion,zonas”>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--Para implementar css en este archivo-->
        <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.css"/>
        <link rel="stylesheet" href="todocss.css" type="text/css" media=screen>
        <link rel="stylesheet" href="lib/fontawesome/css/all.css"/> 
        <link rel="stylesheet" href="lib/alertifyjs/css/alertify.min.css" type="text/css" media=screen>
        <link rel="stylesheet" type="text/css" href="gritter/css/jquery.gritter.css" />
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/popper.min.js"></script>
        <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="lib/fontawesome/js/all.js"></script> 
        <script type="text/javascript" src="lib/alertifyjs/alertify.min.js"></script>
        <script type="text/javascript" src="js/funciones.js"></script>
        <?php include('modal_editarCli.php');?> 
    </head>
    <body>
        <div id="ficha_cli" class="scroll_tab_us">
            <div class="accordion" id="accordionExample">
                <!--------------------------------------------------------------- Datos personales en Agendar---------------------------------------->
                <div class="card">
                    <!--<p> <?php print_r($_SESSION['id']); ?> </p>-->
                    <?php
                    // Trae los datos del cliente correspondiente a lo que viene de nuevaficha2
                    $consult = "SELECT cli.historia, cli.fechaalta, DATE_FORMAT(cli.fechaalta, '%d/%m/%Y') fecha_format, cli.nombrec, cli.prefijo, cli.telefonoc, cli.prefijo2, cli.telefonoc2, 
                    cli.correo, cli.direccion, cli.medio, cli.medio1, cli.preferentec, cli.apellidoc, cli.dni, cli.status, cli.publicidad, cli.cumple, u.usuario, d.descripcion, cli.id, cli.seudoni, cli.id_sexo, s.descripcion sexo
                                  FROM cliente cli, users u, distritos d, sexo s 
                                 WHERE cli.id = '$id_cliente' 
                                   AND cli.us_ing = u.id 
                                   AND cli.id_sexo = s.id 
                                   AND cli.direccion = d.id";
                    $reconsult = mysqli_query($conexion,$consult);  
                    $ver       = mysqli_fetch_array($reconsult);
                    do{  
                       $datos = $ver['historia']."||".$ver['nombrec']."||".$ver['apellidoc']."||".$ver['seudoni']."||".$ver['dni']."||".$ver['prefijo']."||".$ver['telefonoc']."||".$ver['prefijo2']."||".$ver['telefonoc2']."||".$ver['correo']."||".$ver['direccion']."||".$id_cliente."||".$ver['status']."||".$ver['id_sexo']."||".$ver['publicidad']."||".$ver['cumple'];
                        
                    ?> 
                    <div class="card-header bg-sistema hover_collapse py-1" id="headingOne">
                        <h5 class="my-0">
                            <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapseOne" style="margin-left: 0px;" aria-expanded="true" aria-controls="collapseOne">
                                <h5 class="my-0 mr-auto">
                                    Ficha del cliente
                                    <?php 
                        if($ver['status'] == 'A'){
                                    ?> 
                                    <span class="badge badge-pill badge-success">Activo</span>
                                    <?php 
                        }
                        elseif($ver['status'] == 'F'){
                                    ?> 
                                    <span class="badge badge-pill badge-warning">Suspendido</span>
                                    <?php 
                        }  
                        else{
                                    ?>
                                    <span class="badge badge-pill badge-danger">Inactivo</span>
                                    <?php 
                        }
                                    ?>
                                </h5>
                            </button>
                        </h5>
                    </div>
                    
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body pt-0" id="dat_cli">
                            <div class="form-row">
                                <div class="form-group col-12 col-md-3">
                                    <label class="col-form-label">Nro. de cliente</label>
                                    <input id="idClienteDepilzone"  type="text" class="form-control-plaintext css-input" value="<?php echo $ver['id'];?>" readonly>
                                </div>
                                
                                <div class="form-group col-12 col-md-3">
                                    <label class="col-form-label">Fecha de alta</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['fecha_format'];?>" readonly>
                                </div> 
                        
                                <div class="form-group col-12 col-md-3">
                                    <label class="col-form-label">Teleoperadora</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['usuario'];?>" readonly>
                                </div>     
                                    
                                <div class="form-group col-12 col-md-3">
                                    <label class="col-form-label">Nro. de historia</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['historia'];?>" readonly>
                                </div>
                            </div>

                             <div class="form-row">
                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Nombres</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['nombrec'];?>" style="text-transform: capitalize;" readonly>
                                </div>
                                <?php  if($ver['id_sexo']== 3){ ?> 
                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Seudónimo</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['seudoni'];?>" style="text-transform: capitalize;" readonly>
                                </div>
                                 <?php } ?>
                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Apellidos</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['apellidoc'];?>" style="text-transform: capitalize;" readonly>
                                </div>
                                <!-- <9/8/19 GR - creacion campo DNI>-->
                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">DNI</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['dni'];?>" readonly>
                                </div>
                                
                                <div class="col-xs-2">
                                    <label class="col-form-label">Sexo</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['sexo'];?>" readonly>
                                </div>

                                <div class="form-group col-md-1">
                                    <label class="col-form-label">Prefijo</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['prefijo'];?>" minlength="1" maxlength="4" pattern="[0-9]+" title="Solo números" autocomplete="off" required>
                                    <div class="invalid-tooltip">Dato obligatorio (Solo números).</div>
                                </div>
                            
                                <div class="form-group col-12 col-md-2">
                                  <label class="col-form-label">Teléfono</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['telefonoc'];?>" readonly>
                                </div>
                            
                                <div class="form-group col-md-1">
                                    <label class="col-form-label">Prefijo2</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['prefijo2'];?>" minlength="1" maxlength="4" pattern="[0-9]+" title="Solo números" autocomplete="off" required>
                                    <div class="invalid-tooltip">Dato obligatorio (Solo números).</div>
                                </div>
                            
                                <div class="form-group col-12 col-md-2">
                                  <label class="col-form-label">Teléfono2</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['telefonoc2'];?>" readonly>
                                </div>         
                            </div>                          
                            <div class="form-row">
                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Correo</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['correo'];?>" readonly>
                                </div>

                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Medio de contacto</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['medio'];?>" readonly>
                                </div>
                                
                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Otro medio de contacto</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['medio1'];?>" readonly>
                                </div>
                                
                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Dirección</label>
                                    <input type="text" rows="2" cols="125" class="form-control-plaintext css-input" value="<?php echo $ver['descripcion'];?>" readonly>
                                </div>

                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Publicidad</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['publicidad'];?>" style="text-transform: capitalize;" readonly>
                                </div>

                                <div class="form-group col-12 col-md">
                                    <label class="col-form-label">Cumpleaños</label>
                                    <input type="text" class="form-control-plaintext css-input" value="<?php echo $ver['cumple'];?>" readonly>
                                </div>

                            </div>
                            <center>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalEdicion" id="actualizar" onclick="agregaform('<?php echo $datos ?>')"><i class="fas fa-check"></i> Actualizar datos</button>
                            </center>
                            <?php 
                    } 
                    while($datos = mysqli_fetch_array($reconsult));
                            ?>

                        </div>
                    </div>
                </div>
          
                <!---------------------------------------------------------------------- CITAS ------------------------------------------------------------------------>
                <div class="card">
                     <div class="card-header bg-sistema hover_collapse py-1" id="headingTwo">
                         <h5 class="my-0">
                            <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapseTwo" style="margin-left: 0px;" aria-expanded="true" aria-controls="collapseOne"><h5 class="my-0">Citas</h5>
                            </button>
                        </h5>
                    </div>
                    
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" >
                        <div class="card-body pt-0">

                            <?php 
                            if(isset($_SESSION['id'])){
                                $id_user = $_SESSION['id'];
                            }
                            else{
                                $id_user = "";
                            }
                                if ($id_user == NULL) {
                                          header('refresh:6; URL=cerrar.php');
                                    echo "<div class= 'alert alert-info'> Tu sesion ha expirado </div>";
                                }
                                else {
                            ?>
                            <script>
                                // function(){
                                //     var cli_status = '';
                                    
                                //     // boton agregar nueva cita
                                //     if(cli_status == 'I'){
                                //         $("#bot_nueva_cit").addClass("d-none");
                                //     }
                                //     else{
                                //         $("#bot_nueva_cit").removeClass("d-none");
                                //     }
                                // }
                                function recargar_citas2(){
                                   
                                   var filtro_tto = $('#cmb-tto2').val();
                                   var id_cliente= $('#idClienteDepilzone').val();
                                   var obj = document.getElementById('cmb-tto2');
                                   var archivo_seleccionado = obj.options[obj.selectedIndex].getAttribute('data-archivo');
                                   //console.log(filtro_tto);
                                   const valor = {filtro_tto,id_cliente,archivo_seleccionado};
                                  
                                   $.ajax( {
                                       url: 'agendarBuscar2.php',
                                       type: 'POST',
                                       data: valor,
                                       /*processData: false,
                                       contentType: false,*/
                                       success:function(response){ 
                                           $("#tbl_citas2").html(response);
                                       }
                                   } );
                               }
                                function recargar_citas(){
                                   
                                    var filtro_tto = $('#cmb-tto').val();
                                    var id_cliente= $('#idClienteDepilzone').val();
                                    var obj = document.getElementById('cmb-tto');
                                    var archivo_seleccionado = obj.options[obj.selectedIndex].getAttribute('data-archivo');
                                    //console.log(filtro_tto);
                                    const valor = {filtro_tto,id_cliente,archivo_seleccionado};
                                   
                                    $.ajax( {
                                        url: 'agendarBuscar.php',
                                        type: 'POST',
                                        data: valor,
                                        /*processData: false,
                                        contentType: false,*/
                                        success:function(response){ 
                                            $("#tbl_citas").html(response);
                                        }
                                    } );
                                }
                                function direccionar() {
                                    var filtro_tto = $('#cmb-tto').val();
                                    var obj = document.getElementById('cmb-tto');
                                    var archivo_seleccionado = obj.options[obj.selectedIndex].getAttribute('data-archivo');
                                    var id_cliente= $('#idClienteDepilzone').val();
                                    //console.log(id_cliente+' '+archivo_seleccionado)
                                    window.location.assign('citas'+archivo_seleccionado+'.php?id_cliente='+id_cliente+'&id_tratamiento='+filtro_tto+'&procedencia=nueva_cita');
                                }
                                window.addEventListener('load',function(){
                                    //cuando carga la pagina debe mostrar depilacion laser en el select como valor por defecto
                                    $('#cmb-tto').val(1);
                                    $('#cmb-tto2').val(1);
                                });
                            </script>
                            <div class="d-flex justify-content-end my-1">
                                <div class="form-group col-12 col-md-4 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Tratamiento</label>
                                    </div>
                                    <select id="cmb-tto" class="custom-select custom-select-sm" onchange="recargar_citas()" required>                                        
                                        <?php
                                        $queryTTO = "SELECT * FROM tratamientos WHERE status = 'T'";
                                        $traer_tto = mysqli_query($conexion,$queryTTO);    
                                    
                                        while($consul_tto = mysqli_fetch_array($traer_tto)){
                                        ?>
                                        <option value="<?php echo $consul_tto['id']; ?>" data-archivo="<?php echo $consul_tto['url'];?>"><?php echo $consul_tto['nombre'];?></option>
                                        <?php 
                                        } 
                                        ?>
                                    </select>
                                    <div class="invalid-tooltip">Dato obligatorio.</div>

                                    <?php 
                                    if($ver['status'] == 'A') {  
                                     ?>

                                    <button type="button" class="btn btn-sm btn-primary" id="bot_nueva_cit" name="crearcita[]" title="Nueva Cita" onclick="direccionar()"><i class="fas fa-plus"></i> Nueva cita</button>
                                    <?php
                                     }  
                                  ?> 
                                </div>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            <!------ realizar consulta de citas (un select) y si  tiene citas q las muestr, sino muestre msj q no tiene citas --->
                            <?php 
                            $ver = "SELECT id FROM citas WHERE id_cliente = '$id_cliente' AND id_asistencia = 1 AND motivo_no_tto = ''";
                            $ver_conec  = mysqli_query($conexion,$ver);
                            $ver_result = mysqli_fetch_array($ver_conec);
                            $id         = $ver_result['id'];                            
                            if($id >= 1){
                            ?>
                            
                            <div class="table-responsive">
                                 <table class="table table-sm table-striped table-hover" id="tbl_citas">
                                     <thead class="css-blue">
                                         <tr>
                                             <th>Cita</th>       
                                             <th class="tam_th">Fecha/Hora</th>       
                                             <th class="tam_th1">Resumen</th>
                                             <th>Tipo</th>
                                             <th>Status Cita</th>
                                             <th>Status Pago</th>
                                             <th>Importe</th>
                                             <th>Sede</th>
                                             <th></th>
                                         </tr>
                                     </thead>
                                     
                                     <tbody>
                                         <?php 
                                $query = "SELECT c.id,c.id_sede, c.fecha, c.hora,  DATE_FORMAT(c.fecha, '%d/%m/%Y') fecha_format, DATE_FORMAT(c.hora, '%h:%i %p') hora_format,
                                                 c.id_asistencia, GROUP_CONCAT(CONCAT(z.partes, ' Sesión ' ,s.sesion) SEPARATOR ' - ') resumen, c.total, ct.descripcion cit_ti, ca.descripcion cit_st, pt.descripcion status_pago
                                            FROM citas c INNER JOIN tabla_zonas t ON c.id = t.id_cita,  cit_tipo ct, cit_asistencia ca, sesiones s, zonas z, pag_tipo pt
                                           WHERE c.id_cliente = $id_cliente
                                             AND c.cod_cit_tipo = ct.codigo
                                             AND c.id_asistencia = ca.codigo
                                             AND c.id_asistencia = 1 
                                             AND c.motivo_no_tto = ''
                                             AND t.sesion = s.id
                                             AND t.id_zona = z.id
                                             AND pt.codigo = c.cod_pago_tipo
                                        GROUP BY c.id
                                        ORDER BY c.fecha DESC";
                                $resultado = mysqli_query($conexion,$query);
                                while($lista = mysqli_fetch_array($resultado)){
                                    $id_c = $lista['id'];
                                         ?> 
                                         <tr>
                                             <td><?php echo $lista['id'];?></td>    
                                             <td><?php echo $lista['fecha_format']." ".$lista['hora_format'];?></td>
                                             <td><?php echo $lista['resumen'];?></td> 
                                             <td><?php echo $lista['cit_ti'];?></td>    
                                             <td><?php echo $lista['cit_st'];?></td>
                                             <td><?php echo $lista['status_pago'];?></td>
                                             <td>S/ <?php echo $lista['total'];?></td>
                                             <td><?php echo ($lista['id_sede'])==1?'San Borja':'Megaplaza';?></td>
                                             <td>
                                                 <a href="citas3.php?id_ci=<?php echo $id_c;?>&id_cli=<?php echo $id_cliente;?>&procedencia=editar_cita" class="btn-hover3" title="Editar" target="_blank"  >
                                                     <div class="fa-lg">
                                                         <span class="fa-layers fa-fw">
                                                             <i class="fas fa-circle"></i>
                                                             <i class="fa-inverse fas fa-pencil-alt" data-fa-transform="shrink-6"></i>
                                                         </span>
                                                     </div>
                                                 </a>
                                             </td>
                                         </tr>
                                         <?php 
                                } 
                                         ?>     
                                     </tbody> 
                                 </table>
                            </div> 
                            <?php 
                            }

                            else{
                                echo "<div class='alert alert-warning'><i class='fas fa-exclamation-circle fa-2x'></i> Este cliente no posee citas</div>";
                            } 
                            
                            ?>
                        </div>
                    </div>
                </div>

                
                <!------------------------------------------------- HISTORIAL DE CITAS --------------------------------------->    
                <div class="card">
                    <div class="card-header bg-sistema hover_collapse py-1" id="headingThree">
                        <h5 class="my-0">
                            <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapseThree" style="margin-left: 0px;" aria-expanded="true" aria-controls="collapseOne"><h5 class="my-0">Historial de citas</h5>
                            </button>
                        </h5>
                    </div>
                   
                    <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" >
                        <div class="d-flex justify-content-end my-1">
                            <div class="form-group col-12 col-md-4 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text">Tratamiento</label>
                                    </div>
                                    <select id="cmb-tto2" class="custom-select custom-select-sm" onchange="recargar_citas2()" required>                                        
                                        <?php
                                        $queryTTO2 = "SELECT * FROM tratamientos WHERE status = 'T'";
                                        $traer_tto2 = mysqli_query($conexion,$queryTTO);    
                                    
                                        while($consul_tto2 = mysqli_fetch_array($traer_tto2)){
                                        ?>
                                        <option value="<?php echo $consul_tto2['id']; ?>" data-archivo="<?php echo $consul_tto2['url'];?>"><?php echo $consul_tto2['nombre'];?></option>
                                        <?php 
                                        } 
                                        ?>
                                    </select>
                                    <div class="invalid-tooltip">Dato obligatorio.</div>

                                </div>
                            </div>
                        </div>
                    <?php 
                            $ver = "SELECT id FROM citas WHERE id_cliente = '$id_cliente' AND ((id_asistencia != 1) OR (motivo_no_tto != ''))";
                            $ver_conec  = mysqli_query($conexion,$ver);
                            $ver_result = mysqli_fetch_array($ver_conec);
                            $id         = $ver_result['id'];                            
                            if($id >= 1){
                            ?>
                            
                            <div class="table-responsive">
                                 <table class="table table-sm table-striped table-hover" id="tbl_citas2">
                                     <thead class="css-blue">
                                         <tr>
                                             <th>Cita</th>       
                                             <th class="tam_th">Fecha/Hora</th>       
                                             <th class="tam_th1">Resumen</th>
                                             <th>Tipo</th>
                                             <th>Status Cita</th>
                                             <th>Status Pago</th>
                                             <th>Importe</th>
                                             <th>Sede</th>
                                             <th></th>
                                         </tr>
                                     </thead>
                                     
                                     <tbody>
                                         <?php 
                                $query = "SELECT c.id,c.id_sede, c.fecha, c.hora,  DATE_FORMAT(c.fecha, '%d/%m/%Y') fecha_format, DATE_FORMAT(c.hora, '%h:%i %p') hora_format,
                                                 c.id_asistencia, GROUP_CONCAT(CONCAT(z.partes, ' Sesión ' ,s.sesion) SEPARATOR ' - ') resumen, c.total, ct.descripcion cit_ti, ca.descripcion cit_st, pt.descripcion status_pago
                                            FROM citas c INNER JOIN tabla_zonas t ON c.id = t.id_cita,  cit_tipo ct, cit_asistencia ca, sesiones s, zonas z, pag_tipo pt
                                           WHERE c.id_cliente = $id_cliente
                                             AND c.cod_cit_tipo = ct.codigo
                                             AND c.id_asistencia = ca.codigo
                                             AND ((c.id_asistencia != 1) OR (c.motivo_no_tto != ''))
                                             AND t.sesion = s.id
                                             AND t.id_zona = z.id
                                             AND pt.codigo = c.cod_pago_tipo
                                        GROUP BY c.id
                                        ORDER BY c.fecha DESC";
                                $resultado = mysqli_query($conexion,$query);
                                while($lista = mysqli_fetch_array($resultado)){
                                    $id_c = $lista['id'];
                                         ?> 
                                         <tr>
                                             <td><?php echo $lista['id'];?></td>    
                                             <td><?php echo $lista['fecha_format']." ".$lista['hora_format'];?></td>
                                             <td><?php echo $lista['resumen'];?></td> 
                                             <td><?php echo $lista['cit_ti'];?></td>    
                                             <td><?php echo $lista['cit_st'];?></td>
                                             <td><?php echo $lista['status_pago'];?></td>
                                             <td>S/ <?php echo $lista['total'];?></td>
                                             <td><?php echo ($lista['id_sede'])==1?'San Borja':'Megaplaza';?></td>                                             
                                             <!-- <td>
                                                 <a href="citas4.php?id_c=<?//php echo $id_c;?>&id_cli=<?php//echo $id_cliente;?>&procedencia=historial_cita" class="btn-hover3" title="Editar"  target="_blank">
                                                     <div class="fa-lg">
                                                         <span class="fa-layers fa-fw">
                                                             <i class="fas fa-circle"></i>
                                                             <i class="fa-inverse fas fa-pencil-alt" data-fa-transform="shrink-6"></i>
                                                         </span>
                                                     </div>
                                                 </a>
                                             </td> -->
                                             <td>
                                                 <a href="citas3.php?id_ci=<?php echo $id_c;?>&id_cli=<?php echo $id_cliente;?>&procedencia=editar_cita" class="btn-hover3" title="Editar" target="_blank"  >
                                                     <div class="fa-lg">
                                                         <span class="fa-layers fa-fw">
                                                             <i class="fas fa-circle"></i>
                                                             <i class="fa-inverse fas fa-pencil-alt" data-fa-transform="shrink-6"></i>
                                                         </span>
                                                     </div>
                                                 </a>
                                             </td>
                                         </tr>
                                         <?php 
                                } 
                                         ?>     
                                     </tbody> 
                                 </table>
                            </div> 
                            <?php 
                            }

                            else{
                                echo "<div class='alert alert-warning'><i class='fas fa-exclamation-circle fa-2x'></i> Este cliente no posee citas</div>";
                            } 
                            
                            ?>
                        </div>
                    </div>
                </div><!-- card historial -->

 <!-- JVG: Agregado de script para las funciones de gaurdar,sumar y listar -->
                <script>
                    function guardarTarjeta() {
                        var id_cliente = document.getElementById('idClienteDepilzone').value;
                        var tarjeta=document.getElementsByName('tarjeta')[0].value;
                        var puntos=document.getElementsByName('puntos')[0].value;
                        var ajax_url='agendarGuardar.php';
                        var params='id_cliente='+id_cliente+'&tarjeta='+tarjeta+'&puntos='+puntos;
                        var ajax_request= new XMLHttpRequest();
                        ajax_request.onreadystatechange = function() {
                            if (ajax_request.readyState == 4 ) {
                                var response = JSON.parse(ajax_request.responseText);
                                console.log(response);
                                if(response.success){
                                   
                                    location.reload();
                                    //no se puede listar ya que para eso se tendria que recrear el formulario entero
                                }else{
                                    //mostrar mensaje de error
                                    //principalmente para desarrollo, ya que en produccion ya no hay errores
                                    console.log(response);
                                }
                            }
                        }
                        ajax_request.open("POST",ajax_url,true);  
                        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");      
                        ajax_request.send(params);  
                        

                    }
                    function sumarTarjeta(){
                        var id_cliente = document.getElementById('idClienteDepilzone').value;
                        var tarjeta=parseInt(document.getElementsByName('codTarjeta')[0].innerHTML);
                        var descrip=document.getElementsByName('descrip')[0].value;
                        var puntos=parseInt(document.getElementsByName('currentPuntos')[0].innerHTML);
                        var puntos2=document.getElementsByName('puntos2')[0].value;
                        var ajax_url='agendarSumar.php';
                        var params='id_cliente='+id_cliente+'&descrip='+descrip+'&puntos2='+puntos2+'&tarjeta='+tarjeta+'&puntos='+puntos;
                        console.log(params);
                        var ajax_request= new XMLHttpRequest();
                        ajax_request.onreadystatechange = function() {
                            if (ajax_request.readyState == 4 ) {
                                var response = JSON.parse(ajax_request.responseText);
                                console.log(response);
                                if(response.success){
                                   
                                    listarHistorial();
                                    document.getElementsByName('puntos2')[0].value='';
                                    document.getElementsByName('descrip')[0].value='';
                                }else{
                                    //mostrar mensaje de error
                                    //principalmente para desarrollo, ya que en produccion ya no hay errores
                                    console.log(response);
                                }
                            }
                        }
                        ajax_request.open("POST",ajax_url,true);  
                        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");      
                        ajax_request.send(params);  
                    }
                    function listarHistorial(){
                        var ajax_url='agendarListarHistorial.php';       
                        var params='id_cliente='+ document.getElementById('idClienteDepilzone').value;  
                            ajax_url+='?' + params;
                            var ajax_request= new XMLHttpRequest();
                            ajax_request.onreadystatechange = function(){
                                if(ajax_request.readyState==4){                                
                                    var response = JSON.parse(ajax_request.responseText);
                                    var output=''                    
                                    if(response.success){
                                       for (let index = 0; index < response.data.tarjeta_historial.length; index++) {
                                           const element = response.data.tarjeta_historial[index];
                                           output+='<tr>';
                                           output+='<td>'+element.id+'</td>';
                                           output+='<td>'+element.descripcion+'</td>';
                                           output+='<td>'+element.ingreso+'</td>';
                                           output+='<td>'+element.total_puntos+'</td>';
                                           output+='<td>'+element.saldo_total+'</td>';
                                           output+='</tr>'
                                            if(index==(response.data.tarjeta_historial.length-1)){
                                                document.getElementsByName('equivalenteSoles')[0].innerHTML=element.saldo_total; 
                                                document.getElementsByName('currentPuntos')[0].innerHTML=element.total_puntos;
                                            }
                                       }
                                       document.getElementById('tabla_tarjeta_historial').innerHTML=output;                                       
                                    }else{
                                       console.log(response)
                                    };
                                };            
                            }
                            ajax_request.open('GET',ajax_url);
                            ajax_request.send();
                    }
                </script>

                <!------------------------------------------------- PUNTOS DEPILCARD --------------------------------------->    
                <div class="card">
                    <div class="card-header bg-sistema hover_collapse py-1" id="headingFour">
                        <h5 class="my-0">
                            <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapseFour" style="margin-left: 0px;" aria-expanded="true" aria-controls="collapseOne"><h5 class="my-0">DepilCard</h5>
                            </button>
                        </h5>
                    </div>
                    
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" >
                        <div class="card-body pt-1">
                            
                            <!------ realizar consulta de tarjeta (un select) --->
                            <?php
                            
                            $ver2 = "SELECT id FROM tarjeta WHERE id_cliente = '$id_cliente'";
                            $ver_conec2  = mysqli_query($conexion, $ver2);
                            $ver_result2 = mysqli_fetch_array($ver_conec2);
                            $id_citZ = $ver_result2['id'];
                            
                            if($ver_conec2->num_rows == 0){
                            ?>
                            
        <!-- Si no tiene registro de trajeta depilcard, aparece formulario de registro -->
                    <div class="table-responsive-lg scroll_tab_us">
                        <table class="table table-sm table-hover table-striped">
                            
                                <tr class="d-lg-flex">
                                    <div>
                
                                        <td class="col-md col-xl-2"></td>
                                        <td class="col-md col-xl-2"></td>
                                        <td class="col-md col-xl-2"><input type="number" class="form-control form-control-sm" name="tarjeta" placeholder="últimos 4 digitos" minlength="4" maxlength="4" required> </td>
                                        
                                        <td class="col-md col-xl-2"><input type="number" class="form-control form-control-sm" name="puntos" placeholder="Puntos ganados" required></td>
                                            
                                      
                                         <td class="col-md">
                                            <button onclick="guardarTarjeta()" class="btn btn-primary btn-sm" title="Guardar" name="guardar" ><i class="fas fa-check"></i> Guardar</button>
                                        </td>
                                    <?php 

                                   /* if(isset($_POST["tarjeta"])){
                                        $tarjeta = $_POST["tarjeta"];
                                         }
                                         
                                     if(isset($_POST["puntos"])){
                                        $puntos = $_POST["puntos"];
                                        
                                        $saldo = ($puntos * 0.10);
}
                                    if (isset($_POST['guardar'])){
                            
                                    $sql = "INSERT INTO tarjeta(tarjeta, puntos, id_cliente) values ('$tarjeta','$puntos','$id_cliente')";
                                    $res = mysqli_query($conexion,$sql);

                                    $his = "INSERT INTO tarjeta_historial(tarjeta, id_cliente, descripcion, ingreso, total_puntos, saldo_total) values ('$tarjeta', '$id_cliente', 'Activación', '$puntos', '$puntos', '$saldo')";
                                    $res_his = mysqli_query($conexion,$his);

                                    //print_r($his);
                                        echo "<script>location.href='agendar.php?id_cliente=$id_cliente'</script>";
                                            }*/
                                            ?>  
                                    </div>
                                </tr>                
                        </table>
            <!------ sino, quiere decir que tiene un registro de tabla y lo muestra ----->    
                           <!------ sino, quiere decir que tiene un registro de tabla y lo muestra ----->    
                                    <?php 
                                    } else{
                                        $sql=("SELECT * FROM tarjeta WHERE id_cliente = '$id_cliente'");
                                        $query=mysqli_query($conexion,$sql);
                                        $arreglo=mysqli_fetch_array($query);
                                        $puntos1 = $arreglo[2];
                                        $saldo1 = ($puntos1 * 0.10);
                                       
                                        echo "<table class='table table-sm table-striped'>";
                                       echo "<tr class='d-lg-flex'>";
                                       //JVG: Cambio del tag form al tag div, Agregado de tag label para enmarcar codigo tarjeta y puntos
                                       echo "<div>";
                                       echo "<td class='col-md'></td>";
                                       echo "<td class='col-md col-xl-2'><strong>Código de Tarjeta: </strong><label name='codTarjeta'>$arreglo[1]</label></td>";
                                       echo "<td class='col-md col-xl-2'><strong>Puntos Actuales: </strong><label name='currentPuntos'>$arreglo[2]</label></td>"; ?>
                                       <td class='col-md col-xl-2'><strong>Equivalente en soles:</strong><label name="equivalenteSoles"> <?php  echo number_format((float)$saldo1, 2, ',', ''); ?> </label></td>
                                     <td>
                                   <select class="custom-select css-input" onchange="elegirsaldo(this);" name="descrip" id="data" required>
                                       
                                   <option value="">Seleccione</option>
                                  <?php
                                   $registros  = "SELECT * FROM tarjeta_requisitos WHERE status = 'T'";
                                   $regis = mysqli_query($conexion, $registros);    
                                   while($tr = mysqli_fetch_array($regis)){
                                  ?>
                                  <option value='<?php echo $tr['descripcion'];?>' data-parametro2='<?php echo $tr['valor'];?>'><?php echo $tr['descripcion']?></option>
                                  <?php 
                                  } 
                                  ?>
                              </select></td>  
                                     <td ><input type='number' id="selvalor" class='form-control' name='puntos2' placeholder='Puntos ganados'></td>
                                    <input type="hidden" name="info_card" id="info_card">
                        
                                      <?php   
                                         echo "<td class='col-md'>
                                            <button onclick='sumarTarjeta()' class='btn btn-primary btn-sm' title='Sumar' name='sumar'><i class='fas fa-check'></i> Sumar puntos</button></td>";

                                        echo "<td class='col-md col-xl-2'>
                                    <button class='btn btn-primary btn-sm' type='button' data-toggle='collapse' data-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
                                      Historial
                                    </button></td>";
                                        echo "</div>";
                                         echo "</tr>";
                                        echo "</table>";
                                    
                            // Agregado de puntos 


                            /*
                                   if(isset($_POST["puntos2"])){
                                        $puntos2 = $_POST["puntos2"];
                                        $descrip= $_POST['descrip'];
                                         }

                                    if (isset($_POST["sumar"])){ 
                                    $puntos3 = ($puntos1 + $puntos2);
                                    $saldo3 = ($puntos3 * 0.10);

                                    $sql1 = "update tarjeta set puntos='$puntos3' where id_cliente='$arreglo[3]'";
                                    $query= mysqli_query($conexion,$sql1);

                                    $insercion= " INSERT INTO tarjeta_historial(tarjeta, id_cliente, descripcion, ingreso, total_puntos, saldo_total) values";
                                   
                                    if($puntos2<0){
                                        $sql2= " $insercion('$arreglo[1]', '$id_cliente', 'Uso de puntos', '$puntos2', '$puntos3', '$saldo3')";
                                        $query2= mysqli_query($conexion,$sql2);
                                    } else {
                                        $sql3= " $insercion('$arreglo[1]', '$id_cliente', '$descrip', '$puntos2', '$puntos3', '$saldo3')";
                                        $query3= mysqli_query($conexion,$sql3);

                                    }

                                       echo "<script>location.href='agendar.php?id_cliente=$id_cliente'</script>"; 
                                    }
                                    */
                                    ?>
                                  <div class="collapse" id="collapseExample">
                                    <div class="card card-body">
                                      
                                  <table class="table table-striped table-hover table-sm tabla-puntos ">
                                    <thead>
                                    <tr style="background-color:#bde0f4">
                                      <th> N° </th>
                                      <th> Descripción </th>
                                      <th> Puntos ganados </th>
                                      <th> Total Puntos </th>
                                      <th> Saldo en soles </th>
                                    </tr>
                                  </thead>
                                         <!-- JVG: Encerrado en un tbody el contenido de la tabla, para reemplazarlo con la funcion listar  -->
                                    <tbody id="tabla_tarjeta_historial">

                                  <?php 
                                    $CONSUL = "SELECT * FROM tarjeta_historial where id_cliente='$id_cliente';";
                                    $querycons = mysqli_query($conexion,$CONSUL);
                                    while ($histo = mysqli_fetch_array($querycons)) {
                                  
                                  ?>
                                    <tr>
                                      <td> <?php echo $histo['id']; ?></td>
                                      <td> <?php echo $histo['descripcion']; ?></td>
                                      <td> <?php echo $histo['ingreso']; ?></td>
                                      <td> <?php echo $histo['total_puntos']; ?></td>
                                      <td> <?php echo $histo['saldo_total']; ?></td>
                                    </tr>
                                
                                  <?php 
                                    } 
                                  ?>
                                  </tbody>
                                    </table>
                                     </div>
                                  </div>

                                <?php
                                    }
                                    mysqli_close($conexion);
                                }
                                ob_end_flush();
                                    ?>
                                </div>
                            </div>
                        </div><!-- DEPILCARD -->
            </div>
        </div>
    </body>

<script type="text/javascript" src="gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="gritter/gritter-conf.js"></script>

<script type="text/javascript">
        //$(document).ready(function () {
        // var unique_id = $.gritter.add({
           
        //     title: 'DepilZone Agenda',
          
        //     text: 'En esta sección podrás ver la información detallada del cliente, seguimiento de citas, zonas y la actividad con su DepilCard.',
        //     image: 'imagenes/logo.png',
        //     sticky: true,
        //     time: '',
        //     class_name: 'my-sticky-class'
        // });

        // return false;
        // });
 
    function elegirsaldo(sel){
        var saldo = $('option:selected', sel).data("parametro2");
        document.getElementById('selvalor').value = saldo;
      }

       //document.getElementById('selotro').required = true;
  </script>
</html>
