<?php  
     
        include ('conexionbd.php');


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<button type="button"  onclick="enviar(); ">Check!</button>



 <div class="table-responsive scroll_tab_asi_dia">
                    <table class="table table-sm table-striped table-hover">
                        <thead class="css-blue">
                            <tr>
                                <th>N° Cita</th>
                                <th>Fecha Cita</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Teleoperadora</th>
                                <th>Fecha Confirm.</th>
                                <th>Asignar</th>
                            </tr>
                        </thead>
                                      
                        <tbody>
                            <?php
                            $sql = "SELECT a.id, a.fecha, a.hora, DATE_FORMAT(a.fecha, '%d/%m/%Y') fecha_format, DATE_FORMAT(a.hora, '%h:%i %p') hora_format,
                                           a.avisos, a.detalles, b.nombrec, b.apellidoc, b.telefonoc, b.correo, 
                                           DATE_FORMAT(d.fech_confirm, '%d/%m/%Y') fech_confirm, d.id_teleop, u.usuario teleop
                                      FROM citas a LEFT JOIN conf_diarias d ON a.id = d.id_cita LEFT JOIN users u ON u.id = d.id_teleop, cliente b
                                     WHERE b.id = a.id_cliente 
                                       AND a.id_asistencia = '1'
                                       AND a.motivo_no_tto = ''
                                       AND a.fecha = '$manana1'
                                  ORDER BY 3 ASC";
                            $resultado = mysqli_query($conexion, $sql);
                            $lista     = mysqli_fetch_array($resultado);
                            
                            $x=1;
                               
                            if($lista['id'] != ''){
                                $posic = 0;
                                do{
                                    $id_cita = $lista['id'];
                            ?>
                            
                            <tr>
                                <input type="hidden" name="id_cita" value="<?php echo $id_cita;?>">
                                <input type="hidden" name="id_us" id="id_us" value="<?php echo $id_us;?>">
                                <td><?php echo $lista['id'];?></td>
                                <td><?php echo $lista['fecha_format']."  ".$lista['hora_format'];?></td>
                                <td class="text-capitalize"><?php echo $lista['nombrec']."  ".$lista['apellidoc'];?></td>
                                <td><?php echo $lista['telefonoc'];?></td>
                               
                                <?php 
                                    // no asignado a ninguna teleoperadora
                                    if($lista['id_teleop'] == ""){
                                ?>
                                <td>
                                    <select name="teleop" class="custom-select custom-select-sm">
                                        <?php  
                                        $query = "SELECT u.usuario, u.id 
                                                    FROM users u, tipodusers t 
                                                   WHERE u.tipo = t.tipo 
                                                     AND t.tipo = 4 
                                                     AND u.status = 'T'
                                                ORDER BY 1 ASC";
                                        $result = mysqli_query($conexion, $query);
                                        while($array = mysqli_fetch_array($result)){ 
                                        ?>
                                        <option value="<?php echo $array['id'];?>"><?php echo $array['usuario'];?></option>
                                        <?php 
                                        } 
                                        ?>
                                    </select>
                                </td>
                                
                                <td></td>
                            
                                <td>
                                    <!--<div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="reasignar" value="<?php echo $posic;?>" id="a<?php echo $id;?>" onclick="cambio(<?php echo $id;?>)">
                                        <label class="custom-control-label mb-3 ml-4" for="a<?php echo $id;?>"></label>
                                    </div>-->
                                    
                                    <div class="custom-control custom-checkbox">
                                        <input  type="checkbox" class="btn<?php echo $x;?>"  name="checkbox[]" id="<?php echo $id_cita;?>" value="<?php echo $posic;?>" name="id">
                                        <label class="custom-control-label mb-3 ml-4" for="<?php echo $id_cita;?>"></label>
                                    </div>
                                </td>
                                <?php 
                                        $x++;
                                    } 
                                    // asignado a una teleoperadora   
                                    else{ 
                                ?>
                                <td>
                                    <?php echo $lista['teleop'];?>
                                </td>
                                
                                <td>
                                    <?php echo $lista['fech_confirm'];?>      
                                </td>
                            
                                <td>
                                    <span class="badge badge-pill badge-primary"> Asignado</span>
                                </td>
                                <?php
                                    }
                                ?>
                            </tr>
                            <?php
                                    $posic++;
                                }
                                while($lista = mysqli_fetch_array($resultado));
                            }
                            else{
                            ?>
                            <div class='alert alert-warning'><i class="fas fa-exclamation-circle fa-2x"></i> No hay citas para asignar</div>
                             
                            <?php
                            }
                            mysqli_close($conexion);
                            ?>
                           
                        </tbody>
                            
                    </table>
                </div>









<!--
<table>
        <tr>
            <td>Checkbox 1: <input type="checkbox" class="btn1"  name="checkbox[]" /></td>
            <td>Checkbox 2: <input type="checkbox" class="btn2" name="checkbox[]" /></td>
            <td>Checkbox 3: <input type="checkbox" class="btn3"  name="checkbox[]" /></td>
            <td>Checkbox 4: <input type="checkbox" class="btn4"  name="checkbox[]" /></td>
            <td>Checkbox 5: <input type="checkbox" class="btn"  name="checkbox[]" /></td>
            <td>Checkbox 6: <input type="checkbox" class="btn"  name="checkbox[]" /></td>
            <td>Checkbox 7: <input type="checkbox" class="btn" name="checkbox[]" /></td>
            <td>Checkbox 8: <input type="checkbox" class="btn" name="checkbox[]" /></td>
        </tr>
    </table>
-->
<script>
var indice = 0;
 var contador=5;
    
    
    

    

$("button").click(function() { 
    
    
    
  // Desmarcar todas las checkboxes
  $("input[type=checkbox]").prop("checked", false);
    
   for ( i=1;i<=contador;i++){
  // Marcar únicamente el checkbox que corresponde al índice
       
  $(".btn"+i+"").prop("checked",true);
       alert(i);
       $("table td:last-child:contains(ACTIVE)")
    .parents("tr")
    .css("background-color", "");
   }
    alert(indice);

  // Si el índice llega a la cantidad de checkboxes, reiniciar
  if(indice>=2) indice = 0;
}) 
</script>