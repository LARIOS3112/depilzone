<?php

    session_start(); 

    $id_cliente = $_POST['id_cliente'];
    $archivo_seleccionado = $_POST['archivo_seleccionado'];
    $id_sede = $_POST['id_sede'];
    $fecha_consultada = $_POST['fecha_consultada'];
    $id_tratamiento = $_POST['id_tratamiento'];
    $id_zonas = $_POST['id_zonas'];

?>

<thead class="css-blue">
         
    <tr>       
        <th class="tam_th">Sede</th>
        <th class="tam_th">Fecha</th>
        <th class="tam_th">Hora</th>
        <th class="tam_th">Imagen</th>
    </tr>

</thead>

<tbody>

<?php             

    include ('conexionbd.php'); 

    if($id_tratamiento == 1)
    {

        $query_cupo_min_ocupado = "SELECT cu.id as id_cupo, sum(tz.duracion) as duracion_cupo 
            FROM emerg_cupos cu 
            INNER JOIN citas ci ON cu.id = ci.id_cupo 
            INNER JOIN tabla_zonas tz ON ci.id = tz.id_cita
            WHERE cu.id IN (
                SELECT cu.id as id_cupo 
                FROM emerg_maquinas ma
                INNER JOIN emerg_cupos cu ON ma.id = cu.id_maquina 
                INNER JOIN emerg_horarios ho ON cu.id_horarios = ho.id 
                INNER JOIN emerg_sedes se ON ho.id_sede = se.id 
                INNER JOIN emerg_empresas em ON se.id_empresa = em.id 
                WHERE ma.estado = '1' 
                AND ho.status = '1'
                AND ho.fecha = '$fecha_consultada' 
                AND ho.id_sede = '$id_sede'
                GROUP BY id_cupo
            )  
            AND ci.id_status = '1'
            GROUP BY id_cupo";
        
        if($res_cupo_min_ocupado = mysqli_query($conexion, $query_cupo_min_ocupado))
        {

                $id_array_min_ocupado= [];
                $array_min_ocupado = [];

            if($row_cnt_min_ocupado = mysqli_num_rows($res_cupo_min_ocupado))
            {

                    for($i=0; $i < $row_cnt_min_ocupado; $i++){

                        $row_cupo_min_ocupado = mysqli_fetch_object($res_cupo_min_ocupado);

                        $id_array_min_ocupado[$i] = $row_cupo_min_ocupado->id_cupo;
                        $array_min_ocupado[$i] = $row_cupo_min_ocupado->duracion_cupo;
                    }
        
        

                $query_cupo_min_limite = "SELECT cu.id as id_cupo, (sum(ho.hora_fin) - sum(ho.hora_inicio)) * 60 as tiem_limit_maq 
                FROM emerg_maquinas ma
                INNER JOIN emerg_cupos cu ON ma.id = cu.id_maquina 
                INNER JOIN emerg_horarios ho ON cu.id_horarios = ho.id 
                INNER JOIN emerg_sedes se ON ho.id_sede = se.id 
                INNER JOIN emerg_empresas em ON se.id_empresa = em.id 
                WHERE cu.id IN (
                    SELECT cu.id 
                    FROM emerg_cupos cu 
                    INNER JOIN citas ci ON cu.id = ci.id_cupo 
                    INNER JOIN tabla_zonas tz ON ci.id = tz.id_cita
                    WHERE ci.id_status = '1' 
                    GROUP BY id_cupo
                ) 
                AND ma.estado = '1' 
                AND ho.status = '1' 
                AND ho.fecha = '$fecha_consultada' 
                AND ho.id_sede = '$id_sede'
                GROUP BY id_cupo";

                if($res_cupo_min_limite = mysqli_query($conexion, $query_cupo_min_limite)){

                    $id_array_min_limite= [];
                    $array_min_limite = [];

                    $row_cnt_min_limite = mysqli_num_rows($res_cupo_min_limite);

                    for($i=0; $i < $row_cnt_min_limite; $i++){

                        $row_cupo_min_limite = mysqli_fetch_object($res_cupo_min_limite);

                        $id_array_min_limite[$i] = $row_cupo_min_limite->id_cupo;
                        $array_min_limite[$i] = $row_cupo_min_limite->tiem_limit_maq;
                    }

                }

                $query_hora_inicio = "SELECT cu.id as id_cupo, ho.hora_inicio as hora_inicio, ho.hora_fin as hora_fin , ho.id_sede as id_sede, ho.fecha as fecha 
                FROM emerg_maquinas ma
                INNER JOIN emerg_cupos cu ON ma.id = cu.id_maquina 
                INNER JOIN emerg_horarios ho ON cu.id_horarios = ho.id 
                INNER JOIN emerg_sedes se ON ho.id_sede = se.id 
                INNER JOIN emerg_empresas em ON se.id_empresa = em.id 
                WHERE cu.id IN (
                    SELECT cu.id 
                    FROM emerg_cupos cu 
                    INNER JOIN citas ci ON cu.id = ci.id_cupo 
                    INNER JOIN tabla_zonas tz ON ci.id = tz.id_cita
                    WHERE ci.id_status = '1' 
                    GROUP BY id_cupo
                ) 
                AND ma.estado = '1' 
                AND ho.status = '1' 
                AND ho.fecha = '$fecha_consultada' 
                AND ho.id_sede = '$id_sede'
                GROUP BY id_cupo";

                if($res_cupo_hora_inicio = mysqli_query($conexion, $query_hora_inicio)){

                    $id_array_hora_inicio= [];
                    $array_hora_inicio = [];
                    $array_hora_fin = [];
                    $array_id_sede = [];
                    $array_fecha = [];

                    $row_cnt_hora_inicio = mysqli_num_rows($res_cupo_hora_inicio);

                    for($i=0; $i < $row_cnt_hora_inicio; $i++){

                        $row_cupo_hora_inicio = mysqli_fetch_object($res_cupo_hora_inicio);

                        $id_array_hora_inicio[$i] = $row_cupo_hora_inicio->id_cupo;
                        $array_hora_inicio[$i] = $row_cupo_hora_inicio->hora_inicio;
                        $array_hora_fin[$i] = $row_cupo_hora_inicio->hora_fin;
                        $array_id_sede[$i] = $row_cupo_hora_inicio->id_sede;
                        $array_fecha[$i] = $row_cupo_hora_inicio->fecha;
                    }

                }

                $array_hora_disponible = [];

                for($i=0; $i < count($id_array_min_ocupado); $i++){
                    
                    if($id_array_min_ocupado[$i] == $id_array_min_limite[$i]){
                        
                        if($array_min_ocupado[$i] < $array_min_limite[$i]){

                            $hora_inicio = $array_hora_inicio[$i];
                            $hora_fin = $array_hora_fin[$i];
                            $minutos_ocupados = $array_min_ocupado[$i];

                            $h = 0;
                            $m = 0;

                            if(is_int($minutos_ocupados / 60)){
                                $h = $minutos_ocupados / 60;
                                //echo " Horas ".$hora;
                            }else{
                                $m = $minutos_ocupados % 60;
                                //echo " minutos ".$m;
                            }
            
                            $minutos = $m * 100;
                            $hora_inicio_int = intval($hora_inicio);
                            $horas = $hora_inicio_int * 10000;

                            // Hora Disponible del cupo.
                            $hora =  $horas + $minutos;

                            // Guardo la hora disponible en el arreglo de acuerdo al cupo
                            $array_hora_disponible[$i] = $hora;

                            $hora_fin_int = intval($hora_fin);
                            $hora_fin_convertida = $hora_fin_int * 10000;

                            //---------Cambiar Formato a la hora----------------

                            $hora_como_string = $hora;
                            settype($hora_como_string, 'string');

                            $num_caracteres_hora = strlen($hora_como_string);

                            if( $num_caracteres_hora < 6){
                                $hora_6digitos = '0'.$hora_como_string;
                            }else{
                                $hora_6digitos = $hora_como_string;
                            }

                            $hora_date_create = date_create($hora_6digitos);
                            $hora_date_format = date_format($hora_date_create, 'H:i');
                        

                            //------------------------------------------


                            if($hora < $hora_fin_convertida){

    ?>  
                            <tr>
                                <td><?php echo ($array_id_sede[$i] == 1) ? "SAN BORJA" : "MEGAPLAZA";?></td>
                                <td><?php echo ($array_fecha[$i] =="")? $fecha_consultada : $array_fecha[$i] ;?></td>
                                <td><?php echo $hora_date_format;?></td>
                                <td>
                                    <a href="citas<?php echo $archivo_seleccionado;?>.php?id_cliente=<?php echo $id_cliente;?>&id_tratamiento=<?php echo $id_tratamiento; ?>&procedencia=nueva_cita&fechaconsultada=<?php echo ($array_fecha[$i] =="")? $fecha_consultada : $array_fecha[$i] ;?>>&sedeconsultada=<?php echo $array_id_sede[$i];?>&horanueva=<?php echo $hora_date_format;?>&id_cupo=<?php echo $id_array_min_ocupado[$i];?>" target="_blank" class="btn-hover1" title="Crear Cita">
                                        Nueva Cita
                                        <i class="fas fa-plus-circle fa-lg"></i>
                                    </a>
                                </td> 
                            </tr>
    
    <?php 

                            }

                        }
                    }
                }

            }else{            

                $query_default = "SELECT cu.id as id_cupo, sum(tz.duracion) as duracion_cupo 
                FROM emerg_cupos cu 
                INNER JOIN citas ci ON cu.id = ci.id_cupo 
                INNER JOIN tabla_zonas tz ON ci.id = tz.id_cita
                WHERE cu.id IN (
                    SELECT cu.id as id_cupo 
                    FROM emerg_maquinas ma
                    INNER JOIN emerg_cupos cu ON ma.id = cu.id_maquina 
                    INNER JOIN emerg_horarios ho ON cu.id_horarios = ho.id 
                    INNER JOIN emerg_sedes se ON ho.id_sede = se.id 
                    INNER JOIN emerg_empresas em ON se.id_empresa = em.id 
                    WHERE ma.estado = '1' 
                    AND ho.status = '1'
                    GROUP BY id_cupo
                )  
                AND ci.id_status = '1'
                GROUP BY id_cupo";

                $res_cupo_min_ocupado = mysqli_query($conexion, $query_default);
                                

                $id_array_min_ocupado= [];
                $array_min_ocupado = [];

                $row_cnt_min_ocupado = mysqli_num_rows($res_cupo_min_ocupado);



                for($i=0; $i < $row_cnt_min_ocupado; $i++){

                    $row_cupo_min_ocupado = mysqli_fetch_object($res_cupo_min_ocupado);

                    $id_array_min_ocupado[$i] = $row_cupo_min_ocupado->id_cupo;
                    $array_min_ocupado[$i] = $row_cupo_min_ocupado->duracion_cupo;
                }
        
        

                $query_cupo_min_limite = "SELECT cu.id as id_cupo, (sum(ho.hora_fin) - sum(ho.hora_inicio)) * 60 as tiem_limit_maq 
                FROM emerg_maquinas ma
                INNER JOIN emerg_cupos cu ON ma.id = cu.id_maquina 
                INNER JOIN emerg_horarios ho ON cu.id_horarios = ho.id 
                INNER JOIN emerg_sedes se ON ho.id_sede = se.id 
                INNER JOIN emerg_empresas em ON se.id_empresa = em.id 
                WHERE cu.id IN (
                    SELECT cu.id 
                    FROM emerg_cupos cu 
                    INNER JOIN citas ci ON cu.id = ci.id_cupo 
                    INNER JOIN tabla_zonas tz ON ci.id = tz.id_cita
                    WHERE ci.id_status = '1' 
                    GROUP BY id_cupo
                ) 
                AND ma.estado = '1' 
                AND ho.status = '1' 
                GROUP BY id_cupo";

                if($res_cupo_min_limite = mysqli_query($conexion, $query_cupo_min_limite)){

                    $id_array_min_limite= [];
                    $array_min_limite = [];

                    $row_cnt_min_limite = mysqli_num_rows($res_cupo_min_limite);

                    for($i=0; $i < $row_cnt_min_limite; $i++){

                        $row_cupo_min_limite = mysqli_fetch_object($res_cupo_min_limite);

                        $id_array_min_limite[$i] = $row_cupo_min_limite->id_cupo;
                        $array_min_limite[$i] = $row_cupo_min_limite->tiem_limit_maq;
                    }

                }

                $query_hora_inicio = "SELECT cu.id as id_cupo, ho.hora_inicio as hora_inicio, ho.hora_fin as hora_fin , ho.id_sede as id_sede, ho.fecha as fecha 
                FROM emerg_maquinas ma
                INNER JOIN emerg_cupos cu ON ma.id = cu.id_maquina 
                INNER JOIN emerg_horarios ho ON cu.id_horarios = ho.id 
                INNER JOIN emerg_sedes se ON ho.id_sede = se.id 
                INNER JOIN emerg_empresas em ON se.id_empresa = em.id 
                WHERE cu.id IN (
                    SELECT cu.id 
                    FROM emerg_cupos cu 
                    INNER JOIN citas ci ON cu.id = ci.id_cupo 
                    INNER JOIN tabla_zonas tz ON ci.id = tz.id_cita
                    WHERE ci.id_status = '1' 
                    GROUP BY id_cupo
                ) 
                AND ma.estado = '1' 
                AND ho.status = '1' 
                GROUP BY id_cupo";

                if($res_cupo_hora_inicio = mysqli_query($conexion, $query_hora_inicio)){

                    $id_array_hora_inicio= [];
                    $array_hora_inicio = [];
                    $array_hora_fin = [];
                    $array_id_sede = [];

                    $row_cnt_hora_inicio = mysqli_num_rows($res_cupo_hora_inicio);

                    for($i=0; $i < $row_cnt_hora_inicio; $i++){

                        $row_cupo_hora_inicio = mysqli_fetch_object($res_cupo_hora_inicio);

                        $id_array_hora_inicio[$i] = $row_cupo_hora_inicio->id_cupo;
                        $array_hora_inicio[$i] = $row_cupo_hora_inicio->hora_inicio;
                        $array_hora_fin[$i] = $row_cupo_hora_inicio->hora_fin;
                        $array_id_sede[$i] = $row_cupo_hora_inicio->id_sede;
                    }

                }

                $array_hora_disponible = [];

                for($i=0; $i < count($id_array_min_ocupado); $i++){
                    
                    if($id_array_min_ocupado[$i] == $id_array_min_limite[$i]){
                        
                        if($array_min_ocupado[$i] < $array_min_limite[$i]){

                            $hora_inicio = $array_hora_inicio[$i];
                            $hora_fin = $array_hora_fin[$i];
                            $minutos_ocupados = $array_min_ocupado[$i];

                            $h = 0;
                            $m = 0;

                            if(is_int($minutos_ocupados / 60)){
                                $h = $minutos_ocupados / 60;
                                //echo " Horas ".$hora;
                            }else{
                                $m = $minutos_ocupados % 60;
                                //echo " minutos ".$m;
                            }
            
                            $minutos = $m * 100;
                            $hora_inicio_int = intval($hora_inicio);
                            $horas = $hora_inicio_int * 10000;

                            // Hora Disponible del cupo.
                            $hora =  $horas + $minutos;

                            // Guardo la hora disponible en el arreglo de acuerdo al cupo
                            $array_hora_disponible[$i] = $hora;

                            $hora_fin_int = intval($hora_fin);
                            $hora_fin_convertida = $hora_fin_int * 10000;

                            //---------Cambiar Formato a la hora----------------

                            $hora_como_string = $hora;
                            settype($hora_como_string, 'string');

                            $num_caracteres_hora = strlen($hora_como_string);

                            if( $num_caracteres_hora < 6){
                                $hora_6digitos = '0'.$hora_como_string;
                            }else{
                                $hora_6digitos = $hora_como_string;
                            }

                            $hora_date_create = date_create($hora_6digitos);
                            $hora_date_format = date_format($hora_date_create, 'H:i');
                        

                            //------------------------------------------


                            if($hora < $hora_fin_convertida){

         ?>  
                            <tr>
                                <td><?php echo ($array_id_sede[$i] == 1) ? "SAN BORJA" : "MEGAPLAZA";?></td>
                                <td><?php echo ($array_fecha[$i] =="")? $fecha_consultada : $array_fecha[$i] ;?></td>
                                <td><?php echo $hora_date_format;?></td>
                                <td>
                                    <a href="citas<?php echo $archivo_seleccionado;?>.php?id_cliente=<?php echo $id_cliente;?>&id_tratamiento=<?php echo $id_tratamiento; ?>&procedencia=nueva_cita&fechaconsultada=<?php echo ($array_fecha[$i] =="")? $fecha_consultada : $array_fecha[$i] ;?>>&sedeconsultada=<?php echo $array_id_sede[$i];?>&horanueva=<?php echo $hora_date_format;?>&id_cupo=<?php echo $id_array_min_ocupado[$i];?>" target="_blank" class="btn-hover1" title="Crear Cita">
                                        Nueva Cita
                                        <i class="fas fa-plus-circle fa-lg"></i>
                                    </a>
                                </td> 
                            </tr>
        
        <?php 

                            }

                        }
                    }
                }

            }
        }

    }

?>     

</tbody> 