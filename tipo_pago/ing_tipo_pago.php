<!DOCTYPE HTML>
<html>
<head>
    <title>Ingresos Tipo Pago</title>
    <link rel="stylesheet" type="text/css" href="../bootstrap-3.3.6-dist/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="../bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.css" />
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-88784345-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>

<body>
<header class="container">
    <script>
        var int=self.setInterval("refresh()",1000);
        function refresh()
        {
            fecha = new Date();
            if( (fecha.getMinutes() == 0 && fecha.getSeconds() == 0) || (fecha.getMinutes() == 10 && fecha.getSeconds() == 0) ||
                (fecha.getMinutes() == 20 && fecha.getSeconds() == 0) || (fecha.getMinutes() == 30 && fecha.getSeconds() == 0) ||
                (fecha.getMinutes() == 40 && fecha.getSeconds() == 0) || (fecha.getMinutes() == 50 && fecha.getSeconds() == 0))
                location.reload(true);
        }
    </script>
    <nav class="navbar navbar-default">
        <div class="row">
            <div class="col-lg-12"><h1 class="text-center"><a href="http://10.95.17.114/paneles"><img src="../paris.png" width="140px" height="100px"></a>Ingresos Tipo Pago</h1></div>
        </div>

        <div class="row">
            <div class="col-lg-12"><h4 class='text-center'>
                    <?php
                    require_once '../fecha_es.php';
                    $venta = new mysqli('localhost', 'root', '', 'ventahora');

                    $query = "select hora, fecha from actualizar";

                    $res = $venta->query($query);

                    $hora = 0;

                    $fecha = 0;

                    while($row = mysqli_fetch_assoc($res)){
                        $hora = $row['hora'];
                        if(strlen($hora) < 6)
                            $hora = "0" . $hora;
                        $fecha = $row['fecha'];
                    }

                    echo "<p class='label label-success'>Última actualización hoy " . obtenerDia(date("D", strtotime("{$fecha}"))) . ", " . date("d/m/Y", strtotime("{$fecha}")) . " a las " . date("H:i", strtotime("{$hora}")) . "</p>";
                    ?>
                </h4></div>
        </div><br>

        <form class="row" method="get" action="ing_tipo_pago.php">
            <div class="col-lg-2 col-lg-offset-2 col-md-4">
                <div class="text-center"><span class="label label-primary" style="font-size: 13px;">Seleccione día actual</span></div>
                <div class="input-group date" data-provide="datepicker">
                    <input name='fecha' class="form-control" type="text" value="<?php
                    date_default_timezone_set("America/Santiago");

                    if(isset($_GET['fecha'])){
                        echo $_GET['fecha'];
                    }else {
                        echo obtenerDia(date("D")) . ", " . date("d/m/Y");
                    }
                    ?>">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <div class="text-center"><span class="label label-primary" style="font-size: 13px;">Seleccione día Anterior</span></div>
                <div class="input-group date" data-provide="datepicker">
                    <input name='anterior' class="form-control" type="text" value="<?php

                    require_once '../fechas.php';

                    if(isset($_GET['anterior']))
                        echo $_GET['anterior'];
                    else {
                        $fecAnt = fecha(date("Ymd"));
                        echo obtenerDia(date("D", strtotime("{$fecAnt}"))) . ", " . date("d/m/Y", strtotime("{$fecAnt}"));
                    }
                    ?>">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-3"><br>
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Seleccione Tipo de Panel
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="../venta/index.php">Reporte Performance Venta Hora</a></li>
                        <li><a href="../tipo_venta/ing_tipo_venta.php">Reporte Tipo Venta (Por Hora)</a></li>
                        <li><a href="../tipo_pago/ing_tipo_pago.php">Reporte Tipo Pago (Por Hora)</a></li>
                        <li><a href="../por_depto/venta_hora_deptos.php">Reporte Por Depto. (Por Hora)</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-2 col-md-2"><br>
                <button class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </nav>
</header>

<?php
require_once '../paneles.php';

