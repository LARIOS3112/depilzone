<?php
session_start();
date_default_timezone_get('');
ini_set('date.timezone', 'america/lima');

$fecha   = date("Y-m-d");
$tiempo  = date("g:i A");
$iduser = $_SESSION['id'];  

if(isset($_GET['id_cita'])){
    $id_cita  = $_GET['id_cita'];

    include('conexionbd.php');
    $sql  ="SELECT c.id, DATE_ADD(c.fecha, INTERVAL 2 MONTH) fecha,c.hora, SUM(t.duracion)duracion,
                    c.id_preferente, ctd.codigo tcita, ccd.codigo tcliente, cpd.codigo tpago, c.avisos, c.detalles, c.cod_cit_tipo, c.cod_cli_tipo,
                    c.cod_pago_tipo, c.conf_ds, c.conf_ss, c.reprog, c.envio, c.motivo_no_tto, c.total,c.id_sede, u.usuario, DATE_FORMAT(c.fech_ing, '%Y-%m-%d') fech_ing, 
                    cli.nombrec, cli.apellidoc, cli.telefonoc, cli.historia,cli.id id_cliente, mpag.descripcion mod_pago, c.id_pag_mod, c.id_asistencia asistencia_cod, a.descripcion asistencia,  e.nombre, e.apellido
                FROM citas c, tabla_zonas t, cit_tipo ctd, cli_tipo ccd, pag_tipo cpd, cliente cli, pag_modalidad mpag, cit_asistencia a, users u , especialistas e
                WHERE c.id = '$id_cita'
                AND c.cod_cit_tipo = ctd.codigo
                AND c.cod_cli_tipo = ccd.codigo 
                AND c.cod_pago_tipo = cpd.codigo 
                AND t.id_cita = c.id
                AND c.id_cliente = cli.id
                AND c.id_pag_mod = mpag.id   
                AND c.us_ing = u.id
                AND c.id_especialista = e.id 
                AND a.codigo = c.id_asistencia";

    $resultado = mysqli_query($conexion,$sql);
    if($citas = mysqli_fetch_array($resultado)){
        $newFecha=$citas['fecha'];
        $newHora=$citas['hora'];
        $newDuracion= $citas['duracion'];
        $newTipoCliente=$citas['tcliente'];
        $newTipoCita=$citas['tcita'];
        $newTipoPago=$citas['tpago'];
        $newAvisos=$citas['avisos'];;
        $newDetalles='';
        $newSede=$citas['id_sede'];
    }
}else{
    $id_cita  = null;
    $newFecha=$fecha;
    $newHora='08:00';
    $newDuracion= 0;
    $newTipoCliente='';
    $newTipoCita='';
    $newTipoPago='';
    $newAvisos='';
    $newDetalles='';
    $newSede='1';
}
$id_cli  = $_GET['id_cliente'];
$procedencia = $_GET['procedencia'];
$id_tratamiento = $_GET['id_tratamiento'];
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
        
        <script type="text/javascript" src="js/citas.js?v=2"></script>
        <script type="text/javascript" src="js/custom.js"></script>
        
        <?php include('cit_datosPago.php');?>
    </head>
    <style>
        .alter-chb{
            display:none
        }
        #chb-envio{
            color:#cccccc;
            cursor:pointer
        }
        
    </style>
    <script>
        function onchangeEnvio(e){
            if(e.checked){
                document.getElementById('chb-envio').style.color='#007bff';
                e.value="Envio sms y wsp"              
            }else{
                document.getElementById('chb-envio').style.color='#cccccc';
                e.value="0"
            }            
        }
    </script>
    <body>
        <?php
        /*echo "Esto es citas 1 ";*/
        
        // Trae los datos del cliente correspondiente a lo que viene de nuevaficha2
        $consultc   = "SELECT * FROM cliente WHERE id = '$id_cli'";
        $reconsultc = mysqli_query($conexion,$consultc);  
        $datosc     = mysqli_fetch_array($reconsultc);  
        $newNotas=$datosc['notas'];
        do{ 
        ?>
        
        <div class="card css-cuadroc">
            <h5 class="card-header bg-sistema text-capitalize pb-0">
                <div class="row justify-content-between">
                    <div class="col-9">
                        Cita: <i class="fas fa-user"></i> <?php echo $datosc['nombrec']."  ".$datosc['apellidoc']."  ".$datosc['historia'];?> (<small><i class="fas fa-mobile-alt"></i> <?php echo $datosc['telefonoc'];?></small>)
                    </div>
    
                    <div class="d-flex justify-content-end height_div">
                        <button type="button" class="btn btn-link px-0 py-0 btn-hover1 boton_conf1" title="Confirmó cita">
                            <div class="fa-2x py-0">
                                <span class="fa-layers fa-fw bot_position">
                                    <i class="fas fa-circle"></i>
                                    <i class="fa-inverse fas fa-check" data-fa-transform="shrink-4"></i>
                                </span>
                            </div>
                        </button>
                        
                        <button type="button" class="btn btn-link px-0 py-0 btn-hover2 boton_conf2" title="No confirmó cita">
                            <div class="fa-2x py-0">
                                <span class="fa-layers fa-fw bot_position">
                                    <i class="fas fa-circle"></i>
                                    <i class="fa-inverse fas fa-times" data-fa-transform="shrink-4"></i>
                                </span>
                            </div>
                        </button>
                        
                        <button type="button" class="btn btn-link px-0 py-0 btn-hover3 boton_conf3" title="Confirmar cita en una semana">
                            <div class="fa-2x py-0">
                                <span class="fa-layers fa-fw bot_position">
                                    <i class="fas fa-circle"></i>
                                    <span class="fa-layers-text fa-inverse" data-fa-transform="shrink-4 up-1" style="font-weight:900">SS</span>
                                </span>
                            </div>
                        </button> 
                        <button type="button" class="btn btn-link px-0 py-0 btn-hover" title="No contestó llamada" > 
                            <div class="fa-2x py-0">
                                <label for="envio">                                  
                                <span class="fa-layers fa-fw bot_position" id="chb-envio">
                                    <i class="fas fa-circle"></i>
                                    <i class="fa-inverse fas fa-phone" data-fa-transform="shrink-4"></i>
                                    <i class="fa-inverse fas fa-times" data-fa-transform="shrink-9 up-3.2 left-3"></i>
                                </span>
                                </label>    
                            </div>                            
                        </button>                           
                        
                    </div>
                </div>
            </h5> 
           
            <form class="needs-validation" name='form1' id='form1' action='guardarcita.php?id_cli=<?php echo $id_cli;?>&procedencia=<?php echo $procedencia; ?>&id_tratamiento=<?php echo $id_tratamiento;?>' method='post' novalidate>
                <input type="hidden" name="confir_cit_ds" id="confir_cit_ds" value="0">
                <input type="hidden" name="confir_cit_ss" id="confir_cit_ss" value="0">
                <input type="checkbox" name="envio" id="envio" class="alter-chb" value="0" onchange="onchangeEnvio(this)">
                
                <div class="card-body pt-0 pb-2 scroll_cita">
                    
                    <div class="form-row mt-1">
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Fecha</span>
                                </div>
                                <input type="date" class="form-control" name="fechac" required>
                                <div class="invalid-tooltip">Dato obligatorio.</div>
                            </div>
                        </div>
                        
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Hora Inicio</span>
                                </div>
                                <input type="time" class="form-control" name="horac" value="<?php echo $newHora;?>" min="00:00:00" max="22:00:00" title="La hora debe ser en un rango de las 07:00 A 22:00" required>
                                <div class="invalid-tooltip">Dato obligatorio.</div>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Sede</span>
                                </div>                                
                                <select  class="form-control" name="id_sede" title="Lugar donde se atenderá al cliente." required>
                                    <option value="1" <?php echo $newSede=='1'?'selected':''; ?>>San Borja</option>
                                    <option value="2" <?php echo $newSede=='2'?'selected':''; ?>>Megaplaza</option>
                                </select>
                                <div class="invalid-tooltip">Dato obligatorio.</div>
                            </div>
                        </div>
                    </div>
                    <?php 
        }
        while($datosc = mysqli_fetch_array($reconsultc));         
         ?>
            
        
                    <div class="form-row">
                        <!-------------------------select tipo de cliente ------------------>
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Tipo de Cliente</label>
                                </div>
                                <select name="tcliente" class="custom-select custom-select-sm" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    include('conexionbd.php');
                                    $tipos = "SELECT * FROM cli_tipo WHERE status = 'T'";
                                    $tiposcli = mysqli_query($conexion,$tipos);    
                                
                                    while($clientet = mysqli_fetch_array($tiposcli)){
                                    ?>
                                    <option value="<?php echo $clientet['codigo']; ?>" <?php echo $newTipoCliente==$clientet['codigo']?'selected':''  ?>><?php echo $clientet['descripcion'];?></option>
                                    <?php 
                                    } 
                                    ?>
                                </select>
                                <div class="invalid-tooltip">Dato obligatorio.</div>
                            </div>
                        </div>
                        
                       
                        <!------------------------select tipo de cita --------------------->
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Tipo de Cita</label>
                                </div>
                                <select name="tcita" class="custom-select custom-select-sm" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $queryc = "SELECT * FROM cit_tipo WHERE status = 'T'";
                                    $conect = mysqli_query($conexion,$queryc);    
                                
                                    while($citat = mysqli_fetch_array($conect)){
                                    ?>
                                    <option value="<?php echo $citat['codigo']; ?>" <?php echo $newTipoCita==$citat['codigo']?'selected':''  ?>><?php echo $citat['descripcion'];?></option>
                                    <?php 
                                    } 
                                    ?>
                                </select>
                                <div class="invalid-tooltip">Dato obligatorio.</div>
                            </div>
                        </div>
                        
             
                        <!---------------------------select status de pago -------------------->
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Status de Pago</label>
                                </div>
                                <select name="tpago" id="tpago" class="custom-select custom-select-sm" onchange="cambiar_status_pag()" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    $queryc = "SELECT * FROM pag_tipo WHERE status = 'T'";
                                    $conect = mysqli_query($conexion,$queryc);    
                                
                                    while($citat = mysqli_fetch_array($conect)){
                                    ?>
                                    <option <?php echo ($citat['codigo']=="1"?"selected":""); ?> value="<?php echo $citat['codigo']; ?>" <?php //echo $newTipoPago==$citat['codigo']?'selected':''  ?>><?php echo $citat['descripcion'];?></option>
                                    <?php 
                                    } 
                                    ?>
                                </select>
                                <div class="invalid-tooltip">Dato obligatorio.</div>
                            </div>
                        </div>
                        
                        
                        <!--<div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Status de Pago</span>
                                </div>
                                <input type="text" class="form-control" id="tpago_desc" value="Pendiente" disabled>
                                <input type="hidden" name="tpago" id="tpago" value="1">
                            </div>
                        </div> --> 
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><small>Notas</small></span>
                                </div>
                                <textarea rows="4"  class="form-control form-control-sm" name="notas" placeholder="Notas persistentes"><?php echo $newNotas;?></textarea>
                            </div>
                        </div>
                        
                        
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><small>Avisos </small></span>
                                </div>
                                <textarea rows="4"  class="form-control form-control-sm" name="avisos" placeholder="Avisos importantes con relacion al CLIENTE"><?php echo $newAvisos;?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><small>Detalles</small></span>
                                </div>
                                <textarea rows="4" class="form-control form-control-sm" name="detalles" placeholder="Agregar detalles de promocion,cita u otros"><?php echo $newDetalles;?></textarea>
                            </div>
                        </div>
                      
                    </div><!--- div row -->
                
                    
                    
                    <!-- **********************ZONAS DE LA CITA***************** -->
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header bg-sistema hover_collapse py-0" id="headingOne">
                                <h5 class="my-0">
                                    <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapseOne" style="margin-left: 0px;" aria-expanded="true" aria-controls="collapseOne"><h5 class="my-0">Servicios</h5>
                                    </button>
                                </h5>
                            </div>
                            
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body pt-1 px-1">
                                    <?php 
                                        $listUsers=array();
                                        if($resListusers=mysqli_query($conexion,"select id,nombre,usuario from users where status='T' and tipo in (4,6,2,7,8) order by nombre")){
                                            while($rowResListUsers=$resListusers->fetch_object()){
                                                $newRowResListUsers=array();                                                        
                                                $listUsers[]=$rowResListUsers;
                                            }
                                        }
                                    ?>
                                    <!---------------formulario de zonas a registrar en la cita ------------------------>
                                    <div class="form-group row pb-0">
                                        <label class="col-form-label col-form-label-sm col-auto pr-0">Promoción</label>
                                        <div class="col-8 col-sm-3">
                                            <select class="custom-select custom-select-sm css-input" id="promo" onchange="mostraridpromo(this);">
                                                <option value="">Seleccione</option>
                                                <?php
                                                $sqlPromo  = "SELECT * FROM promociones WHERE status = 'T'";
                                                $resPromo = mysqli_query($conexion, $sqlPromo);    
                                                while($promo = mysqli_fetch_array($resPromo)){
                                                ?>
                                                <option value='<?php echo $promo['descripcion'];?>' data-idpromo='<?php echo $promo['id'];?>'><?php echo $promo['descripcion']?></option>
                                                <?php 
                                                } 
                                                ?>
                                            </select>
                                            <input type="hidden" id="id_promo">
                                        </div>
                                        <!--Ingreso zonas -->
                                        <label class="col-form-label col-form-label-sm col-auto pr-0">Zona</label>
                                        <div class="col-8 col-sm-3">
                                            <select class="custom-select custom-select-sm css-input" id="zona" onchange="mostrarid(this);">
                                                <option value="">Seleccione</option>
                                                <?php
                                                $registros  = "SELECT * FROM zonas WHERE status = 'T'";
                                                $regiszonas = mysqli_query($conexion, $registros);    
                                                while($z = mysqli_fetch_array($regiszonas)){
                                                ?>
                                                <option value='<?php echo $z['partes'];?>' data-idzona='<?php echo $z['id'];?>'><?php echo $z['partes']?></option>
                                                <?php 
                                                } 
                                                ?>
                                            </select>
                                            <input type="hidden" id="id_zona">
                                        </div>
                                        
                                        
                                        <label class="col-form-label col-form-label-sm col-auto px-sm-0">Sesión</label>
                                        <div class="col-12 col-sm-2">
                                            <select class="custom-select custom-select-sm css-input" id="sesion">
                                                <option value="">Seleccione</option>
                                                <?php 
                                                $consu = "SELECT * FROM sesiones WHERE status = 'T'";
                                                $resp  = mysqli_query($conexion, $consu);
                                                
                                                while($answer = mysqli_fetch_array($resp)){
                                                ?>
                                                   
                                                <option value="<?php echo $answer['id'];?>"><?php echo $answer['sesion'];?></option>
                                                <?php 
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        
                                        <label class="col-form-label col-form-label-sm col-auto px-sm-0">Precio</label>
                                        <div class="col-12 col-sm-2">
                                            <input type='number' class="form-control form-control-sm css-input" id='precio' placeholder="0.00">
                                        </div>
                                        
                                        
                                        <label class="col-form-label col-form-label-sm col-auto px-sm-0">Duración</label>
                                        <div class="col-12 col-sm-2">
                                            <input type='number' class="form-control form-control-sm css-input" id='duracion' placeholder="0">
                                        </div>

                                        <label class="col-form-label col-form-label-sm col-auto px-sm-0">Agendado/Guardado por</label>
                                        <div class="col-12 col-sm-2">
                                            <select class="custom-select custom-select-sm css-input" id="agendado_por">
                                                <option value="">Sin asignar</option>
                                                <?php                                                    
                                                    foreach ($listUsers as $rowUser) {                               
                                                        echo "<option value='".($rowUser->id)."' ".(($rowUser->id)==$iduser?'selected':'').">".($rowUser->nombre)."</option>";
                                                    }
                                                ?>
                                            </select>

                                        </div>
                                        <input type='hidden' id='preferente' value="<?php echo $iduser;?>">
                                        
                                        
                                        <button type="button" class="btn btn-link btn-hover1 p-sm-0" title="Insertar" onclick="add_zona()"><i class="fas fa-plus-circle fa-lg"></i></button>
                                    </div>
                                    
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-hover" id="tblDatos">
                                            <thead class="css-blue">
                                                <tr class="head_tab">
                                                    <th>N°</th>                                
                                                    <th class="col-4">Zona</th>  
                                                    <th>Promoción</th>                                                     
                                                    <th>Sesión</th>
                                                    <th>Precio</th>
                                                    <th>Duración</th>
                                                    <th>Agendado/Guardado por</th>                                                    
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="tabla">
                                            <?php 
                                            if($id_cita!==null){
                                                $sql2 = "SELECT t.id_zona, s.sesion, t.duracion, t.precio, t.igv, t.subtotal, z.partes, p.descripcion,p.id id_promo
                                                        FROM tabla_zonas t left join promociones p on p.id=t.id_promo, zonas z, sesiones s 
                                                        WHERE t.id_cita = $id_cita
                                                            AND t.id_zona = z.id
                                                            AND t.sesion = s.id";
                                                $result2 = mysqli_query($conexion,$sql2);
                                                //$con     = mysqli_fetch_array($result2);
                                                $conta=0;
                                                while($con = mysqli_fetch_array($result2)){
                                                    
                                                ?>     
                                                    <tr>
                                                        <td class="py-0"><?php echo $conta+1;?></td>
                                                        <td class="d-none"><input name="datos_zona['<?php echo $conta;?>'][id_zona]" value="<?php echo $con['id_zona'];?>"></td>
                                                        <td class="py-0 col-5"><?php echo $con["partes"];?></td>
                                                        <td class="d-none"><input name="datos_zona['<?php echo $conta;?>'][id_promo]" value="<?php echo $con['id_promo'];?>"></td>
                                                        <td class="py-0 col-5"><?php echo $con["descripcion"]==null?'Sin asignar':$con["descripcion"];?></td>
                                                        <td class="py-0"><input type="number" class="form-control" style="padding:0px;height:fit-content;width:40px" onkeydown="return false" name="datos_zona['<?php echo $conta;?>'][sesion]" value="<?php echo ($con["sesion"]+1>10?1:($con["sesion"]+1));?>"/></td>
                                                        <td class="py-0"><input onchange="CalcularTotal()" type="number" class="form-control" style="padding:0px;height:fit-content;width:60px" name="datos_zona['<?php echo $conta;?>'][precio]" value="<?php echo ($con["sesion"]+1>10?0:($con["precio"]));?>"/></td>
                                                        <td class="py-0"><input type="hidden" name="datos_zona['<?php echo $conta;?>'][duracion]" value="<?php echo $con["duracion"];?>"/><?php echo $con["duracion"];?></td>
                                                        <td class="d-none"><input type="hidden" name="datos_zona['<?php echo $conta;?>'][igv]" value="<?php echo $con["igv"]; ?>"/><?php echo $con["igv"]; ?></td>
                                                        <td class="d-none"><input type="number" disabled class="form-control" style="padding:0px;height:fit-content;width:60px"  name="datos_zona['<?php echo $conta;?>'][valor]" value="<?php echo $con["subtotal"];?>"/></td>
                                                        <td class="d-none"><input type="hidden" name="datos_zona['<?php echo $conta;?>'][id_pref]" value="<?php echo $iduser;?>"></td>
                                                        <td class="py-0">
                                                            <select disabled name="datos_zona['<?php echo $conta;?>'][id_agend]" style="padding:0px;height:fit-content;width:140px">
                                                                <option value="">-- Sin asignar --</option>
                                                                <?php 
                                                                    foreach ($listUsers as $rowUser) {
                                                                        
                                                                        echo "<option value='".($rowUser->id)."'>".($rowUser->nombre)."</option>";
                                                                    }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td class="py-0"><button type="button" class="btn btn-link btn-hover2 py-0" name="eliminar" onclick="borrar(this, '<?php echo $conta;?>')" title="Eliminar"><i class="fas fa-times-circle fa-lg"></i></button></td>
                                                    </tr>
                                                    
                                                <?php 
                                                    $conta++;
                                                };
                                            }
                                            ?>
                                            
                                            </tbody>
                                            
                                         </table>
                                    </div><!-- div responsive table -->
                                
                                </div><!-- card-body -->
                            </div>
            
                        </div><!-- card -->
                    </div><!-- acordeon -->

                    <div class="row justify-content-sm-end">
                        <div class="col-auto pr-1">
                            <label class="col-form-label col-form-label-sm">Total</label>
                        </div>
                        
                         <div class="col-12 col-sm-3 col-md-2">
                             <div class="input-group input-group-sm">
                                 <input type="text" class="form-control form-control-sm" name="monto_total" id="monto_total" value="0" readonly required>
                                 <div class="input-group-append">
                                     <span class="input-group-text">S/</span>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div><!-- card-body -->
                <script>
                    CalcularTotal();
                    getZonasPrevias();
                </script>
            
                <div class="card-footer">  
                    <center>
                        <button type="submit" class="btn btn-sm btn-primary" name="guardar" title="Guardar"><i class="fas fa-check"></i> Guardar</button>
                        <button type="reset" class="btn btn-sm btn-primary" title="Limpiar" onclick="limpiar_zonas()"><i class="fas fa-redo-alt"></i> Limpiar</button>
                    </center>
                        
                        
                        
 
                    <!--<div class="row justify-content-end">
                        <div class="col-12 col-sm-8 col-md-4">
                            <div class="row justify-content-sm-center">
                                <button type="submit" class="btn btn-primary" name="guardar" title="Guardar"><i class="fas fa-check"></i> Guardar</button>
                                <button type="reset" class="btn btn-primary" title="Limpiar" onclick="limpiar_zonas()"><i class="fas fa-redo-alt"></i> Limpiar</button>
                            </div>
                        </div>
                        
                        
                        <div class="col-12 col-sm-4">
                            <div class="row justify-content-sm-end">
                                <button type="button" class="btn btn-primary" title="Datos de Pago" data-toggle="modal" data-target="#modalPago"><i class="fas fa-money-check-alt "></i> Datos de Pago</button>
                            </div>
                        </div>
                    </div>-->
                    
                </div><!-- card-footer -->
            
            
            </form>
        </div><!-- card -->
        

        <!-- <a href="agendar.php?id_cliente=<?php //echo $id_cli;?>&id_usuario=<?php //echo $iduser;?>" class="fixed-bottom posicion_bot_atras" title="Regresar" onclick="window.top.carga_show_hide()"><i class="fas fa-arrow-circle-left fa-3x bot_sesion ml-3" title="Regresar"></i></a> -->
        
    </body> 
</html>



<script>
    // activar botones de confirmacion de cita
    (function(){ 
        var btn1 = $(".boton_conf1"); 
        var btn2 = $(".boton_conf2"); 
        var btn3 = $(".boton_conf3"); 

        // icono no confirmó cita
        btn1.addClass("d-none");
        btn2.removeClass("d-none");
        btn3.addClass("d-none");
    })();

</script>