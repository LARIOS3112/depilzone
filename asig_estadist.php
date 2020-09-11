<?php

include('conexionbd.php');
date_default_timezone_get('');
ini_set('date.timezone', 'america/lima');
$hoy = date("Y-m-d");

?>

<!DOCTYPE HTML>
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
        
        <script src="lib/highcharts/highcharts.js"></script>
        <script src="lib/highcharts/modules/exporting.js"></script>
        <script src="js/custom.js"></script>
    </head>
    
    <body onload="nobackbutton();">
        <form class="form-inline my-sm-2" action="asig_estadist.php" method="post">
            <div class="row justify-content-sm-start">
                
                <div class="form-group col-sm-auto">
                    <label class="mr-sm-1">Desde</label>
                    <input type="date" name="desde" class="form-control" required>
                </div>
             
                <div class="form-group pl-sm-0 col-sm-auto">
                    <label class="mr-sm-1">Hasta</label>
                    <input type="date" name="hasta" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary ml-3 ml-sm-0" title="Buscar" name="buscar"><i class="fas fa-search"></i>  Buscar</button>
            </div>
        </form>
           
        <!--Archivo utilizado para crear lo que se visualiza-->
        <?php
        if (isset($_POST['buscar']) and isset($_POST['desde']) and isset($_POST['hasta'])) {
            $buscandolafecha = $_POST['buscar'];
            $fecha1          = $_POST['desde'];
            $fecha2          = $_POST['hasta'];
        ?>
        <script>
            window.top.carga_show_hide();
        </script>
        <?php
        } 
        else{ 
            $buscandolafecha = "";
            $fecha1          = $hoy;
            $fecha2          = $hoy;
        } 
        ?>

        <div id="container" class="tam_container"></div>
     
    </body>
</html>


<script type="text/javascript">
    
    $(function(){
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Citas por Teleoperadora'
            },
            subtitle: {
                text: 'Citas realizadas en la fecha seleccionada'
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
                    text: 'Citas agendadas'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Total: <b>{point.y:.0f} citas</b>'
            },
            series: [{
                name: 'Ventas',
                data: [

                    <?php
                    $sql = "SELECT COUNT(a.id) id1, b.usuario 
                              FROM citas a, users b 
                             WHERE (a.fech_ing >= '$fecha1' AND a.fech_ing <= '$fecha2') 
                               AND a.id_preferente = b.id  
                          GROUP BY b.usuario";
                    $resultado = mysqli_query($conexion, $sql);
                    while($lista = mysqli_fetch_array($resultado)){
                    ?>
                    
                    ['<?php echo $lista['usuario'];?>',
                     <?php echo $lista['id1'];?>],
                    <?php 
                    } 
                    ?>
                ],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y:.0f}', // one decimal
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
