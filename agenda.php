<?php
session_start();
date_default_timezone_get('');
ini_set('date.timezone', 'america/lima');
$hoy    = date("Y/m/d");
$tiempo = date("H:i:s");
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
        <link rel="stylesheet" type="text/css" href="gritter/css/jquery.gritter.css" />
        
        <script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="lib/popper.min.js"></script>
        <script type="text/javascript" src="lib/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="lib/fontawesome/js/all.js"></script>
        <script src="js/custom.js"></script>
    </head>
    <script>
        var historias=[];

        function formato(texto){
            return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$2/$3/$1');
        }
        function listarCitas() {           
            var fecha = document.getElementById('filtroFecha').value;           
            var id_tto = document.getElementById('cmb-tto').value;
            var id_sede = document.getElementById('cmb-sede').value;
            var id_asistencia = document.getElementById('cmb-asistencia').value;
            var conf_ds = document.getElementById('cmb-etapa').value;
            var tipo_cita=document.getElementById('cmb-tipo-cita').value;
            document.getElementById('datos').innerHTML='Buscando...';
            document.getElementById('btn-buscar').disabled=true;            
            document.getElementById('fecha_filtrada').innerHTML=`<i class="fas fa-calendar-alt fa-lg"></i> ${new Date(formato(fecha)).toLocaleDateString("Es-es", {year: "numeric", month: "short", day: "numeric"})}`; 
            var ajax_url='agendaBuscar.php';       
            var params='fecha='+fecha+'&id_tto='+id_tto+'&id_sede='+id_sede+'&id_asistencia='+id_asistencia+'&conf_ds='+conf_ds+'&tipo_cita='+tipo_cita;  
            ajax_url+='?' + params;
            var ajax_request= new XMLHttpRequest();
            ajax_request.onreadystatechange = function(){
                if(ajax_request.readyState==4){                
                    var response = JSON.parse(ajax_request.responseText);
                    var output='';
                    if(response.success){
                        var citas_array=response.data.citas;
                        if(id_tto==''){
                            citas_array.sort((x,y)=>{
                            return ((x.hora == y.hora) ? 0 : ((x.hora > y.hora) ? 1 : -1 ));
                            });
                        }    
                        historias=[];
                        for (let index = 0; index < citas_array.length; index++) {
                            const cita = citas_array[index];
                            if(cita.id_asistencia==1&&cita.historia!=""){
                                historias.push({historia:cita.historia,conf_ds:cita.conf_ds,cliente:cita.nombrec});
                            }
                            //console.log(cita.color_pgt)
                            //SI LA CITA TIENE STATUS REALIZADA DEBE DIRIGIR A CITAS 4!!!!!!!!!!!!!
                            if (cita.id_asistencia==1) {
                                output+='<a title="'+cita.asignado_a+'" href="citas3'+cita.tto_url+'.php?id_ci='+cita.id_ci+'&id_cli='+cita.id_cli+'&procedencia=agenda&id_tratamiento='+cita.tto_id+'" target="_blank"  onmouseover="window.status='+"''"+'; return true;" class="list-group-item list-group-item-action flex-column align-items-start px-0 pt-0 pb-0 bd-callout bd-callout-color mb-1 '+cita.color+' '+cita.color_cli+'">'; 
                            }else{
                                output+='<a title="'+cita.asignado_a+'" href="citas3'+cita.tto_url+'.php?id_ci='+cita.id_ci+'&id_cli='+cita.id_cli+'&procedencia=agenda&id_tratamiento='+cita.tto_id+'" target="_blank"  onmouseover="window.status='+"''"+'; return true;" class="list-group-item list-group-item-action flex-column align-items-start px-0 pt-0 pb-0 bd-callout bd-callout-color mb-1 '+cita.color+' '+cita.color_cli+'">'; 

                                //output+='<a href="citas4'+cita.tto_url+'.php?id_c='+cita.id_ci+'&id_cli='+cita.id_cli+'&procedencia=agenda&id_tratamiento='+cita.tto_id+'" target="_blank"  onmouseover="window.status='+"''"+'; return true;" class="list-group-item list-group-item-action flex-column align-items-start px-0 pt-0 pb-0 bd-callout bd-callout-color mb-1 '+cita.color+' '+cita.color_cli+'">'; 
                            }
                            //output+='<a href="citas3'+cita.tto_url+'.php?id_ci='+cita.id_ci+'&id_cli='+cita.id_cli+'&procedencia=confirmacion&id_tratamiento='+cita.tto_id+'" target="_blank"  onmouseover="window.status='+"''"+'; return true;" class="list-group-item list-group-item-action flex-column align-items-start px-0 pt-0 pb-0 bd-callout bd-callout-color mb-1 '+cita.color+' '+cita.color_cli+'">';
                            
                            if (cita.id_color_pgt==3) {
                            output+='<div class="d-flex -flex justify-content-between w-100 bg-list-pagado">'
                            output+='<div>';
                            }
                            else if(cita.id_color_pgt==2){
                            output+='<div class="d-flex -flex justify-content-between w-100 bg-list-abonado">'
                            output+='<div>';
                            }
                            else{
                            output+='<div class="d-flex -flex justify-content-between w-100 bg-list">'
                            output+='<div>';
                            }

                            output+='<small class="font_size2">Cita: '+cita.id_ci+(cita.tto_id==2?' (ADELGAZA 5D)':'')+'</small>';

                            output+='</div>';
                            if(cita.tipo_pag=='Pagado'){
                                output+='<small style="color:black" class="font_size2 centrar_as">[Pagado]</small>'; 
                            }                            
                            if (cita.id_asistencia==1) {
                                output+='<div class="d-none" id="bot_confirm'+cita.id_ci+'">'
                                output+='<button type="button" class="btn btn-link px-0 py-0 btn-hover1 d-none" id="btn1'+cita.id_ci+'" title="Confirmó cita"><div class="fa-sm py-0"> <span class="fa-layers fa-fw"><i class="fas fa-circle"></i><i class="fa-inverse fas fa-check" data-fa-transform="shrink-4"></i></span></div></button>';
                                output+='<button type="button" class="btn btn-link px-0 py-0 btn-hover2 d-none" id="btn2'+cita.id_ci+'" title="No confirmó cita"><div class="fa-sm py-0"><span class="fa-layers fa-fw"><i class="fas fa-circle"></i><i class="fa-inverse fas fa-times" data-fa-transform="shrink-4"></i></span></div></button>';
                                output+='<button type="button" class="btn btn-link px-0 py-0 btn-hover3 d-none" id="btn3'+cita.id_ci+'" title="Confirmar cita en una semana"><div class="fa-sm py-0"><span class="fa-layers fa-fw"><i class="fas fa-circle"></i><span class="fa-layers-text fa-inverse" data-fa-transform="shrink-4 up-1" style="font-weight:900">SS</span></span></div></button>';
                                if(cita.envio=='Envio sms y wsp'){
                                    output+=`
                                    <button type="button" class="btn btn-link px-0 py-0 btn-hover4" title="No contestó llamada">
                                        <div class="fa-sm py-0">
                                            <span class="fa-layers fa-fw">
                                                <i class="fas fa-circle"></i>
                                                <i class="fa-inverse fas fa-phone" data-fa-transform="shrink-4"></i>
                                                <i class="fa-inverse fas fa-times" data-fa-transform="shrink-9 up-3.2 left-3"></i>
                                            </span>
                                        </div>
                                    </button>
                                    `
                                }
                                output+='</div>';    
                            
                            }else if(cita.id_asistencia==2){
                                output+='<small class="font_size2 centrar_as">CITA REALIZADA  <i class="fas fa-user-check"></i></small>';                       
                            }else if(cita.id_asistencia==3){
                                output+='<small style="color:red" class="font_size2 centrar_as">CITA CANCELADA <i class="fas fa-times"></i></small>';                       
                            }else if(cita.id_asistencia==4){
                                output+='<small style="color:red" class="font_size2 centrar_as">CITA ANULADA <i class="fas fa-times"></i></small>';                       
                            }
                           
                            output+='</div>';
                           
                                
                            
            
                            
                            output+='<div class="form-inline w-100 ml-0 font_size">'
                            output+='<span class="fa-layers fa-fw fa-pull-left fa-3x">'
                            output+='<i class="fas fa-clock" style="color:#bfbfbf" ></i>'
                            output+='<span class="fa-layers-text" data-fa-transform="shrink-10" style="font-weight:900; color:#444242">'+cita.hora_format+'</span>'
                            output+='</span>'
                                
                            output+='<div class="col col-sm-5 col-md-3">'
                            output+='<i class="fas fa-user"></i><span class="mb-1 text-capitalize">'+cita.id_cli+'. '+cita.nombrec+' '+cita.apellidoc+' ['+cita.historia+']</span><br>'
                            output+='<i class="fas fa-mobile-alt"></i><span class="mb-1">'+cita.telefonoc+'</span>'
                            output+='</div>'
                                
                            output+='<div class="col-12 col-md-4 px-lg-0">'
                            output+='<i class="fas fa-tasks" title="Zonas"></i><span class="mb-1"> ('+cita.tipo_cita+') // '+(cita.resumen==null?'':cita.resumen)+'</span>'
                                    
                            output+='</div>'
                                
                            output+='<div class="col-12 col-md">'
                            output+='<i class="fas fa-info-circle" title="Avisos"></i><span class="mb-1"> '+cita.avisos+'</span><br>'
                            output+='<i class="fas fa-bars" title="Detalles"></i><span class="mb-1"> '+cita.detalles+'</span><br>'
                                    
                            output+='</div>'
                            output+='</div>'
                            output+='</a>'
                        }
                        var citas_ca=0;
                        var citas_an=0;
                        var citas_nc=0;
                        var citas_sc = 0;
                        var citas_ds=0;
                        var citas_ss=0;
                        var citas_total=citas_array.length;
                        var citas_re=0;
                        if(response.data.citas.length!==0){
                            document.getElementById('datos').innerHTML=output;                                                        
                            //FUNCION QUE ACTIVA LOS BOTONES, necesita que este pintado los registros para funcionar
                            for (let i = 0; i < citas_array.length; i++) {                                
                                const cita = citas_array[i];
                                //console.log(cita);
                                var id_ci    = cita.id_ci;
                                var id_asist = cita.id_asistencia;
                                
                                var conf_ds = cita.conf_ds;
                                var conf_ss = cita.conf_ss;
                                
                                var bot_confirm = $('#bot_confirm'+id_ci); 
                                var btn1        = $('#btn1'+id_ci); 
                                var btn2        = $('#btn2'+id_ci); 
                                var btn3        = $('#btn3'+id_ci); 
                                //status de cita pendiente
                                if(id_asist == 1){
                                    bot_confirm.removeClass("d-none");                                    
                                    // icono no confirmó cita
                                    if(conf_ds == 0){
                                        btn1.addClass("d-none");
                                        btn2.removeClass("d-none");
                                        btn3.addClass("d-none");
                                        if(cita.envio=='Envio sms y wsp'){
                                            citas_nc++;
                                        }else{
                                            citas_sc++;
                                        }
                                    }else if(conf_ds==1) {                                    
                                        btn1.addClass("d-none");
                                        btn2.addClass("d-none");
                                        btn3.removeClass("d-none");                                       
                                        if(cita.envio=='Envio sms y wsp'){
                                            citas_nc++;
                                        }else{
                                            citas_ss++;
                                        }
                                    }else if(conf_ds==2){                                       
                                        btn1.removeClass("d-none");
                                        btn2.addClass("d-none");
                                        btn3.addClass("d-none");                                       
                                        if(cita.envio=='Envio sms y wsp'){
                                            citas_nc++;
                                        }else{
                                            citas_ds++;
                                        }
                                    }
                                    //citas_total++;
                                }else if(id_asist==3){
                                    citas_ca++;
                                    //citas_total++;
                                }else if(id_asist==2){
                                    citas_re++;
                                }else if(id_asist==4){
                                    citas_an++;
                                }
                            }
                            document.getElementById('citas_ca').innerHTML=citas_ca+' y '+citas_an; 
                            document.getElementById('citas_nc').innerHTML=citas_nc;   
                            document.getElementById('citas_sc').innerHTML=citas_sc;     
                            document.getElementById('citas_ds').innerHTML=citas_ds; 
                            document.getElementById('citas_ss').innerHTML=citas_ss; 
                            document.getElementById('citas_total').innerHTML=citas_re+' / '+citas_total;
                            document.getElementById('citas_total').title=citas_total-citas_re;
                        }else{
                            document.getElementById('datos').innerHTML='Sin citas'; 
                            document.getElementById('citas_ca').innerHTML=0;
                            document.getElementById('citas_nc').innerHTML=0;   
                            document.getElementById('citas_sc').innerHTML=0;     
                            document.getElementById('citas_ds').innerHTML=0; 
                            document.getElementById('citas_ss').innerHTML=0;
                            document.getElementById('citas_total').innerHTML=0; 
                            document.getElementById('citas_total').title=null;
                        }
                        document.getElementById('btn-buscar').disabled=false;
                    }else{
                        console.log(response.data.msg);
                    };
                };            
            }
            ajax_request.open('GET',ajax_url);
            ajax_request.send();
        }
        function modalHistorias(){
            console.log(historias);
            var main_agenda=document.getElementById('main_agenda');
            var modal_agenda=document.createElement('div');
            modal_agenda.setAttribute('style','width:100%;height:100%;background-color:rgba(0,0,0,0.5);position:absolute;top:0;left:0;z-index:100;display:flex;flex-direction:column;overflow:auto');
            modal_agenda.innerHTML=`
                <table class="table table-light" style="max-width:800px;margin:auto">
                    <thead>
                    <tr><th colspan="100%"><button id="btn_modal_agenda_close"><i class="fas fa-times"></i> Cerrar</button></th></tr>
                        <tr>
                            <th>Historia</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        ${historias.map((item,index)=>{
                            return `<tr>                               
                                <td><label for="chb_agenda_hist_${index}" style="cursor:pointer">${item.historia}</label></td>
                                <td><label for="chb_agenda_hist_${index}" style="cursor:pointer">${item.cliente}</label></td>
                                <td><label for="chb_agenda_hist_${index}" style="cursor:pointer">${item.conf_ds==2?'Confirmado hace un dia':item.conf_ds==1?'Confirmado hace una semana':'Sin confirmar'}</label></td>
                                <th><input type="checkbox" id="chb_agenda_hist_${index}"/></th>
                            </tr>`
                        }).join('')}
                    </tbody>
                </table>
            `;
            main_agenda.appendChild(modal_agenda);
            var closeModal=()=>{
                main_agenda.removeChild(modal_agenda);
            }
            modal_agenda.querySelector('#btn_modal_agenda_close').addEventListener('click',()=>{closeModal()})
        }
        window.addEventListener('load',function(){
            listarCitas();
        });
    </script>
    <body onload="nobackbutton();">
    <div style="position:absolute;height:100%;width:100%;overflow:auto;display:flex;flex-direction:column" id="main_agenda">
        <div class="form-inline" >          
            <div class="input-group input-group-sm mt-1 mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Fecha</span>
                </div>
                <input type="date" style="width:fit-content" class="form-control" placeholder="Buscar" id="filtroFecha" value="<?php echo date('Y-m-d');?>">
                
                <select style="width:fit-content" id="cmb-tto" class="custom-select custom-select-sm"  required>                                        
                    <option value="">Todas las agendas</option>
                    <?php
                    include ('conexionbd.php');
                    $queryTTO = "SELECT * FROM tratamientos WHERE status = 'T'";
                    $traer_tto = mysqli_query($conexion,$queryTTO);    
                    
                    while($consul_tto = mysqli_fetch_array($traer_tto)){
                    ?>
                    <option value="<?php echo $consul_tto['id']; ?>"><?php echo $consul_tto['nombre'];?></option>
                    <?php 
                    } 
                    ?>
                </select>
                <select style="width:fit-content" id="cmb-sede" class="custom-select custom-select-sm" >                                        
                    <option value="">Todas las sedes</option>
                    <option value="1">San Borja</option>
                    <option value="2">Megaplaza</option>
                </select>
                <select style="width:fit-content" id="cmb-asistencia" class="custom-select custom-select-sm">                                        
                    <option value="">Todos los estados</option>
                    <?php 
                        if($resAsistencias=mysqli_query($conexion,"select * from cit_asistencia")){
                            while($rowAsistencia=$resAsistencias->fetch_object()){
                                echo "<option value='".($rowAsistencia->codigo)."' >".($rowAsistencia->descripcion)."</option>";
                            }
                        }
                    ?>
                </select>
                <select style="width:fit-content" id="cmb-etapa" class="custom-select custom-select-sm" >                                        
                    <option value="">Todas las etapas</option>
                    <option value="0">Sin confirmar</option>
                    <option value="1">Confirmados Semana Siguiente</option>
                    <option value="2">Confirmados Dia Siguiente</option>
                </select>
                <select style="width:fit-content" id="cmb-tipo-cita" class="custom-select custom-select-sm"  >                                        
                    <option value="">Todas las tipos</option>
                    <option value="1">Consulta</option>
                    <option value="2">Mantenimiento</option>
                    <option value="4">Retoque</option>
                    <option value="5">Sesión</option> 
                </select>
                <div class="input-group-append">
                    <button class="btn btn-primary" onclick="listarCitas()" id="btn-buscar"><i class="fas fa-search"></i> Buscar o Actualizar</button>
                </div>
            </div>
        </div>
        <div class="card" style="flex-grow:1">
            <div class="card-header bg-sistema">
                <div class="form-row">
                    <div class="col" id="fecha_filtrada">
                        <i class="fas fa-calendar-alt fa-lg"></i> 
                    </div>
                    <div><button class="btn btn-primary" onclick="modalHistorias()">Listar historias</button></div>
                    <div class="input-group input-group-sm d-flex justify-content-end col-md-4 col-lg-3">
                        <input type="text" class="form-control" placeholder="Buscar datos de la cita" id="searchTerm" onkeyup="doSearch()">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="card-body scroll_agenda px-1 pt-1">
               <!-- <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>-->
   
                <div class="list-group" id="datos">
                    
                </div>
            </div>
            
        </div>
        <div style="width:100%;display:flex;flex-direction:column;padding:5px;background-color: white;border-radius: 10px; margin-top: 10px;font-size: 20px;">
            <table>
                <tbody>
                    <tr>
                        <td>N° de citas canceladas(se comunicara) y anuladas(no desea tratamiento): <span style="font-weight:bold" id="citas_ca"></span></td>
                        <td>N° de citas no contestadas: <span style="font-weight:bold" id="citas_nc"></span></td>                        
                    </tr>
                    <tr>
                        <td>N° de citas sin confirmar: <span style="font-weight:bold" id="citas_sc"></span></td>
                        <td>N° de citas confirmadas como semana siguiente: <span style="font-weight:bold" id="citas_ss"></span></td>
                    </tr>
                    <tr>
                        <td>N° de citas confirmadas como dia siguiente: <span style="font-weight:bold" id="citas_ds"></span></td>
                        <td> CITAS REALIZADAS / TOTAL DE CITAS: <span style="font-weight:bold" id="citas_total"></span></td>
                    </tr>
                    <tr>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </body>