if(isset($_GET['fecha']) && isset($_GET['anterior'])){
    $fecha = utf8_decode($_GET['fecha']);
    $fechaAnt = utf8_decode($_GET['anterior']);

    $fecha = str_split($fecha);
    $fecha = $fecha[11] . $fecha[12] . $fecha[13] . $fecha[14] . $fecha[8] . $fecha[9] . $fecha[5] . $fecha[6];

    $fechaAnt = str_split($fechaAnt);
    $fechaAnt = $fechaAnt[11] . $fechaAnt[12] . $fechaAnt[13] . $fechaAnt[14] . $fechaAnt[8] . $fechaAnt[9] . $fechaAnt[5] . $fechaAnt[6];

    echo '<table class="table table-condensed table-bordered table-hover">';
    echo '<thead>';
    echo '<tr>';
    echo '<th rowspan="3" style="background: white;"><h6 class="text-center"><b>Hora</b></h6></th>';
    echo '<th colspan="18" rowspan="1" style="background-color: #62AA48; color: white;"><h6 class="text-center"><b>Ingresos Tipo de Pago</b><br><br><b>
                          Día Actual ' . obtenerDia(date("D", strtotime("{$fecha}"))) . ', ' . date("d/m/Y", strtotime("{$fecha}")) . ' - Día Anterior ' . obtenerDia(date("D", strtotime("{$fechaAnt}"))) . ', ' . date("d/m/Y", strtotime("{$fechaAnt}")) . '</b></h6></th>';
    echo '</tr>';
    echo '<tr>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>CAT</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Credit Transbank</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Debit Transbank</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Gift Card</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Credit Empresa</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Total</b></h6></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '</tr>';
    echo '</thead>';
    tipopago($fecha, $fechaAnt, $venta);
    echo "</table>";

}else{
    require_once '../fechas.php';

    $fecha = date("Ymd");

    $fechaAnt = fecha($fecha);

    echo '<table class="table table-condensed table-bordered table-hover">';
    echo '<thead>';
    echo '<tr>';
    echo '<th rowspan="3" style="background: white;"><h6 class="text-center"><b>Hora</b></h6></th>';
    echo '<th colspan="18" rowspan="1" style="background-color: #62AA48; color: white;"><h6 class="text-center"><b>Ingresos Tipo de Pago</b><br><br><b>
                          Día Actual ' . obtenerDia(date("D", strtotime("{$fecha}"))) . ', ' . date("d/m/Y", strtotime("{$fecha}")) . ' - Día Anterior ' . obtenerDia(date("D", strtotime("{$fechaAnt}"))) . ', ' . date("d/m/Y", strtotime("{$fechaAnt}")) . '</b></h6></th>';
    echo '</tr>';
    echo '<tr>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>CAT</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Credit Transbank</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Debit Transbank</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Gift Card</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Credit Empresa</b></h6></td>';
    echo '<td rowspan="1" colspan="3" style="background-color: #D2D2A5;"><h6 class="text-center"><b>Total</b></h6></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Actual</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>$ Anterior</b></h6></td>';
    echo '<td style="background-color: #D2D2A5;"><h6 class="text-center"><b>% R/Past</b></h6></td>';
    echo '</tr>';
    echo '</thead>';
    tipopago($fecha, $fechaAnt, $venta);
    echo "</table>";

}
?>

<script src="../jquery-1.12.0.min.js"></script>
<script src="../bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
<script src="../bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
<script src="../bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.es.min.js"></script>
<script>
    $('.date').datepicker({
        format: 'D, dd/mm/yyyy',
        language: 'es-ES'
    });
</script>
<script>
    function mostrar(id){
        var estado = document.querySelectorAll(id);
        var cant   = estado.length;

        for(var i = 0; i < cant; i++){
            var vista = estado[i].style.display;
            if(vista == 'none')
                vista = 'table-cell';
            else
                vista = 'none';
            estado[i].style.display = vista;
        }
    }
</script>
<script src="../jquery.stickytableheaders.js"></script>
<script>
    $('table').stickyTableHeaders();
</script>
</body>
</html>