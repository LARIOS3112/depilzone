<?php
    session_start(); 
    $id_cliente =$_POST['id_cliente'];
    $id_tto =$_POST['filtro_tto'];
    $archivo_seleccionado=$_POST['archivo_seleccionado'];
?>
<thead class="css-blue">
    <tr>
        <th>Cita</th>       
        <th class="tam_th">Fecha/Hora</th>       
        <th class="tam_th1">Resumen</th>
        <th>Tipo</th>
        <th>Status Cita</th>
        <th>Status Pago</th>
        <th>Importe</th>
        <th></th>
    </tr>
</thead>
       
<tbody>
            <?php             
            include ('conexionbd.php'); 
            if($archivo_seleccionado==''){
                $query = "select c.id, c.fecha, c.hora,  DATE_FORMAT(c.fecha, '%d/%m/%Y') fecha_format, DATE_FORMAT(c.hora, '%h:%i %p') hora_format,
                    c.id_asistencia, GROUP_CONCAT(CONCAT(z.partes, ' Sesión ' ,s.sesion) SEPARATOR ' - ') resumen, c.total, ct.descripcion cit_ti, ca.descripcion cit_st, pt.descripcion status_pago
                    FROM citas c INNER JOIN tabla_zonas t ON c.id = t.id_cita,  cit_tipo ct, cit_asistencia ca, sesiones s, zonas z, pag_tipo pt
                    WHERE c.id_cliente = $id_cliente
                        AND c.cod_cit_tipo = ct.codigo
                        AND c.id_asistencia = ca.codigo
                        AND ((c.id_asistencia != 1) OR (c.motivo_no_tto != ''))
                        AND t.sesion = s.id
                        AND t.id_zona = z.id
                        AND pt.codigo = c.cod_pago_tipo
                        AND c.id_tratamiento=$id_tto
                GROUP BY c.id
                ORDER BY c.fecha DESC";
            }elseif ($archivo_seleccionado=='_adelgaza') {
                $query = "select c.id, c.fecha, c.hora,  DATE_FORMAT(c.fecha, '%d/%m/%Y') fecha_format, DATE_FORMAT(c.hora, '%h:%i %p') hora_format,
                    c.id_asistencia, GROUP_CONCAT(CONCAT(z.nombre, ' sesion' ,s.sesion) SEPARATOR ' - ') resumen, c.total, ct.descripcion cit_ti, ca.descripcion cit_st, pt.descripcion status_pago
                    FROM citas c LEFT JOIN tabla_deta_adelgaza t ON c.id = t.id_cita LEFT JOIN detalles_adelgaza z on z.id=t.id_deta_adelgaza LEFT JOIN sesiones s on s.id=t.id_sesion,  cit_tipo ct, cit_asistencia ca, pag_tipo pt
                    WHERE c.id_cliente = $id_cliente
                        AND c.cod_cit_tipo = ct.codigo
                        AND c.id_asistencia = ca.codigo
                        AND ((c.id_asistencia != 1) OR (c.motivo_no_tto != ''))
                        AND pt.codigo = c.cod_pago_tipo
                        AND c.id_tratamiento=$id_tto
                GROUP BY c.id
                ORDER BY c.fecha DESC";
            }

$resultado = mysqli_query($conexion,$query);
while($lista = mysqli_fetch_object($resultado)){
    $id_c = $lista->id;
            ?> 
    <tr>
    <td><?php echo $lista->id;?></td>    
    <td><?php echo $lista->fecha_format." ".$lista->hora_format;?></td>
    <td><?php echo mb_detect_encoding($lista->resumen, 'UTF-8',true)?$lista->resumen:utf8_encode($lista->resumen); ;?></td> 
    <td><?php echo $lista->cit_ti;?></td>    
    <td><?php echo $lista->cit_st;?></td>
    <td><?php echo $lista->status_pago;?></td>
    <td>S/<?php echo $lista->total;?></td>
    <td>
        <a href="citas4<?php echo $archivo_seleccionado;?>.php?id_c=<?php echo $id_c;?>&id_cli=<?php echo $id_cliente;?>&id_tratamiento=<?php echo $id_tto; ?>&procedencia=historial_cita" target="_blank" class="btn-hover3" title="Editar">
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