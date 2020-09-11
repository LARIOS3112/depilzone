<?php
    include('conexionbd.php');
    date_default_timezone_get('');
    ini_set('date.timezone', 'america/lima');
$hoy = date("Y-m-d");
?>
<!DOCTYPE HTML>
<html>
	<head>
        <script src="js/custom.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name=”keywords” content=”depilacion,zonas”>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DepilZONE</title>
        <link rel="icon" type="image/png" href="imagenes/intranet-04.png" />
        
		<script type="text/javascript" src="lib/jquery/jquery.min.js"></script>
		<style type="text/css">
            
${demo.css}
		</style>
        
	</head>
	<body onload= "nobackbutton();">
        <form action="asig_estadisticas.php" method="post"> <label>Indique la estadisticas de las fechas:</label><br><label>Desde:</label><input type="date" name="desde" required><label>Hasta:</label><input type="date" name="hasta" required><input type="submit" name="buscar" value="Buscar">
    <!--Archivo utilizado para crear lo que se visualiza-->

<?php

 if (isset($_POST['buscar']) and isset($_POST['desde']) and isset($_POST['hasta'])) {
    $buscandolafecha=$_POST['buscar'];
    $fecha1=$_POST['desde'];
    $fecha2=$_POST['hasta'];?>
</form>
<?php
 } else{ 
    $buscandolafecha="";
    $fecha1=$hoy;
    $fecha2=$hoy;
 } ?>

<script src="lib/highcharts/highcharts.js"></script>
<script src="lib/highcharts/modules/exporting.js"></script>

<div id="container" style="min-width: 300px; height: 400px; margin: 0 auto"></div>

	</body>
    
    <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'ESTADISTICAS DE VENTAS POR TELEOPERADORA'
        },
        subtitle: {
            text: 'Ventas realizadas por rango'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'comisiones en soles'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Comision S/<b>{point.y:.2f} </b>'
        },
        series: [{
            name: 'Ventas',
            data: [

            <?php
            //tabla de comisiones el id 1 es el que el administrador cambiara
            $query="select comision from comisiones where id=1";
            $result=mysqli_query($conexion,$query);
            while ($comi=mysqli_fetch_array($result)) {
               $b=$comi['comision'];
            }

            $sql="select sum(a.precio) total,b.usuario from tabla_zonas a, users b,citas c,cliente d WHERE (c.fecha>='$fecha1' and c.fecha<='$fecha2') and a.id_pref=b.id and a.sesion=1 and a.id_cita=c.id and c.cod_pago_tipo=3 and c.id_cliente=d.id group by b.usuario";
            $resultado=mysqli_query($conexion,$sql);
             while($lista=mysqli_fetch_array($resultado)){
             
             ?>
                ['<?php echo $lista['usuario'];?>',
             <?php echo number_format($lista['total']*$b/100,2);?>],
                    <?php } ?>
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.2f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
});
        </script>
</html>