</html>


<script type="text/javascript">

$(document).ready(function () {
  
    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});

    document.querySelector("#searchTerm").onkeyup = function(){
        $TableFilter("#datos", this.value);
    }
    
    $TableFilter = function(id, value){
        var rows = document.querySelectorAll(id + ' a');
        
        for(var i = 0; i < rows.length; i++){
            var showRow = false;
            
            var row = rows[i];
            row.style.display = 'none';
            
            for(var x = 0; x < row.childElementCount; x++){
                if(row.children[x].textContent.toLowerCase().indexOf(value.toLowerCase().trim()) > -1){
                    showRow = true;
                    break;
                }
            }
            
            if(showRow){
                row.style.display = null;
            }
        }
    };
 
</script>

<script type="text/javascript" src="gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="gritter/gritter-conf.js"></script>

<script type="text/javascript">
        // $(document).ready(function () {
        // var unique_id = $.gritter.add({
           
        //     title: 'DepilZone Agenda',
          
        //     text: 'En esta sección tienes las citas del día, donde podrás confirmar, reprogramar etc. Puedes también buscar la cita en el filtro de arriba por nombre, número de teléfono o cualquier otro dato referente a tu busqueda.',
        //     image: 'imagenes/logo.png',
        //     sticky: true,
        //     time: '',
        //     class_name: 'my-sticky-class'
        // });

        // return false;
        // });
    
    
   // $(document).ready(function() { $('a').postlink(); });
  </script>