<?php
session_start();
date_default_timezone_get('');
ini_set('date.timezone', 'america/lima');

$fecha   = date("d/m/Y");
$tiempo  = date("g:i A");
$iduser = $_SESSION['id'];  

// $id_cli  = $_GET['id_cliente'];
// $id_tratamiento = $_GET['id_tratamiento'];
// $procedencia= $_GET['procedencia'];
if(isset($_GET['id_cita'])){
    $id_cita  = $_GET['id_cita'];

    include('conexionbd.php');    
    $sql ="SELECT c.id,c.id_sede,DATE_ADD(c.fecha, INTERVAL 2 DAY) fecha, c.hora, SUM(t.duracion) duracion,
    c.id_preferente, ctd.codigo tcita, ccd.codigo tcliente, cpd.codigo tpago, c.avisos, c.detalles, c.cod_cit_tipo, c.cod_cli_tipo,
    c.cod_pago_tipo, c.conf_ds, c.conf_ss, c.reprog, c.envio, c.motivo_no_tto, c.total, u.usuario, DATE_FORMAT(c.fech_ing, '%Y-%m-%d') fech_ing, 
    cli.nombrec, cli.apellidoc, cli.telefonoc, cli.historia, mpag.descripcion mod_pago, c.id_pag_mod, c.id_asistencia asistencia_cod, a.descripcion asistencia,  e.nombre, e.apellido
    FROM citas c left join tabla_deta_adelgaza t on t.id_cita=c.id, cit_tipo ctd, cli_tipo ccd, pag_tipo cpd, cliente cli, pag_modalidad mpag, cit_asistencia a, users u , especialistas e
    WHERE c.id = $id_cita
    AND c.cod_cit_tipo = ctd.codigo
    AND c.cod_cli_tipo = ccd.codigo 
    AND c.cod_pago_tipo = cpd.codigo                   
    AND c.id_cliente = cli.id
    AND c.id_pag_mod = mpag.id   
    AND c.us_ing = u.id
    AND c.id_especialista = e.id 
    AND a.codigo = c.id_asistencia
    GROUP by c.id";
    $resultado = mysqli_query($conexion,$sql);
    if($citas = mysqli_fetch_array($resultado)){
        $newFecha=$citas['fecha'];
        $newHora=$citas['hora'];
        $newDuracion= $citas['duracion'];
        $newTipoCliente=$citas['tcliente'];
        $newTipoCita=$citas['tcita'];
        $newTipoPago=$citas['tpago'];
        $newAvisos=$citas['avisos'];
        $newDetalles=$citas['detalles'];
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
    $newSede='0';
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
        
        <!-- <script type="text/javascript" src="js/citas.js"></script> -->
        <script type="text/javascript" src="js/custom.js"></script>
        
        <?php include('cit_datosPago.php');?>
    </head>

    <body>
        <?php
        /*echo "Esto es citas 1 ";*/
        
        // Trae los datos del cliente correspondiente a lo que viene de nuevaficha2
        $consultc   = "SELECT * FROM cliente WHERE id = '$id_cli'";
        $reconsultc = mysqli_query($conexion,$consultc);  
        $datosc     = mysqli_fetch_array($reconsultc);  
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
                    </div>
                </div>
            </h5> 
            
            <form class="needs-validation" name='form1' id='form1' action='guardarcita_adelgaza.php?id_cli=<?php echo $id_cli;?>&id_tratamiento=<?php echo $id_tratamiento;?>&procedencia=<?php echo $procedencia;?>' method='post' novalidate>
                <input type="hidden" name="confir_cit_ds" id="confir_cit_ds" value="0">
                <input type="hidden" name="confir_cit_ss" id="confir_cit_ss" value="0">
                <input type="hidden" name="id_tratamiento" id="id_tratamiento" value="<?php echo $id_tratamiento;?>">
                
                <div class="card-body pt-0 pb-2 scroll_cita">
                    
                    <div class="form-row mt-1">
                        <!-- <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Fecha</span>
                                </div>
                                <input type="date" class="form-control" name="fechac" required value=""> 
                                <div class="invalid-tooltip">Dato obligatorio.</div>
                            </div>
                        </div>
                        
                        <div class="form-group col-12 col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Hora Inicio</span>
                                </div>
                                <input type="time" class="form-control" name="horac" min="00:00:00" max="22:00:00" title="La hora debe ser en un rango de las 07:00 A 22:00" required value="">
                                <div class="invalid-tooltip">Dato obligatorio.</div>
                            </div>
                        </div> -->
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
                                <input type="time" class="form-control" name="horac" min="00:00:00" max="22:00:00" title="La hora debe ser en un rango de las 07:00 A 22:00" required>
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
                                <select name="tcita" class="custom-select custom-select-sm" required onchange="mostrar_monto()">
                                    <option value="">Seleccione</option>
                                    <?php
                                    $queryc = "SELECT * FROM cit_tipo WHERE status = 'T' and (codigo=1 or codigo=5)";
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
                                    <option value="<?php echo $citat['codigo']; ?>"><?php echo $citat['descripcion'];?></option>
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
                       
                        
                        <div class="form-group col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><small>Avisos Importantes</small></span>
                                </div>
                                <textarea rows="2" cols="125" class="form-control form-control-sm" name="avisos" placeholder="Avisos importantes con relacion al CLIENTE"><?php echo $newAvisos;?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><small>Detalles</small></span>
                                </div>
                                <textarea rows="2" cols="125" class="form-control form-control-sm" name="detalles" placeholder="Agregar detalles de promocion,cita u otros"></textarea>
                            </div>
                        </div>
                      
                    </div><!--- div row -->
                    <script>
                        var datos_tabla=[];
                        function llenarDatosTabla(){                           
                            //obtiene los datos que se pintan al cargar la pagina con el php para almacenarlos en la variable datos_tabla, y asi seguir manejandolo como en citas_adelgaza.
                            var filas=document.getElementById('tabla_deta_adel').querySelectorAll('tr');
                            //console.log(filas);
                            for (let index = 0; index < filas.length; index++) {
                                const element = filas[index];
                                var id_deta_adel = element.querySelector('input[name="datos_deta_adel['+index+'][id_deta_adel]"]').value;
                                var nombre_deta_adel = element.querySelector('td[name="datos_deta_adel['+index+'][nombre_deta_adel]"]').innerText;
                                var id_sesion = element.querySelector('input[name="datos_deta_adel['+index+'][id_sesion]"]').value;
                                var nombre_sesion = element.querySelector('td[name="datos_deta_adel['+index+'][nombre_sesion]"]').innerText;
                                var duracion = element.querySelector('input[name="datos_deta_adel['+index+'][duracion]"]').value;
                                datos_tabla.push({id_deta_adel,nombre_deta_adel,id_sesion,nombre_sesion,duracion});
                            }
                            console.log(datos_tabla);
                            // pintarTabla();
                        }
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
                                        window.top.carga_show_hide();
                                    }
                                    
                                    form.classList.add('was-validated');
                                }, false);
                            });
                            llenarDatosTabla();
                        }, false);
                        function cambiar_status_pag() {
                            if($("#tpago").val() == '2'){
                                $("#tpago").removeClass("color_status_pag").addClass("color_status_pag2");
                            }
                            else if($("#tpago").val() == '3'){
                                $("#tpago").removeClass("color_status_pag2").addClass("color_status_pag");
                            }
                            else{
                                $("#tpago").removeClass("color_status_pag2 color_status_pag");
                            }
                        };
                        
                        function mostrar_monto(){
                            var inputMontoContainer=document.getElementById('monto_container');
                            document.getElementById('monto_total').value=0;
                            if(document.getElementsByName('tcita')[0].value==1){
                                inputMontoContainer.style.display='block';
                            }else{
                                inputMontoContainer.style.display='none';
                            }
                            
                            
                        }
                        function add_deta_adel(){
                            
                            var cmb_deta_adel = document.getElementById('cmb-deta-adel');
                            var id_deta_adel=cmb_deta_adel.value;
                            var nombre_deta_adel = cmb_deta_adel.options[cmb_deta_adel.selectedIndex].innerText;
                            var cmb_sesion = document.getElementById('cmb-sesion');
                            var id_sesion=cmb_sesion.value;
                            var nombre_sesion = cmb_sesion.options[cmb_sesion.selectedIndex].innerText;
                            var duracion=document.getElementById('duracion').value;
                            if(id_deta_adel==''||id_sesion==''){
                                return;
                            }
                            var repetido=false;
                            for (let index = 0; index < datos_tabla.length; index++) {
                                const element = datos_tabla[index];
                                if(element.id_deta_adel==id_deta_adel){
                                    repetido=true
                                }
                            }
                            if(repetido){
                                return;
                            }
                            datos_tabla.push({id_deta_adel,nombre_deta_adel,id_sesion,nombre_sesion,duracion});
                            pintarTabla();
                            
                            //console.log(output);
                        };
                        function pintarTabla(){
                            var output='';
                            for (let index = 0; index < datos_tabla.length; index++) {
                                const fila = datos_tabla[index];
                                output+=`<tr>
                                <td class="py-0">${index + 1}</td>
                                <td class="d-none"><input name="datos_deta_adel[${index}][id_deta_adel]" value="${fila.id_deta_adel}"></td><td>${fila.nombre_deta_adel}</td>
                                <td class="d-none"><input name="datos_deta_adel[${index}][id_sesion]" value="${fila.id_sesion}"></td><td>${fila.nombre_sesion}</td>
                                <td class="d-none"><input name="datos_deta_adel[${index}][duracion]" value="${fila.duracion}"></td><td>${fila.duracion} min.</td><td>
                                <button type="button" class="btn btn-link btn-hover2 py-0" name="eliminar" onclick="remove_deta_adel(${index})" title="Eliminar"><i class="fas fa-times-circle fa-lg"></i></button></td></tr>`;
                            }
                            document.getElementById('tabla_deta_adel').innerHTML=output;
                        }
                        function remove_deta_adel(ind){
                            datos_tabla.splice(ind,1);
                            pintarTabla();
                        }
                        function limpiar_tabla(){
                            datos_tabla=[];
                            pintarTabla();
                        }
                    </script>
                    
                    
                    <!-- **********************DETALLES ADELGAZA DE LA CITA***************** -->
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
                                    
                                    <!---------------formulario de zonas a registrar en la cita ------------------------>
                                    <div class="form-group row pb-0">
                                        <!--Ingreso zonas -->
                                        <label class="col-form-label col-form-label-sm col-auto pr-0">Sub tratamiento</label>
                                        <div class="col-12 col-sm-3 col-md-4">
                                            <select class="custom-select custom-select-sm css-input" id="cmb-deta-adel" name="id_deta_adel">
                                                <option value="">Seleccione</option>
                                                <?php
                                                $registros  = "SELECT * FROM detalles_adelgaza WHERE status = 'T'";
                                                $regisDetaAdel = mysqli_query($conexion, $registros);    
                                                while($z = mysqli_fetch_array($regisDetaAdel)){
                                                ?>
                                                <option value='<?php echo $z['id'];?>'><?php echo mb_detect_encoding($z['nombre'], 'UTF-8',true)?$z['nombre']:utf8_encode($z['nombre'])?></option>
                                                <?php 
                                                } 
                                                ?>
                                            </select>
                                        </div>
                                        
                                        
                                        <label class="col-form-label col-form-label-sm col-auto px-sm-0">Sesión</label>
                                        <div class="col-12 col-sm-2">
                                            <select class="custom-select custom-select-sm css-input" id="cmb-sesion" name="id_sesion">
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
                                        
                                        
                                        <!-- <label class="col-form-label col-form-label-sm col-auto px-sm-0">Precio</label>
                                        <div class="col-12 col-sm-1">
                                            <input type='number' class="form-control form-control-sm css-input" id='precio' placeholder="0.00">
                                        </div> -->
                                        
                                        
                                        <label class="col-form-label col-form-label-sm col-auto px-sm-0" >Duración</label>
                                        <div class="col-12 col-sm-1">
                                            <input type='number' class="form-control form-control-sm css-input" name="duracion" id='duracion' value="30" style="min-width:50px">
                                        </div>
                                        
                                        <input type='hidden' id='preferente' value="<?php echo $iduser;?>">
                                        
                                        
                                        <button type="button" class="btn btn-link btn-hover1 p-sm-0" title="Insertar" onclick="add_deta_adel()"><i class="fas fa-plus-circle fa-lg"></i></button>
                                    </div>
                                    
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-hover" id="tblDatos">
                                            <thead class="css-blue">
                                                <tr class="head_tab">
                                                    <th>N°</th>
                                                    <th class="col-5">Sub-tratamiento</th>
                                                    <th>Sesión</th>
                                                    <th>Duración</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="tabla_deta_adel">
                                                <?php 
                                                if($id_cita!==null){
                                                    $sql2 = "SELECT t.id_deta_adelgaza,d.nombre,t.id_sesion,s.sesion,t.duracion
                                                            FROM tabla_deta_adelgaza t inner join  detalles_adelgaza d on d.id=t.id_deta_adelgaza inner join sesiones s on s.id=t.id_sesion
                                                            WHERE t.id_cita = $id_cita";
                                                    $result2 = mysqli_query($conexion,$sql2);
                                                    $ind=0;
                                                    while($con = mysqli_fetch_array($result2)){
                                                ?>
                                    
                                                <tr>   
                                                    <td class="py-0"><?php echo ($ind+1); ?></td>
                                                    <td class="d-none"><input name="datos_deta_adel[<?php echo $ind; ?>][id_deta_adel]" value="<?php echo $con["id_deta_adelgaza"];?>"></td>
                                                    <td name="datos_deta_adel[<?php echo $ind; ?>][nombre_deta_adel]"><?php echo mb_detect_encoding($con['nombre'], 'UTF-8',true)?$con['nombre']:utf8_encode($con['nombre']);?></td>
                                                    <td class="d-none"><input name="datos_deta_adel[<?php echo $ind; ?>][id_sesion]" value="<?php echo $con["id_sesion"]+1;?>"></td>
                                                    <td name="datos_deta_adel[<?php echo $ind; ?>][nombre_sesion]"><?php echo $con["sesion"]+1;?></td>
                                                    <td class="d-none"><input name="datos_deta_adel[<?php echo $ind; ?>][duracion]" value="<?php echo $con["duracion"];?>"></td><td><?php echo $con["duracion"];?> min.</td><td>
                                                    <button type="button" class="btn btn-link btn-hover2 py-0" name="eliminar" onclick="remove_deta_adel(<?php echo $ind; ?>)" title="Eliminar"><i class="fas fa-times-circle fa-lg"></i></button></td></tr>
                                                </tr>
                                                <?php 
                                                    $ind++;
                                                    } ;
                                                }   
                                                ?>
                                            </tbody>
                                            
                                         </table>
                                    </div><!-- div responsive table -->
                                
                                </div><!-- card-body -->
                            </div>
            
                        </div><!-- card -->
                    </div><!-- acordeon -->                    
                    
                    <div id="monto_container" style="border:0px;padding:0px;width:100%;display:none">
                        <div class="row justify-content-sm-end">
                            <div class="col-auto pr-1">
                                <label class="col-form-label col-form-label-sm">Total</label>
                            </div>
                            
                            <div class="col-12 col-sm-3 col-md-2">
                            
                                <div class="input-group input-group-sm">
                                    
                                    <input type="text" class="form-control form-control-sm" name="monto_total" id="monto_total" value="0" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">S/</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- card-body -->
                
                <div class="card-footer">  
                    <center>
                        <button type="submit" class="btn btn-sm btn-primary" name="guardar" title="Guardar"><i class="fas fa-check"></i> Guardar</button>
                        <button type="reset" class="btn btn-sm btn-primary" title="Limpiar" onclick="limpiar_tabla()"><i class="fas fa-redo-alt"></i> Limpiar</button>
                    </center>
                        
                </div><!-- card-footer -->
            
            
            </form>
        </div><!-- card -->
        

        <a href="agendar.php?id_cliente=<?php echo $id_cli;?>&id_usuario=<?php echo $iduser;?>" class="fixed-bottom posicion_bot_atras" title="Regresar" onclick="window.top.carga_show_hide()"><i class="fas fa-arrow-circle-left fa-3x bot_sesion ml-3" title="Regresar"></i></a>
        
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
        btn2.addClass("d-none");
        btn3.addClass("d-none");
    })();
    
</script>