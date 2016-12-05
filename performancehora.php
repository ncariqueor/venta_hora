<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="iso-8859-1"/>
        <title>Performance Paris Internet</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
        <link rel="stylesheet" type="text/css" href="bootstrap-3.3.6-dist/css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="bootstrap-select-1.9.4/dist/css/bootstrap-select.css"/>
        <link rel="stylesheet" type="text/css" href="estilo.css" />
    </head>

    <body onload="asignar();">
        <script>
            var int=self.setInterval("refresh()",1000);
            function refresh()
            {
                fecha = new Date();
                if((fecha.getMinutes() == 2 && fecha.getSeconds() == 0) || (fecha.getMinutes() == 12 && fecha.getSeconds() == 0) ||
                    (fecha.getMinutes() == 22 && fecha.getSeconds() == 0) || (fecha.getMinutes() == 32 && fecha.getSeconds() == 0) ||
                    (fecha.getMinutes() == 42 && fecha.getSeconds() == 0) || (fecha.getMinutes() == 52 && fecha.getSeconds() == 0))
                    location.reload(true);
            }
        </script>

        <script>
            function asignar(){
                var month = document.getElementById("mes").value;
                var year = document.getElementById("anio").value;
                var day = document.getElementById("dia").value;

                var monthpast = document.getElementById("mesant").value;
                var yearpast = document.getElementById("anioant").value;
                var daypast = document.getElementById("diaant").value;

                var tipo = document.getElementById("tipo").value;
                document.getElementById("exportar").href = tipo+".php?mes="+month+"&anio="+year+"&dia="+day+"&mesant="+monthpast+"&anioant="+yearpast+"&diaant="+daypast;
            }
        </script>

        <header class="container">
            <nav class="navbar navbar-default">
                <div class="btn-group-sm">
                    <div class="row">
                        <div class="col-md-12"><h3 class="text-center"><a href="http://10.95.17.114/paneles"><img src="paris.png" width="140px" height="100px" title="Reportes Paris"></a> Panel Venta por hora</h3></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6"><h5 class="text-center text-success">Última actualización a las <?php
                                $con = new mysqli('localhost', 'root', '', 'ventahora');
                                $query = "select hora from actualizar";
                                $res = $con->query($query);
                                $hour = 0;
                                while($row = mysqli_fetch_assoc($res)){
                                    $h = $row['hora'];

                                    if(strlen($row['hora']) == 1)
                                        $h = "00000" . $h;

                                    if(strlen($row['hora']) == 2)
                                        $h = "0000" . $h;

                                    if(strlen($row['hora']) == 3)
                                        $h = "000" . $h;

                                    if(strlen($row['hora']) == 4)
                                        $h = "00" . $h;

                                    if(strlen($row['hora']) == 5)
                                        $h = "0" . $row['hora'];

                                    $h = new DateTime($h);
                                }
                                echo $h->format("H:i") . " horas";
                                ?></h5>
                        </div>

                        <div class="col-lg-6 col-md-1">
                            <a class="btn btn-default btn-sm" href="query.php" style="margin-left: 200px;">Query Venta por hora <img id="txt" src="images.png"></a>
                        </div>
                    </div><br>

                    <form action="performancehora.php" method="post" class="row">
                        <div class="col-lg-4 col-sm-6">
                            <label class="label label-primary">Día Actual</label>
                            <select name="dia" id="dia" class="selectpicker" title="Día" data-style="btn btn-default btn-sm" data-width="50px" onchange="asignar();">
                                <?php
                                    date_default_timezone_set("America/Santiago");
                                    if(isset($_POST['dia'])){
                                        $select = $_POST['dia'];
                                        $actual = date("d");

                                        $d = date("Ymd");

                                        $d = new DateTime($d);

                                        $d = $d->modify('last day of this month');

                                        for($day = 1; $day <= 31; $day++){
                                            $dia = $day;
                                            if(strlen($dia) < 2)
                                                $dia = '0'.$dia;
                                            if($select == $dia)
                                                echo "<option selected='selected' value='" . $dia . "'>" . $dia . "</option>";
                                            else
                                                echo "<option value='" . $dia . "'>" . $dia . "</option>";
                                        }
                                    }else{
                                        $actual = date("d");

                                        $d = date("Ymd");

                                        $d = new DateTime($d);

                                        $d = $d->modify('last day of this month');

                                        for($day = '01'; $day <= 31; $day++) {
                                            $dia = $day;
                                            if(strlen($dia) < 2)
                                                $dia = '0'.$dia;
                                            if ($actual == $dia)
                                                echo "<option value='" . $dia . "' selected='selected'>" . $dia . "</option>";
                                            else
                                                echo "<option value='" . $dia . "'>" . $dia . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <select name="mes" id="mes" class="selectpicker" title="Mes" data-style="btn btn-default btn-sm" data-width="100px" onchange="asignar();">
                                <?php
                                if(isset($_POST['mes'])){
                                    $mes = $_POST['mes'];
                                    if($mes == '01') {
                                        echo "<option value='01' selected='selected'>Enero</option>";
                                        echo "<option value='02'>Febrero</option>";
                                        echo "<option value='03'>Marzo</option>";
                                        echo "<option value='04'>Abril</option>";
                                        echo "<option value='05'>Mayo</option>";
                                        echo "<option value='06'>Junio</option>";
                                        echo "<option value='07'>Julio</option>";
                                        echo "<option value='08'>Agosto</option>";
                                        echo "<option value='09'>Septiembre</option>";
                                        echo "<option value='10'>Octubre</option>";
                                        echo "<option value='11'>Noviembre</option>";
                                        echo "<option value='12'>Diciembre</option>";
                                    }else{
                                        if($mes == '02'){
                                            echo "<option value='01'>Enero</option>";
                                            echo "<option value='02' selected='selected'>Febrero</option>";
                                            echo "<option value='03'>Marzo</option>";
                                            echo "<option value='04'>Abril</option>";
                                            echo "<option value='05'>Mayo</option>";
                                            echo "<option value='06'>Junio</option>";
                                            echo "<option value='07'>Julio</option>";
                                            echo "<option value='08'>Agosto</option>";
                                            echo "<option value='09'>Septiembre</option>";
                                            echo "<option value='10'>Octubre</option>";
                                            echo "<option value='11'>Noviembre</option>";
                                            echo "<option value='12'>Diciembre</option>";
                                        } else{
                                            if($mes == '03'){
                                                echo "<option value='01'>Enero</option>";
                                                echo "<option value='02'>Febrero</option>";
                                                echo "<option value='03' selected='selected'>Marzo</option>";
                                                echo "<option value='04'>Abril</option>";
                                                echo "<option value='05'>Mayo</option>";
                                                echo "<option value='06'>Junio</option>";
                                                echo "<option value='07'>Julio</option>";
                                                echo "<option value='08'>Agosto</option>";
                                                echo "<option value='09'>Septiembre</option>";
                                                echo "<option value='10'>Octubre</option>";
                                                echo "<option value='11'>Noviembre</option>";
                                                echo "<option value='12'>Diciembre</option>";
                                            }else{
                                                if($mes == '04'){
                                                    echo "<option value='01'>Enero</option>";
                                                    echo "<option value='02'>Febrero</option>";
                                                    echo "<option value='03'>Marzo</option>";
                                                    echo "<option value='04' selected='selected'>Abril</option>";
                                                    echo "<option value='05'>Mayo</option>";
                                                    echo "<option value='06'>Junio</option>";
                                                    echo "<option value='07'>Julio</option>";
                                                    echo "<option value='08'>Agosto</option>";
                                                    echo "<option value='09'>Septiembre</option>";
                                                    echo "<option value='10'>Octubre</option>";
                                                    echo "<option value='11'>Noviembre</option>";
                                                    echo "<option value='12'>Diciembre</option>";
                                                }else{
                                                    if($mes == '05'){
                                                        echo "<option value='01'>Enero</option>";
                                                        echo "<option value='02'>Febrero</option>";
                                                        echo "<option value='03'>Marzo</option>";
                                                        echo "<option value='04'>Abril</option>";
                                                        echo "<option value='05' selected='selected'>Mayo</option>";
                                                        echo "<option value='06'>Junio</option>";
                                                        echo "<option value='07'>Julio</option>";
                                                        echo "<option value='08'>Agosto</option>";
                                                        echo "<option value='09'>Septiembre</option>";
                                                        echo "<option value='10'>Octubre</option>";
                                                        echo "<option value='11'>Noviembre</option>";
                                                        echo "<option value='12'>Diciembre</option>";
                                                    }else{
                                                        if($mes == '06'){
                                                            echo "<option value='01'>Enero</option>";
                                                            echo "<option value='02'>Febrero</option>";
                                                            echo "<option value='03'>Marzo</option>";
                                                            echo "<option value='04'>Abril</option>";
                                                            echo "<option value='05'>Mayo</option>";
                                                            echo "<option value='06' selected='selected'>Junio</option>";
                                                            echo "<option value='07'>Julio</option>";
                                                            echo "<option value='08'>Agosto</option>";
                                                            echo "<option value='09'>Septiembre</option>";
                                                            echo "<option value='10'>Octubre</option>";
                                                            echo "<option value='11'>Noviembre</option>";
                                                            echo "<option value='12'>Diciembre</option>";
                                                        }else{
                                                            if($mes == '07'){
                                                                echo "<option value='01'>Enero</option>";
                                                                echo "<option value='02'>Febrero</option>";
                                                                echo "<option value='03'>Marzo</option>";
                                                                echo "<option value='04'>Abril</option>";
                                                                echo "<option value='05'>Mayo</option>";
                                                                echo "<option value='06'>Junio</option>";
                                                                echo "<option value='07' selected='selected'>Julio</option>";
                                                                echo "<option value='08'>Agosto</option>";
                                                                echo "<option value='09'>Septiembre</option>";
                                                                echo "<option value='10'>Octubre</option>";
                                                                echo "<option value='11'>Noviembre</option>";
                                                                echo "<option value='12'>Diciembre</option>";
                                                            }else{
                                                                if($mes == '08'){
                                                                    echo "<option value='01'>Enero</option>";
                                                                    echo "<option value='02'>Febrero</option>";
                                                                    echo "<option value='03'>Marzo</option>";
                                                                    echo "<option value='04'>Abril</option>";
                                                                    echo "<option value='05'>Mayo</option>";
                                                                    echo "<option value='06'>Junio</option>";
                                                                    echo "<option value='07'>Julio</option>";
                                                                    echo "<option value='08' selected='selected'>Agosto</option>";
                                                                    echo "<option value='09'>Septiembre</option>";
                                                                    echo "<option value='10'>Octubre</option>";
                                                                    echo "<option value='11'>Noviembre</option>";
                                                                    echo "<option value='12'>Diciembre</option>";
                                                                }else{
                                                                    if($mes == '09'){
                                                                        echo "<option value='01'>Enero</option>";
                                                                        echo "<option value='02'>Febrero</option>";
                                                                        echo "<option value='03'>Marzo</option>";
                                                                        echo "<option value='04'>Abril</option>";
                                                                        echo "<option value='05'>Mayo</option>";
                                                                        echo "<option value='06'>Junio</option>";
                                                                        echo "<option value='07'>Julio</option>";
                                                                        echo "<option value='08'>Agosto</option>";
                                                                        echo "<option value='09' selected='selected'>Septiembre</option>";
                                                                        echo "<option value='10'>Octubre</option>";
                                                                        echo "<option value='11'>Noviembre</option>";
                                                                        echo "<option value='12'>Diciembre</option>";
                                                                    }else{
                                                                        if($mes == '10'){
                                                                            echo "<option value='01'>Enero</option>";
                                                                            echo "<option value='02'>Febrero</option>";
                                                                            echo "<option value='03'>Marzo</option>";
                                                                            echo "<option value='04'>Abril</option>";
                                                                            echo "<option value='05'>Mayo</option>";
                                                                            echo "<option value='06'>Junio</option>";
                                                                            echo "<option value='07'>Julio</option>";
                                                                            echo "<option value='08'>Agosto</option>";
                                                                            echo "<option value='09'>Septiembre</option>";
                                                                            echo "<option value='10' selected='selected'>Octubre</option>";
                                                                            echo "<option value='11'>Noviembre</option>";
                                                                            echo "<option value='12'>Diciembre</option>";
                                                                        }else{
                                                                            if($mes == '11'){
                                                                                echo "<option value='01'>Enero</option>";
                                                                                echo "<option value='02'>Febrero</option>";
                                                                                echo "<option value='03'>Marzo</option>";
                                                                                echo "<option value='04'>Abril</option>";
                                                                                echo "<option value='05'>Mayo</option>";
                                                                                echo "<option value='06'>Junio</option>";
                                                                                echo "<option value='07'>Julio</option>";
                                                                                echo "<option value='08'>Agosto</option>";
                                                                                echo "<option value='09'>Septiembre</option>";
                                                                                echo "<option value='10'>Octubre</option>";
                                                                                echo "<option value='11' selected='selected'>Noviembre</option>";
                                                                                echo "<option value='12'>Diciembre</option>";
                                                                            }else{
                                                                                if($mes == '12'){
                                                                                    echo "<option value='01'>Enero</option>";
                                                                                    echo "<option value='02'>Febrero</option>";
                                                                                    echo "<option value='03'>Marzo</option>";
                                                                                    echo "<option value='04'>Abril</option>";
                                                                                    echo "<option value='05'>Mayo</option>";
                                                                                    echo "<option value='06'>Junio</option>";
                                                                                    echo "<option value='07'>Julio</option>";
                                                                                    echo "<option value='08'>Agosto</option>";
                                                                                    echo "<option value='09'>Septiembre</option>";
                                                                                    echo "<option value='10'>Octubre</option>";
                                                                                    echo "<option value='11'>Noviembre</option>";
                                                                                    echo "<option value='12' selected='selected'>Diciembre</option>";
                                                                                }else{
                                                                                    echo "<option value='01'>Enero</option>";
                                                                                    echo "<option value='02'>Febrero</option>";
                                                                                    echo "<option value='03'>Marzo</option>";
                                                                                    echo "<option value='04'>Abril</option>";
                                                                                    echo "<option value='05'>Mayo</option>";
                                                                                    echo "<option value='06'>Junio</option>";
                                                                                    echo "<option value='07'>Julio</option>";
                                                                                    echo "<option value='08'>Agosto</option>";
                                                                                    echo "<option value='09'>Septiembre</option>";
                                                                                    echo "<option value='10'>Octubre</option>";
                                                                                    echo "<option value='11'>Noviembre</option>";
                                                                                    echo "<option value='12'>Diciembre</option>";
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    if(date("m") == '01')
                                        echo "<option value='01' selected='selected'>Enero</option>";
                                    else
                                        echo "<option value='01'>Enero</option>";

                                    if(date("m") === '02')
                                        echo "<option value='02' selected='selected'>Febrero</option>";
                                    else
                                        echo "<option value='02'>Febrero</option>";

                                    if(date("m") == '03')
                                        echo "<option value='03' selected='selected'>Marzo</option>";
                                    else
                                        echo "<option value='03'>Marzo</option>";

                                    if(date("m") == '04')
                                        echo "<option value='04' selected='selected'>Abril</option>";
                                    else
                                        echo "<option value='04'>Abril</option>";

                                    if(date("m") == '05')
                                        echo "<option value='05' selected='selected'>Mayo</option>";
                                    else
                                        echo "<option value='05'>Mayo</option>";

                                    if(date("m") == '06')
                                        echo "<option value='06' selected='selected'>Junio</option>";
                                    else
                                        echo "<option value='06'>Junio</option>";

                                    if(date("m") == '07')
                                        echo "<option value='07' selected='selected'>Julio</option>";
                                    else
                                        echo "<option value='07'>Julio</option>";

                                    if(date("m") == '08')
                                        echo "<option value='08' selected='selected'>Agosto</option>";
                                    else
                                        echo "<option value='08'>Agosto</option>";

                                    if(date("m") == '09')
                                        echo "<option value='09' selected='selected'>Septiembre</option>";
                                    else
                                        echo "<option value='09'>Septiembre</option>";

                                    if(date("m") == '10')
                                        echo "<option value='10' selected='selected'>Octubre</option>";
                                    else
                                        echo "<option value='10'>Octubre</option>";

                                    if(date("m") == '11')
                                        echo "<option value='11' selected='selected'>Noviembre</option>";
                                    else
                                        echo "<option value='11'>Noviembre</option>";

                                    if(date("m") == '12')
                                        echo "<option value='12' selected='selected'>Diciembre</option>";
                                    else
                                        echo "<option value='12'>Diciembre</option>";
                                }
                                ?>
                            </select>
                            <select name="anio" id="anio" class="selectpicker" title="Año" data-style="btn btn-default btn-sm" data-width="70px" onchange="asignar();">
                                <?php
                                if(isset($_POST['anio'])){
                                    $anio = $_POST['anio'];
                                    $actual = date("Y");
                                    for($dia = 2015; $dia <= $actual; $dia++){
                                        if($anio == $dia)
                                            echo "<option selected='selected' value='" . $dia . "'>" . $dia . "</option>";
                                        else
                                            echo "<option value='" . $dia . "'>" . $dia . "</option>";
                                    }
                                }else{
                                    $actual = date("Y");
                                    for($dia = 2015; $dia <= $actual; $dia++) {
                                        if (date("Y") == $dia)
                                            echo "<option value='" . $dia . "' selected='selected'>" . $dia . "</option>";
                                        else
                                            echo "<option value='" . $dia . "'>" . $dia . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <label class="label label-primary">Día Anterior</label>
                            <select name="diaant" id="diaant" class="selectpicker" title="Día" data-style="btn btn-default btn-sm" data-width="50px" onchange="asignar();">
                                <?php
                                date_default_timezone_set("America/Santiago");
                                require_once 'fechas.php';
                                $actual = date("Ymd");
                                $actual = fecha($actual);
                                $actual = new DateTime($actual);
                                if(isset($_POST['diaant'])){
                                    $select = $_POST['diaant'];
                                    $actual = date("d");

                                    $d = date("Ymd");

                                    $d = new DateTime($d);

                                    $d = $d->modify('last day of this month');

                                    for($day = 1; $day <= 31; $day++){
                                        $dia = $day;
                                        if(strlen($dia) < 2)
                                            $dia = '0'.$dia;
                                        if($select == $dia)
                                            echo "<option selected='selected' value='" . $dia . "'>" . $dia . "</option>";
                                        else
                                            echo "<option value='" . $dia . "'>" . $dia . "</option>";
                                    }
                                }else{
                                    $d = date("Ymd");

                                    $d = new DateTime($d);

                                    $d = $d->modify('last day of this month');

                                    for($day = '01'; $day <= 31; $day++) {
                                        $dia = $day;
                                        if(strlen($dia) < 2)
                                            $dia = '0'.$dia;
                                        if ($actual->format("d") == $dia)
                                            echo "<option value='" . $dia . "' selected='selected'>" . $dia . "</option>";
                                        else
                                            echo "<option value='" . $dia . "'>" . $dia . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <select name="mesant" id="mesant" class="selectpicker" title="Mes" data-style="btn btn-default btn-sm" data-width="100px" onchange="asignar();">
                                <?php
                                if(isset($_POST['mesant'])){
                                    $mes = $_POST['mesant'];
                                    if($mes == '01') {
                                        echo "<option value='01' selected='selected'>Enero</option>";
                                        echo "<option value='02'>Febrero</option>";
                                        echo "<option value='03'>Marzo</option>";
                                        echo "<option value='04'>Abril</option>";
                                        echo "<option value='05'>Mayo</option>";
                                        echo "<option value='06'>Junio</option>";
                                        echo "<option value='07'>Julio</option>";
                                        echo "<option value='08'>Agosto</option>";
                                        echo "<option value='09'>Septiembre</option>";
                                        echo "<option value='10'>Octubre</option>";
                                        echo "<option value='11'>Noviembre</option>";
                                        echo "<option value='12'>Diciembre</option>";
                                    }else{
                                        if($mes == '02'){
                                            echo "<option value='01'>Enero</option>";
                                            echo "<option value='02' selected='selected'>Febrero</option>";
                                            echo "<option value='03'>Marzo</option>";
                                            echo "<option value='04'>Abril</option>";
                                            echo "<option value='05'>Mayo</option>";
                                            echo "<option value='06'>Junio</option>";
                                            echo "<option value='07'>Julio</option>";
                                            echo "<option value='08'>Agosto</option>";
                                            echo "<option value='09'>Septiembre</option>";
                                            echo "<option value='10'>Octubre</option>";
                                            echo "<option value='11'>Noviembre</option>";
                                            echo "<option value='12'>Diciembre</option>";
                                        } else{
                                            if($mes == '03'){
                                                echo "<option value='01'>Enero</option>";
                                                echo "<option value='02'>Febrero</option>";
                                                echo "<option value='03' selected='selected'>Marzo</option>";
                                                echo "<option value='04'>Abril</option>";
                                                echo "<option value='05'>Mayo</option>";
                                                echo "<option value='06'>Junio</option>";
                                                echo "<option value='07'>Julio</option>";
                                                echo "<option value='08'>Agosto</option>";
                                                echo "<option value='09'>Septiembre</option>";
                                                echo "<option value='10'>Octubre</option>";
                                                echo "<option value='11'>Noviembre</option>";
                                                echo "<option value='12'>Diciembre</option>";
                                            }else{
                                                if($mes == '04'){
                                                    echo "<option value='01'>Enero</option>";
                                                    echo "<option value='02'>Febrero</option>";
                                                    echo "<option value='03'>Marzo</option>";
                                                    echo "<option value='04' selected='selected'>Abril</option>";
                                                    echo "<option value='05'>Mayo</option>";
                                                    echo "<option value='06'>Junio</option>";
                                                    echo "<option value='07'>Julio</option>";
                                                    echo "<option value='08'>Agosto</option>";
                                                    echo "<option value='09'>Septiembre</option>";
                                                    echo "<option value='10'>Octubre</option>";
                                                    echo "<option value='11'>Noviembre</option>";
                                                    echo "<option value='12'>Diciembre</option>";
                                                }else{
                                                    if($mes == '05'){
                                                        echo "<option value='01'>Enero</option>";
                                                        echo "<option value='02'>Febrero</option>";
                                                        echo "<option value='03'>Marzo</option>";
                                                        echo "<option value='04'>Abril</option>";
                                                        echo "<option value='05' selected='selected'>Mayo</option>";
                                                        echo "<option value='06'>Junio</option>";
                                                        echo "<option value='07'>Julio</option>";
                                                        echo "<option value='08'>Agosto</option>";
                                                        echo "<option value='09'>Septiembre</option>";
                                                        echo "<option value='10'>Octubre</option>";
                                                        echo "<option value='11'>Noviembre</option>";
                                                        echo "<option value='12'>Diciembre</option>";
                                                    }else{
                                                        if($mes == '06'){
                                                            echo "<option value='01'>Enero</option>";
                                                            echo "<option value='02'>Febrero</option>";
                                                            echo "<option value='03'>Marzo</option>";
                                                            echo "<option value='04'>Abril</option>";
                                                            echo "<option value='05'>Mayo</option>";
                                                            echo "<option value='06' selected='selected'>Junio</option>";
                                                            echo "<option value='07'>Julio</option>";
                                                            echo "<option value='08'>Agosto</option>";
                                                            echo "<option value='09'>Septiembre</option>";
                                                            echo "<option value='10'>Octubre</option>";
                                                            echo "<option value='11'>Noviembre</option>";
                                                            echo "<option value='12'>Diciembre</option>";
                                                        }else{
                                                            if($mes == '07'){
                                                                echo "<option value='01'>Enero</option>";
                                                                echo "<option value='02'>Febrero</option>";
                                                                echo "<option value='03'>Marzo</option>";
                                                                echo "<option value='04'>Abril</option>";
                                                                echo "<option value='05'>Mayo</option>";
                                                                echo "<option value='06'>Junio</option>";
                                                                echo "<option value='07' selected='selected'>Julio</option>";
                                                                echo "<option value='08'>Agosto</option>";
                                                                echo "<option value='09'>Septiembre</option>";
                                                                echo "<option value='10'>Octubre</option>";
                                                                echo "<option value='11'>Noviembre</option>";
                                                                echo "<option value='12'>Diciembre</option>";
                                                            }else{
                                                                if($mes == '08'){
                                                                    echo "<option value='01'>Enero</option>";
                                                                    echo "<option value='02'>Febrero</option>";
                                                                    echo "<option value='03'>Marzo</option>";
                                                                    echo "<option value='04'>Abril</option>";
                                                                    echo "<option value='05'>Mayo</option>";
                                                                    echo "<option value='06'>Junio</option>";
                                                                    echo "<option value='07'>Julio</option>";
                                                                    echo "<option value='08' selected='selected'>Agosto</option>";
                                                                    echo "<option value='09'>Septiembre</option>";
                                                                    echo "<option value='10'>Octubre</option>";
                                                                    echo "<option value='11'>Noviembre</option>";
                                                                    echo "<option value='12'>Diciembre</option>";
                                                                }else{
                                                                    if($mes == '09'){
                                                                        echo "<option value='01'>Enero</option>";
                                                                        echo "<option value='02'>Febrero</option>";
                                                                        echo "<option value='03'>Marzo</option>";
                                                                        echo "<option value='04'>Abril</option>";
                                                                        echo "<option value='05'>Mayo</option>";
                                                                        echo "<option value='06'>Junio</option>";
                                                                        echo "<option value='07'>Julio</option>";
                                                                        echo "<option value='08'>Agosto</option>";
                                                                        echo "<option value='09' selected='selected'>Septiembre</option>";
                                                                        echo "<option value='10'>Octubre</option>";
                                                                        echo "<option value='11'>Noviembre</option>";
                                                                        echo "<option value='12'>Diciembre</option>";
                                                                    }else{
                                                                        if($mes == '10'){
                                                                            echo "<option value='01'>Enero</option>";
                                                                            echo "<option value='02'>Febrero</option>";
                                                                            echo "<option value='03'>Marzo</option>";
                                                                            echo "<option value='04'>Abril</option>";
                                                                            echo "<option value='05'>Mayo</option>";
                                                                            echo "<option value='06'>Junio</option>";
                                                                            echo "<option value='07'>Julio</option>";
                                                                            echo "<option value='08'>Agosto</option>";
                                                                            echo "<option value='09'>Septiembre</option>";
                                                                            echo "<option value='10' selected='selected'>Octubre</option>";
                                                                            echo "<option value='11'>Noviembre</option>";
                                                                            echo "<option value='12'>Diciembre</option>";
                                                                        }else{
                                                                            if($mes == '11'){
                                                                                echo "<option value='01'>Enero</option>";
                                                                                echo "<option value='02'>Febrero</option>";
                                                                                echo "<option value='03'>Marzo</option>";
                                                                                echo "<option value='04'>Abril</option>";
                                                                                echo "<option value='05'>Mayo</option>";
                                                                                echo "<option value='06'>Junio</option>";
                                                                                echo "<option value='07'>Julio</option>";
                                                                                echo "<option value='08'>Agosto</option>";
                                                                                echo "<option value='09'>Septiembre</option>";
                                                                                echo "<option value='10'>Octubre</option>";
                                                                                echo "<option value='11' selected='selected'>Noviembre</option>";
                                                                                echo "<option value='12'>Diciembre</option>";
                                                                            }else{
                                                                                if($mes == '12'){
                                                                                    echo "<option value='01'>Enero</option>";
                                                                                    echo "<option value='02'>Febrero</option>";
                                                                                    echo "<option value='03'>Marzo</option>";
                                                                                    echo "<option value='04'>Abril</option>";
                                                                                    echo "<option value='05'>Mayo</option>";
                                                                                    echo "<option value='06'>Junio</option>";
                                                                                    echo "<option value='07'>Julio</option>";
                                                                                    echo "<option value='08'>Agosto</option>";
                                                                                    echo "<option value='09'>Septiembre</option>";
                                                                                    echo "<option value='10'>Octubre</option>";
                                                                                    echo "<option value='11'>Noviembre</option>";
                                                                                    echo "<option value='12' selected='selected'>Diciembre</option>";
                                                                                }else{
                                                                                    echo "<option value='01'>Enero</option>";
                                                                                    echo "<option value='02'>Febrero</option>";
                                                                                    echo "<option value='03'>Marzo</option>";
                                                                                    echo "<option value='04'>Abril</option>";
                                                                                    echo "<option value='05'>Mayo</option>";
                                                                                    echo "<option value='06'>Junio</option>";
                                                                                    echo "<option value='07'>Julio</option>";
                                                                                    echo "<option value='08'>Agosto</option>";
                                                                                    echo "<option value='09'>Septiembre</option>";
                                                                                    echo "<option value='10'>Octubre</option>";
                                                                                    echo "<option value='11'>Noviembre</option>";
                                                                                    echo "<option value='12'>Diciembre</option>";
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    if($actual->format("m") == '01')
                                        echo "<option value='01' selected='selected'>Enero</option>";
                                    else
                                        echo "<option value='01'>Enero</option>";

                                    if($actual->format("m") === '02')
                                        echo "<option value='02' selected='selected'>Febrero</option>";
                                    else
                                        echo "<option value='02'>Febrero</option>";

                                    if($actual->format("m") == '03')
                                        echo "<option value='03' selected='selected'>Marzo</option>";
                                    else
                                        echo "<option value='03'>Marzo</option>";

                                    if($actual->format("m") == '04')
                                        echo "<option value='04' selected='selected'>Abril</option>";
                                    else
                                        echo "<option value='04'>Abril</option>";

                                    if($actual->format("m") == '05')
                                        echo "<option value='05' selected='selected'>Mayo</option>";
                                    else
                                        echo "<option value='05'>Mayo</option>";

                                    if($actual->format("m") == '06')
                                        echo "<option value='06' selected='selected'>Junio</option>";
                                    else
                                        echo "<option value='06'>Junio</option>";

                                    if($actual->format("m") == '07')
                                        echo "<option value='07' selected='selected'>Julio</option>";
                                    else
                                        echo "<option value='07'>Julio</option>";

                                    if($actual->format("m") == '08')
                                        echo "<option value='08' selected='selected'>Agosto</option>";
                                    else
                                        echo "<option value='08'>Agosto</option>";

                                    if($actual->format("m") == '09')
                                        echo "<option value='09' selected='selected'>Septiembre</option>";
                                    else
                                        echo "<option value='09'>Septiembre</option>";

                                    if($actual->format("m") == '10')
                                        echo "<option value='10' selected='selected'>Octubre</option>";
                                    else
                                        echo "<option value='10'>Octubre</option>";

                                    if($actual->format("m") == '11')
                                        echo "<option value='11' selected='selected'>Noviembre</option>";
                                    else
                                        echo "<option value='11'>Noviembre</option>";

                                    if($actual->format("m") == '12')
                                        echo "<option value='12' selected='selected'>Diciembre</option>";
                                    else
                                        echo "<option value='12'>Diciembre</option>";
                                }
                                ?>
                            </select>
                            <select name="anioant" id="anioant" class="selectpicker" title="Año" data-style="btn btn-default btn-sm" data-width="70px" onchange="asignar();">
                                <?php

                                if(isset($_POST['anioant'])){
                                    $anio = $_POST['anioant'];
                                    $actual = date("Y");
                                    for($dia = 2014; $dia <= $actual; $dia++){
                                        if($anio == $dia)
                                            echo "<option selected='selected' value='" . $dia . "'>" . $dia . "</option>";
                                        else
                                            echo "<option value='" . $dia . "'>" . $dia . "</option>";
                                    }
                                }else{
                                    $ac = date("Y");
                                    for($dia = 2014; $dia <= $ac; $dia++) {
                                        if ($actual->format("Y") == $dia)
                                            echo "<option value='" . $dia . "' selected='selected'>" . $dia . "</option>";
                                        else
                                            echo "<option value='" . $dia . "'>" . $dia . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-sm-4">
                            <select name="tipo" id="tipo" class="selectpicker" title="Tipo Panel" data-style="btn btn-default btn-sm" data-width="154px" onchange="asignar();">
                                <?php
                                if(isset($_POST['tipo'])){
                                    $tipo = $_POST['tipo'];
                                    if($tipo == 'ventas'){
                                        echo "<option value='ventas' selected='selected'>Panel de Ingresos</option>";
                                        echo "<option value='tipoventa'>Panel por Tipo Venta</option>";
                                        echo "<option value='tipopago'>Panel por Tipo Pago</option>";
                                        echo "<option value='indicadores'>Panel de Indicadores</option>";
                                    }else{
                                        if($tipo == 'tipoventa'){
                                            echo "<option value='ventas'>Panel de Ingresos</option>";
                                            echo "<option value='tipoventa' selected='selected'>Panel por Tipo Venta</option>";
                                            echo "<option value='tipopago'>Panel por Tipo Pago</option>";
                                            echo "<option value='indicadores'>Panel de Indicadores</option>";
                                        }else{
                                            if($tipo == 'tipopago'){
                                                echo "<option value='ventas'>Panel de Ingresos</option>";
                                                echo "<option value='tipoventa'>Panel por Tipo Venta</option>";
                                                echo "<option value='tipopago' selected='selected'>Panel por Tipo Pago</option>";
                                                echo "<option value='indicadores'>Panel de Indicadores</option>";
                                            }else{
                                                if($tipo == 'indicadores'){
                                                    echo "<option value='ventas'>Panel de Ingresos</option>";
                                                    echo "<option value='tipoventa'>Panel por Tipo Venta</option>";
                                                    echo "<option value='tipopago'>Panel por Tipo Pago</option>";
                                                    echo "<option value='indicadores' selected='selected'>Panel de Indicadores</option>";
                                                }else {
                                                    echo "<option value='ventas'>Panel de Ingresos</option>";
                                                    echo "<option value='tipoventa'>Panel por Tipo Venta</option>";
                                                    echo "<option value='tipopago'>Panel por Tipo Pago</option>";
                                                    echo "<option value='indicadores'>Panel de Indicadores</option>";
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    echo "<option value='ventas' selected='selected'>Panel de Ingresos</option>";
                                    echo "<option value='tipoventa'>Panel por  Tipo Venta</option>";
                                    echo "<option value='tipopago'>Panel por Tipo Pago</option>";
                                    echo "<option value='indicadores'>Panel de Indicadores</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-1 col-sm-4">
                            <button class="btn btn-primary btn-sm" style="width: 100px;">Actualizar</button>
                        </div>

                        <div class="col-lg-1 col-sm-4">
                            <a href="#" id="exportar" class="btn btn-success btn-sm">Exportar</a>
                        </div>



                    </form>
                </div>
            </nav>
        </header>

        <?php
        require_once 'fechas.php';

        function diasem($diasem){
            if($diasem == 'Mon')
                return 'Lunes';
            if($diasem == 'Tue')
                return 'Martes';
            if($diasem == 'Wed')
                return 'Miércoles';
            if($diasem == 'Thu')
                return 'Jueves';
            if($diasem == 'Fri')
                return 'Viernes';
            if($diasem == 'Sat')
                return 'Sábado';
            if($diasem == 'Sun')
                return 'Domingo';
        }

        if(isset($_POST['dia']) && isset($_POST['mes']) && isset($_POST['anio'])){
            $dia = $_POST['dia'];
            $mes = $_POST['mes'];
            $anio = $_POST['anio'];

            $day = $anio . $mes . $dia;

            $day = new DateTime($day);
        }

        if(isset($_POST['diaant']) && isset($_POST['mesant']) && isset($_POST['anioant'])){
            $dia = $_POST['diaant'];
            $mes = $_POST['mesant'];
            $anio = $_POST['anioant'];

            $dayp = $anio . $mes . $dia;

            $dayp = new DateTime($dayp);
        }

        if(!isset($_POST['dia']) && !isset($_POST['mes']) && !isset($_POST['anio']) && !isset($_POST['diaant']) && !isset($_POST['mesant']) && !isset($_POST['anioant'])){
            $day = date("Ymd");

            $dayp = fecha($day);

            $day = new DateTime($day);

            $dayp = new DateTime($dayp);
        }

        if(isset($_POST['tipo'])){
            $tipo = $_POST['tipo'];
            if($tipo == 'ventas') {
                echo '<div id="ventas">';
                echo '<table class="table table-condensed table-bordered table-hover">';
                echo '<thead>';
                echo '<tr>';
                echo '<th rowspan="1" colspan="14" style="background-color: #337ab7; color: white; border-right-width: 5px; border-right-color: white;">
                      <h6 class="text-center"><b>Día Actual<br><br>' . diasem($day->format("D")) . ", " . $day->format("d-m-Y") . '</b></h6></th>';
                echo '<th colspan="2" style="background-color: #5A82D7; color: white;">
                      <h6 class="text-center"><b>Día Anterior<br><br>' . diasem($dayp->format("D")) . ", " . $dayp->format("d-m-Y") . '</br></h6></th>';
                echo '<th rowspan="3" style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>% R/Past</b></h6></th>';
                echo '<th rowspan="3" style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>% Peso <br>acumulada</br></h6></th>';
                echo '</tr>';

                echo '<tr>';
                echo '<th rowspan="2" colspan="1" style="background-color: #337ab7; color: white;"><h6 class="text-center"><b>Hora</b></h6></th>';
                echo '<th colspan="2" style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Ingreso Bruto</b></h6></th>';
                echo '<th colspan="2" style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Click & Collect</b></h6></th>';
                echo '<th colspan="2" style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Pendiente Validación</br></h6></th>';
                echo '<th colspan="2" style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Anulaciones</b></h6></th>';
                echo '<th colspan="2" style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Novios</b></h6></th>';
                echo '<th colspan="3" style="background-color: #337ab7; color: white; border-right-width: 5px; border-right-color: white;"><h6 class="text-center"><b>Ingreso Neto (Sin IVA)</b></h6></th>';

                echo '<th colspan="2" style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>Ingreso Neto (Sin IVA)</b></h6></th>';
                echo '</tr>';

                echo '<tr>';
                echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
                echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
                echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
                echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
                echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
                echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
                echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
                echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
                echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
                echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
                echo '<th style="background-color: #337ab7; color: white;"><h6 class="text-center"><b>Monto por Hora $</b></h6></th>';
                echo '<th style="background-color: #337ab7; color: white;"><h6 class="text-center"><b>Monto Acumulado $</b></h6></th>';
                echo '<th style="background-color: #337ab7; color: white; border-right-width: 5px; border-right-color: white;""><h6 class="text-center"><b>#</b></h6></th>';

                echo '<th style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>Monto Acumulado $</b></h6></th>';
                echo '<th style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
                echo '</tr>';
                echo '</thead>';

                if (isset($_POST['dia']) && isset($_POST['mes']) && isset($_POST['anio']) && isset($_POST['diaant']) && isset($_POST['mesant']) && isset($_POST['anioant'])) {
                    $dia = $_POST['dia'];
                    $mes = $_POST['mes'];
                    $anio = $_POST['anio'];
                    $buscaract = $anio . $mes . $dia;

                    $dia = $_POST['diaant'];
                    $mes = $_POST['mesant'];
                    $anio = $_POST['anioant'];
                    $buscarant = $anio . $mes . $dia;

                    $con = new mysqli('localhost', 'root', '', 'ventahora');

                    $total = "select mingresobrutoacum, ordingresobrutoacum, mclickacum, ordclickacum, mpendacum, ordpendacum,
                                     manulacum, ordanulacum, mnoviosacum, ordnoviosacum, mingresonetoacum, ordingresonetoacum

                              from resultadosp1

                              where diaactual = $buscaract and mingresonetoacum <> 0 order by inicio desc limit 1";

                    $res = $con->query($total);

                    $mingresobrutoacum = 0;
                    $ordingresobrutoacum = 0;
                    $mclickacum = 0;
                    $ordclickacum = 0;
                    $mpendacum = 0;
                    $ordpendacum = 0;
                    $manulacum = 0;
                    $ordanulacum = 0;
                    $mnoviosacum = 0;
                    $ordnoviosacum = 0;
                    $mingresonetoacum = 0;
                    $ordingresonetoacum = 0;

                    while($row = mysqli_fetch_assoc($res)){
                        $mingresobrutoacum   = $row['mingresobrutoacum'];
                        $ordingresobrutoacum = $row['ordingresobrutoacum'];
                        $mclickacum          = $row['mclickacum'];
                        $ordclickacum        = $row['ordclickacum'];
                        $mpendacum           = $row['mpendacum'];
                        $ordpendacum         = $row['ordpendacum'];
                        $manulacum           = $row['manulacum'];
                        $ordanulacum         = $row['ordanulacum'];
                        $mnoviosacum         = $row['mnoviosacum'];
                        $ordnoviosacum       = $row['ordnoviosacum'];
                        $mingresonetoacum    = $row['mingresonetoacum'];
                        $ordingresonetoacum  = $row['ordingresonetoacum'];
                    }

                    $total = "select mingresonetoacum, ordingresonetoacum

                              from resultadosp1

                              where diaactual = $buscarant and mingresonetoacum <> 0 order by inicio desc limit 1";

                    $res = $con->query($total);

                    $mingresonetopacum   = 0;
                    $ordingresonetopacum = 0;

                    while($row = mysqli_fetch_assoc($res)){
                        $mingresonetopacum   = $row['mingresonetoacum'];
                        $ordingresonetopacum = $row['ordingresonetoacum'];
                    }

                    $rpast            = 0;
                    if($mingresonetopacum != 0)
                        $rpast        = round((($mingresonetoacum/$mingresonetopacum)-1)*100);

                    if($mingresobrutoacum == 0)
                        $mingresobrutoacum = '-';
                    else
                        $mingresobrutoacum = number_format($mingresobrutoacum, 0, ',', '.');

                    if($ordingresobrutoacum == 0)
                        $ordingresobrutoacum = '-';
                    else
                        $ordingresobrutoacum = number_format($ordingresobrutoacum, 0, ',', '.');

                    if($mclickacum == 0)
                        $mclickacum = '-';
                    else
                        $mclickacum = number_format($mclickacum, 0, ',', '.');

                    if($ordclickacum == 0)
                        $ordclickacum = '-';
                    else
                        $ordclickacum = number_format($ordclickacum, 0, ',', '.');

                    if($mpendacum == 0)
                        $mpendacum = '-';
                    else
                        $mpendacum = number_format($mpendacum, 0, ',', '.');

                    if($ordpendacum == 0)
                        $ordpendacum = '-';
                    else
                        $ordpendacum = number_format($ordpendacum, 0, ',', '.');

                    if($manulacum == 0)
                        $manulacum = '-';
                    else
                        $manulacum = number_format($manulacum, 0, ',', '.');

                    if($ordanulacum == 0)
                        $ordanulacum = '-';
                    else
                        $ordanulacum = number_format($ordanulacum, 0, ',', '.');

                    if($mnoviosacum == 0)
                        $mnoviosacum = '-';
                    else
                        $mnoviosacum = number_format($mnoviosacum, 0, ',', '.');

                    if($ordnoviosacum == 0)
                        $ordnoviosacum = '-';
                    else
                        $ordnoviosacum = number_format($ordnoviosacum, 0, ',', '.');

                    if($mingresonetoacum == 0)
                        $mingresonetoacum = '-';
                    else
                        $mingresonetoacum = number_format($mingresonetoacum, 0, ',', '.');

                    if($ordingresonetoacum == 0)
                        $ordingresonetoacum = '-';
                    else
                        $ordingresonetoacum = number_format($ordingresonetoacum, 0, ',', '.');

                    if ($rpast > 0)
                        $color = 'label label-success';

                    if ($rpast == 0)
                        $color = 'label label-warning';

                    if ($rpast < 0)
                        $color = 'label label-danger';


                    echo "<tr><td style='border-right-color: black; background-color: #C3CEFF;' nowrap='100px' class='text-center'><h6 class='text-center'><b>Total Actual</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mingresobrutoacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordingresobrutoacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mclickacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordclickacum . "</b></h6></td>";

                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mpendacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordpendacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $manulacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordanulacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mnoviosacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordnoviosacum . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>-</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mingresonetoacum . "</b></h6></td>";
                    echo "<td style='border-right-width: 5px; border-right-color: white; background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordingresonetoacum . "</b></h6></td>";

                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mingresonetopacum, 0, ',', '.') . "</b></h6></td>";
                    echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($ordingresonetopacum, 0, ',', '.') . "</b></h6></td>";
                    echo "<td class='text-center'  style='background-color: #C3CEFF;'><h6 class='text-center " . $color . "'><b>" . $rpast . " %</b></h6></td>";
                    echo "<td class='text-center text-primary'  style='background-color: #C3CEFF;'><h6 class='text-center label label-default'><b>100 %</b></h6></td></tr>";


                    $query = "select act.hora as fin, act.inicio as inicio, act.mingresobrutoacum as brutoactual, act.ordingresobrutoacum as ordbrutoactual,
                                     act.mclickacum as clickactual, act.ordclickacum as ordclickactual, act.mpendacum as pendactual, act.ordpendacum as ordpendactual,
                                     act.manulacum as anulactual, act.ordanulacum as ordanulactual, act.mnoviosacum as noviosactual, act.ordnoviosacum as ordnoviosactual,
                                     act.mingresonetohora as netohoraactual, act.mingresonetoacum as netoactual, act.ordingresonetoacum as ordnetoactual,
                                     act.rpastacum as peso, ant.mingresonetoacum as netoanterior, ant.ordingresonetoacum ordnetoanterior

                              from resultadosp1 ant, resultadosp1 act

                              where ant.diaactual = $buscarant and act.diaactual = $buscaract and act.hora = ant.hora and act.inicio = ant.inicio order by act.inicio asc";

                    $res = $con->query($query);

                    if ($res) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            $inicio = $row['inicio'];

                            if(strlen($inicio) == 1)
                                $inicio = '00000' . $inicio;
                            if(strlen($inicio) == 2)
                                $inicio = '0000' . $inicio;
                            if(strlen($inicio) == 3)
                                $inicio = '000' . $inicio;
                            if(strlen($inicio) == 4)
                                $inicio = '00' . $inicio;
                            if(strlen($inicio) == 5)
                                $inicio = '0' . $inicio;

                            $inicio = new DateTime($inicio);

                            $hora = $row['fin'];

                            if(strlen($hora) == 1)
                                $hora = '00000' . $hora;
                            if(strlen($hora) == 2)
                                $hora = '0000' . $hora;
                            if(strlen($hora) == 3)
                                $hora = '000' . $hora;
                            if(strlen($hora) == 4)
                                $hora = '00' . $hora;
                            if(strlen($hora) == 5)
                                $hora = '0' . $hora;

                            $hora = new DateTime($hora);

                            $mingresobruto    = $row['brutoactual'];
                            $ordingresobruto  = $row['ordbrutoactual'];
                            $mclick           = $row['clickactual'];
                            $ordclick         = $row['ordclickactual'];
                            $mpend            = $row['pendactual'];
                            $ordpend          = $row['ordpendactual'];
                            $manul            = $row['anulactual'];
                            $ordanul          = $row['ordanulactual'];
                            $mnovios          = $row['noviosactual'];
                            $ordnovios        = $row['ordnoviosactual'];
                            $mingresonetohora = $row['netohoraactual'];
                            $mingresonetoacum = $row['netoactual'];
                            $ordingresoneto   = $row['ordnetoactual'];
                            $pesoventa        = $row['peso'];
                            $mingresonetop    = $row['netoanterior'];
                            $ordingresonetop  = $row['ordnetoanterior'];

                            $rpast            = 0;
                            if($mingresonetop != 0)
                                $rpast        = round((($mingresonetoacum/$mingresonetop)-1)*100);

                            if ($rpast > 0)
                                $color = 'label label-success';

                            if ($rpast == 0)
                                $color = 'label label-warning';

                            if ($rpast < 0)
                                $color = 'label label-danger';

                            $colorhora2 = '';
                            if($inicio->format("H") == date("H") && $hora->format("H") == date("H") && $buscaract == date("Ymd"))
                                $colorhora2 = 'label label-primary';

                            if($mingresobruto == 0)
                                $mingresobruto = '-';
                            else
                                $mingresobruto = number_format($mingresobruto, 0, ',', '.');

                            if($ordingresobruto == 0)
                                $ordingresobruto = '-';
                            else
                                $ordingresobruto = number_format($ordingresobruto, 0, ',', '.');

                            if($mclick == 0)
                                $mclick = '-';
                            else
                                $mclick = number_format($mclick, 0, ',', '.');

                            if($ordclick == 0)
                                $ordclick = '-';
                            else
                                $ordclick = number_format($ordclick, 0, ',', '.');

                            if($mpend == 0)
                                $mpend = '-';
                            else
                                $mpend = number_format($mpend, 0, ',', '.');

                            if($ordpend == 0)
                                $ordpend = '-';
                            else
                                $ordpend = number_format($ordpend, 0, ',', '.');

                            if($manul == 0)
                                $manul = '-';
                            else
                                $manul = number_format($manul, 0, ',', '.');

                            if($ordanul == 0)
                                $ordanul = '-';
                            else
                                $ordanul = number_format($ordanul, 0, ',', '.');

                            if($mnovios == 0)
                                $mnovios = '-';
                            else
                                $mnovios = number_format($mnovios, 0, ',', '.');

                            if($ordnovios == 0)
                                $ordnovios = '-';
                            else
                                $ordnovios = number_format($ordnovios, 0, ',', '.');

                            if($mingresonetohora == 0)
                                $mingresonetohora = '-';
                            else
                                $mingresonetohora = number_format($mingresonetohora, 0, ',', '.');

                            if($mingresonetoacum == 0)
                                $mingresonetoacum = '-';
                            else
                                $mingresonetoacum = number_format($mingresonetoacum, 0, ',', '.');

                            if($ordingresoneto == 0)
                                $ordingresoneto = '-';
                            else
                                $ordingresoneto = number_format($ordingresoneto, 0, ',', '.');

                            echo "<tr><td style='border-right-color: black;' nowrap='100px' class='text-center'><h6 class='text-center " . $colorhora2 . "'>" . $inicio->format("H:i:s") . " - " . $hora->format("H:i:s") . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $mingresobruto . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $ordingresobruto . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $mclick . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $ordclick . "</h6></td>";

                            echo "<td><h6 class='text-center'>" . $mpend . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $ordpend . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $manul . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $ordanul . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $mnovios . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $ordnovios . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $mingresonetohora . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . $mingresonetoacum . "</h6></td>";
                            echo "<td style='border-right-width: 5px; border-right-color: white;'><h6 class='text-center'>" . $ordingresoneto . "</h6></td>";

                            echo "<td><h6 class='text-center'>" . number_format($mingresonetop, 0, ',', '.') . "</h6></td>";
                            echo "<td><h6 class='text-center'>" . number_format($ordingresonetop, 0, ',', '.') . "</h6></td>";
                            echo "<td class='text-center'><h6 class='text-center " . $color . "'>" . $rpast . " %</h6></td>";
                            echo "<td class='text-center'><h6 class='text-center label label-default'>" . $pesoventa . " %</h6></td></tr>";
                        }
                    }
                }
                echo '</table></div>';
            }else {
                if ($tipo == 'tipoventa') {
                    echo '<div id="tipoventa">';
                    echo '<table class="table table-condensed table-bordered table-hover">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th rowspan="3" style="background: white;"><h6 class="text-center"><b>Hora</b></h6></th>';
                    echo '<th colspan="15" rowspan="1" style="background-color: #35388E; color: white;"><h6 class="text-center"><b>Ingresos Tipo de Venta</b><br><br><b>
                          Día Actual ' . diasem($day->format("D")) . ', ' . $day->format("d-m-Y") . ' - Día Anterior ' . diasem($dayp->format("D")) . ', ' . $dayp->format("d-m-Y") . '</b></h6></th>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td rowspan="1" colspan="3" style="background-color: #E0E1EE;"><h6 class="text-center"><b>Ingresos Sitio</b></h6></td>';
                    echo '<td rowspan="1" colspan="3" style="background-color: #E0E1EE;"><h6 class="text-center"><b>Ingresos Fonocompras</b></h6></td>';
                    echo '<td rowspan="1" colspan="3" style="background-color: #E0E1EE;"><h6 class="text-center"><b>Empresa</b></h6></td>';
                    echo '<td rowspan="1" colspan="3" style="background-color: #E0E1EE;"><h6 class="text-center"><b>Ingresos Puntos Cencosud</b></h6></td>';
                    echo '<td rowspan="1" colspan="3" style="background-color: #E0E1EE;"><h6 class="text-center"><b>Total</b></h6></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Actual</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Anterior</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">% R/Past</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Actual</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Anterior</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">% R/Past</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Actual</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Anterior</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">% R/Past</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Actual</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Anterior</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">% R/Past</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Actual</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">$ Anterior</h6></td>';
                    echo '<td style="background-color: #E0E1EE;"><h6 class="text-center">% R/Past</h6></td>';
                    echo '</tr>';
                    echo '</thead>';

                    if(isset($_POST['dia']) && isset($_POST['mes']) && isset($_POST['anio']) && isset($_POST['diaant']) && isset($_POST['mesant']) && isset($_POST['anioant'])) {
                        $dia = $_POST['dia'];
                        $mes = $_POST['mes'];
                        $anio = $_POST['anio'];
                        $buscaract = $anio . $mes . $dia;

                        $dia = $_POST['diaant'];
                        $mes = $_POST['mesant'];
                        $anio = $_POST['anioant'];
                        $buscarant = $anio . $mes . $dia;

                        $con = new mysqli('localhost', 'root', '', 'ventahora');

                        $query = "select ingresositio, ingresofono, empresa, puntos, totalventa
                                  from resultadosp2
                                  where diaactual = $buscaract and totalventa <> 0 order by inicio desc limit 1";

                        $res = $con->query($query);

                        $ingresositioac = 0;

                        $ingresofonoac = 0;

                        $empresaac = 0;

                        $puntosac = 0;

                        $totalventaac = 0;

                        while($row = mysqli_fetch_assoc($res)){
                            $ingresositioac = $row['ingresositio'];

                            $ingresofonoac  = $row['ingresofono'];

                            $empresaac      = $row['empresa'];

                            $puntosac       = $row['puntos'];

                            $totalventaac     = $row['totalventa'];
                        }

                        $query = "select ingresositio, ingresofono, empresa, puntos, totalventa
                                  from resultadosp2
                                  where diaactual = $buscarant and totalventa <> 0 order by inicio desc limit 1";

                        $res = $con->query($query);

                        $ingresositioan = 0;

                        $ingresofonoan  = 0;

                        $empresaan = 0;

                        $puntosan = 0;

                        $totalventaan = 0;

                        while($row = mysqli_fetch_assoc($res)){
                            $ingresositioan = $row['ingresositio'];

                            $ingresofonoan  = $row['ingresofono'];

                            $empresaan      = $row['empresa'];

                            $puntosan       = $row['puntos'];

                            $totalventaan     = $row['totalventa'];
                        }

                        $rpastingresositio = 0;
                        if($ingresositioan != 0)
                            $rpastingresositio = round((($ingresositioac / $ingresositioan) - 1) * 100);

                        if ($rpastingresositio > 0)
                            $colorsitio = 'label label-success';
                        else
                            $colorsitio = 'label label-danger';

                        $rpastingresofono = 0;
                        if($ingresofonoan != 0)
                            $rpastingresofono = round((($ingresofonoac / $ingresofonoan) - 1) * 100);

                        if ($rpastingresofono > 0)
                            $colorfono = 'label label-success';
                        else
                            $colorfono = 'label label-danger';

                        $rpastempresa = 0;
                        if($empresaan != 0)
                            $rpastempresa = round((($empresaac / $empresaan) - 1) * 100);

                        if ($rpastempresa > 0)
                            $colorempresa = 'label label-success';
                        else
                            $colorempresa = 'label label-danger';

                        $rpastpuntos = 0;
                        if($puntosan != 0)
                            $rpastpuntos = round((($puntosac / $puntosan) - 1) * 100);

                        if ($rpastpuntos > 0)
                            $colorpuntos = 'label label-success';
                        else
                            $colorpuntos = 'label label-danger';

                        $rpasttotalventas = 0;
                        if($totalventaan != 0)
                            $rpasttotalventas = round((($totalventaac / $totalventaan) - 1) * 100);

                        if ($rpasttotalventas > 0)
                            $colorventas = 'label label-success';
                        else
                            $colorventas = 'label label-danger';

                        echo "<tr><td style='background-color: #C3CEFF;' nowrap='100px' class='text-center'><h6 class='text-center'><b>Total Actual</b></h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($ingresositioac, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($ingresositioan, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='border-right-color: black; background-color: #C3CEFF;' class='text-center'><h6 class='text-center " . $colorsitio . "'>" . number_format($rpastingresositio, 0, ',', '.') . " %</h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($ingresofonoac, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($ingresofonoan, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='border-right-color: black; background-color: #C3CEFF;' class='text-center'><h6 class='text-center " . $colorfono . "'>" . number_format($rpastingresofono, 0, ',', '.') . " %</h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($empresaac, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($empresaan, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='border-right-color: black; background-color: #C3CEFF;' class='text-center'><h6 class='text-center " . $colorempresa . "'>" . number_format($rpastempresa, 0, ',', '.') . " %</h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($puntosac, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($puntosan, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='border-right-color: black; background-color: #C3CEFF;' class='text-center'><h6 class='text-center " . $colorpuntos . "'>" . number_format($rpastpuntos, 0, ',', '.') . " %</h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($totalventaac, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($totalventaan, 0, ',', '.') . "</b></h6></td>";
                        echo "<td style='background-color: #C3CEFF;' class='text-center'><h6 class='text-center " . $colorventas . "'>" . number_format($rpasttotalventas, 0, ',', '.') . " %</h6></td></tr>";


                        $query = "select act.inicio as inicio, act.fin as fin, act.ingresositio as ingresositioac, act.ingresofono as ingresofonoac,
                                         act.empresa as empresaac, act.puntos as puntosac, act.totalventa as totalventaac, ant.ingresositio as ingresositioan,
                                         ant.ingresofono as ingresofonoan, ant.empresa as empresaan, ant.puntos as puntosan, ant.totalventa as totalventaan

                                 from resultadosp2 act, resultadosp2 ant

                                 where act.diaactual = $buscaract and ant.diaactual = $buscarant and act.inicio = ant.inicio and act.fin = ant.fin order by act.inicio asc";

                        $res = $con->query($query);
                        if ($res) {
                            $num = mysqli_num_rows($res);
                            $i = 0;
                            while ($row = mysqli_fetch_assoc($res)) {
                                $inicio = $row['inicio'];

                                if(strlen($inicio) == 1)
                                    $inicio = '00000' . $inicio;
                                if(strlen($inicio) == 2)
                                    $inicio = '0000' . $inicio;
                                if(strlen($inicio) == 3)
                                    $inicio = '000' . $inicio;
                                if(strlen($inicio) == 4)
                                    $inicio = '00' . $inicio;
                                if(strlen($inicio) == 5)
                                    $inicio = '0' . $inicio;

                                $inicio = new DateTime($inicio);

                                $hora = $row['fin'];

                                if(strlen($hora) == 1)
                                    $hora = '00000' . $hora;
                                if(strlen($hora) == 2)
                                    $hora = '0000' . $hora;
                                if(strlen($hora) == 3)
                                    $hora = '000' . $hora;
                                if(strlen($hora) == 4)
                                    $hora = '00' . $hora;
                                if(strlen($hora) == 5)
                                    $hora = '0' . $hora;

                                $hora = new DateTime($hora);

                                $ingresositioac = $row['ingresositioac'];

                                $ingresositioan = $row['ingresositioan'];

                                $rpastingresositio = 0;
                                if($ingresositioan != 0)
                                    $rpastingresositio = round((($ingresositioac / $ingresositioan) - 1) * 100);

                                if ($rpastingresositio > 0)
                                    $colorsitio = 'label label-success';
                                else
                                    $colorsitio = 'label label-danger';

                                $ingresofonoac = $row['ingresofonoac'];

                                $ingresofonoan = $row['ingresofonoan'];

                                $rpastingresofono = 0;
                                if($ingresofonoan != 0)
                                    $rpastingresofono = round((($ingresofonoac / $ingresofonoan) - 1) * 100);

                                if ($rpastingresofono > 0)
                                    $colorfono = 'label label-success';
                                else
                                    $colorfono = 'label label-danger';

                                $empresaac = $row['empresaac'];

                                $empresaan = $row['empresaan'];

                                $rpastempresa = 0;
                                if($empresaan != 0)
                                    $rpastempresa = round((($empresaac / $empresaan) - 1) * 100);

                                if ($rpastempresa > 0)
                                    $colorempresa = 'label label-success';
                                else
                                    $colorempresa = 'label label-danger';

                                $puntosac = $row['puntosac'];

                                $puntosan = $row['puntosan'];

                                $rpastpuntos = 0;
                                if($puntosan != 0)
                                    $rpastpuntos = round((($puntosac / $puntosan) - 1) * 100);

                                if ($rpastpuntos > 0)
                                    $colorpuntos = 'label label-success';
                                else
                                    $colorpuntos = 'label label-danger';

                                $totalventasac = $row['totalventaac'];

                                $totalventasan = $row['totalventaan'];

                                $rpasttotalventas = 0;
                                if($totalventasan != 0)
                                    $rpasttotalventas = round((($totalventasac / $totalventasan) - 1) * 100);

                                if ($rpasttotalventas > 0)
                                    $colorventas = 'label label-success';
                                else
                                    $colorventas = 'label label-danger';

                                $colorhora2 = '';
                                if($inicio->format("H") == date("H") && $hora->format("H") == date("H") && $buscaract == date("Ymd"))
                                    $colorhora2 = 'label label-primary';

                                echo "<tr><td nowrap='100px' class='text-center'><h6 class='text-center " . $colorhora2 . "'>" . $inicio->format("H:i:s") . " - " . $hora->format("H:i:s") . "</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($ingresositioac, 0, ',', '.') . "</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($ingresositioan, 0, ',', '.') . "</h6></td>";
                                echo "<td style='border-right-color: black;' class='text-center'><h6 class='text-center " . $colorsitio . "'>" . number_format($rpastingresositio, 0, ',', '.') . " %</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($ingresofonoac, 0, ',', '.') . "</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($ingresofonoan, 0, ',', '.') . "</h6></td>";
                                echo "<td style='border-right-color: black;' class='text-center'><h6 class='text-center " . $colorfono . "'>" . number_format($rpastingresofono, 0, ',', '.') . " %</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($empresaac, 0, ',', '.') . "</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($empresaan, 0, ',', '.') . "</h6></td>";
                                echo "<td style='border-right-color: black;' class='text-center'><h6 class='text-center " . $colorempresa . "'>" . number_format($rpastempresa, 0, ',', '.') . " %</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($puntosac, 0, ',', '.') . "</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($puntosan, 0, ',', '.') . "</h6></td>";
                                echo "<td style='border-right-color: black;' class='text-center'><h6 class='text-center " . $colorpuntos . "'>" . number_format($rpastpuntos, 0, ',', '.') . " %</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($totalventasac, 0, ',', '.') . "</h6></td>";
                                echo "<td><h6 class='text-center'>" . number_format($totalventasan, 0, ',', '.') . "</h6></td>";
                                echo "<td class='text-center'><h6 class='text-center " . $colorventas . "'>" . number_format($rpasttotalventas, 0, ',', '.') . " %</h6></td></tr>";

                                /**/

                                $i++;
                            }
                        }
                    }
                    echo '</table></div>';
                }else{
                    if($tipo == 'tipopago'){
                        echo '<div id="tipopago">';
                        echo '<table class="table table-condensed table-bordered table-hover">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th rowspan="3" style="background: white;"><h6 class="text-center"><b>Hora</b></h6></th>';
                        echo '<th colspan="18" rowspan="1" style="background-color: #62AA48; color: white;"><h6 class="text-center"><b>Ingresos Tipo de Pago</b><br><br><b>
                          Día Actual ' . diasem($day->format("D")) . ', ' . $day->format("d-m-Y") . ' - Día Anterior ' . diasem($dayp->format("D")) . ', ' . $dayp->format("d-m-Y") . '</b></h6></th>';
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

                        if(isset($_POST['dia']) && isset($_POST['mes']) && isset($_POST['anio']) && isset($_POST['diaant']) && isset($_POST['mesant']) && isset($_POST['anioant'])) {
                            $dia = $_POST['dia'];
                            $mes = $_POST['mes'];
                            $anio = $_POST['anio'];
                            $buscaract = $anio . $mes . $dia;

                            $dia = $_POST['diaant'];
                            $mes = $_POST['mesant'];
                            $anio = $_POST['anioant'];
                            $buscarant = $anio . $mes . $dia;

                            $con = new mysqli('localhost', 'root', '', 'ventahora');

                            $query = "select cat, ctrans, dtrans, gift, cempresa, totalpago
                                  from resultadosp2
                                  where diaactual = $buscaract and totalpago <> 0 order by inicio desc limit 1";

                            $res = $con->query($query);

                            $mcatac = 0;

                            $mctransac = 0;

                            $mdtransac = 0;

                            $mgiftac = 0;

                            $mcempresaac = 0;

                            $totalpagoac = 0;

                            while($row = mysqli_fetch_assoc($res)){
                                $mcatac = $row['cat'];

                                $mctransac = $row['ctrans'];

                                $mdtransac = $row['dtrans'];

                                $mgiftac = $row['gift'];

                                $mcempresaac = $row['cempresa'];

                                $totalpagoac = $row['totalpago'];
                            }

                            $query = "select cat, ctrans, dtrans, gift, cempresa, totalpago
                                  from resultadosp2
                                  where diaactual = $buscarant and totalpago <> 0 order by inicio desc limit 1";

                            $res = $con->query($query);

                            $mcatan = 0;

                            $mctransan = 0;

                            $mdtransan = 0;

                            $mgiftan = 0;

                            $mcempresaan = 0;

                            $totalpagoan = 0;

                            while($row = mysqli_fetch_assoc($res)){
                                $mcatan = $row['cat'];

                                $mctransan = $row['ctrans'];

                                $mdtransan = $row['dtrans'];

                                $mgiftan = $row['gift'];

                                $mcempresaan = $row['cempresa'];

                                $totalpagoan = $row['totalpago'];
                            }

                            $rpastcat = 0;
                            if($mcatan != 0)
                                $rpastcat = round((($mcatac / $mcatan) - 1) * 100);

                            if ($rpastcat > 0)
                                $colorcat = 'label label-success';
                            else
                                $colorcat = 'label label-danger';

                            $rpastctrans = 0;
                            if($mctransan != 0)
                                $rpastctrans = round((($mctransac / $mctransan) - 1) * 100);

                            if ($rpastctrans > 0)
                                $colorctrans = 'label label-success';
                            else
                                $colorctrans = 'label label-danger';

                            $rpastdtrans = 0;
                            if($mdtransan != 0)
                                $rpastdtrans = round((($mdtransac / $mdtransan) - 1) * 100);

                            if ($rpastdtrans > 0)
                                $colordtrans = 'label label-success';
                            else
                                $colordtrans = 'label label-danger';

                            $rpastgift = 0;
                            if($mgiftan != 0)
                                $rpastgift = round((($mgiftac / $mgiftan) - 1) * 100);

                            if ($rpastgift > 0)
                                $colorgift = 'label label-success';
                            else
                                $colorgift = 'label label-danger';

                            $rpastcempresa = 0;
                            if($mcempresaan != 0)
                                $rpastcempresa = round((($mcempresaac / $mcempresaan) - 1) * 100);

                            if ($rpastcempresa > 0)
                                $colorcempresa = 'label label-success';
                            else
                                $colorcempresa = 'label label-danger';

                            $rpasttotalpago = 0;
                            if($totalpagoan != 0)
                                $rpasttotalpago = round((($totalpagoac / $totalpagoan) - 1) * 100);

                            if ($rpasttotalpago > 0)
                                $colorpagos = 'label label-success';
                            else
                                $colorpagos = 'label label-danger';

                            echo "<tr><td style='background-color: #C3CEFF;'><h6 class='text-center'><b>Total Actual</b></h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mcatac, 0, ',', '.') . "</b></h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mcatan, 0, ',', '.') . "</b></h6></td>";
                            echo "<td class='text-center' style='border-right-color: black; background-color: #C3CEFF;'><h6 class='text-center " . $colorcat . "'>" . number_format($rpastcat, 0, ',', '.') . " %</h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mctransac, 0, ',', '.') . "</b></h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mctransan, 0, ',', '.') . "</b></h6></td>";
                            echo "<td class='text-center' style='border-right-color: black; background-color: #C3CEFF;'><h6 class='text-center " . $colorctrans . "'>" . number_format($rpastctrans, 0, ',', '.') . " %</h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mdtransac, 0, ',', '.') . "</b></h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mdtransan, 0, ',', '.') . "</b></h6></td>";
                            echo "<td class='text-center' style='border-right-color: black; background-color: #C3CEFF;'><h6 class='text-center " . $colordtrans . "'>" . number_format($rpastdtrans, 0, ',', '.') . " %</h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mgiftac, 0, ',', '.') . "</b></h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mgiftan, 0, ',', '.') . "</b></h6></td>";
                            echo "<td class='text-center' style='border-right-color: black; background-color: #C3CEFF;'><h6 class='text-center " . $colorgift . "'>" . number_format($rpastgift, 0, ',', '.') . " %</h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mcempresaac, 0, ',', '.') . "</b></h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mcempresaan, 0, ',', '.') . "</b></h6></td>";
                            echo "<td class='text-center' style='border-right-color: black; background-color: #C3CEFF;'><h6 class='text-center " . $colorcempresa . "'>" . number_format($rpastcempresa, 0, ',', '.') . " %</h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($totalpagoac, 0, ',', '.') . "</b></h6></td>";
                            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($totalpagoan, 0, ',', '.') . "</b></h6></td>";
                            echo "<td class='text-center' style='border-right-color: black; background-color: #C3CEFF;'><h6 class='text-center " . $colorpagos . "'>" . number_format($rpasttotalpago, 0, ',', '.') . " %</h6></td></tr>";


                            $query = "select act.inicio as inicio, act.fin as fin, act.cat as mcatac, act.ctrans as mctransac,
                                         act.dtrans as mdtransac, act.gift as mgiftac, act.cempresa as mcempresaac, act.totalpago as totalpagoac,
                                         ant.cat as mcatan, ant.ctrans as mctransan, ant.dtrans as mdtransan, ant.gift as mgiftan, ant.cempresa as mcempresaan,
                                         ant.totalpago as totalpagoan

                                 from resultadosp2 act, resultadosp2 ant

                                 where act.diaactual = $buscaract and ant.diaactual = $buscarant and act.inicio = ant.inicio and act.fin = ant.fin order by act.inicio asc";

                            $res = $con->query($query);
                            if ($res) {
                                $num = mysqli_num_rows($res);
                                $i = 0;
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $inicio = $row['inicio'];

                                    if(strlen($inicio) == 1)
                                        $inicio = '00000' . $inicio;
                                    if(strlen($inicio) == 2)
                                        $inicio = '0000' . $inicio;
                                    if(strlen($inicio) == 3)
                                        $inicio = '000' . $inicio;
                                    if(strlen($inicio) == 4)
                                        $inicio = '00' . $inicio;
                                    if(strlen($inicio) == 5)
                                        $inicio = '0' . $inicio;

                                    $inicio = new DateTime($inicio);

                                    $hora = $row['fin'];

                                    if(strlen($hora) == 1)
                                        $hora = '00000' . $hora;
                                    if(strlen($hora) == 2)
                                        $hora = '0000' . $hora;
                                    if(strlen($hora) == 3)
                                        $hora = '000' . $hora;
                                    if(strlen($hora) == 4)
                                        $hora = '00' . $hora;
                                    if(strlen($hora) == 5)
                                        $hora = '0' . $hora;

                                    $hora = new DateTime($hora);

                                    $mcatac = $row['mcatac'];

                                    $mcatan = $row['mcatan'];

                                    $rpastcat = 0;
                                    if($mcatan != 0)
                                        $rpastcat = round((($mcatac / $mcatan) - 1) * 100);

                                    if ($rpastcat > 0)
                                        $colorcat = 'label label-success';
                                    else
                                        $colorcat = 'label label-danger';

                                    $mctransac = $row['mctransac'];

                                    $mctransan = $row['mctransan'];

                                    $rpastctrans = 0;
                                    if($mctransan != 0)
                                        $rpastctrans = round((($mctransac / $mctransan) - 1) * 100);

                                    if ($rpastctrans > 0)
                                        $colorctrans = 'label label-success';
                                    else
                                        $colorctrans = 'label label-danger';

                                    $mdtransac = $row['mdtransac'];

                                    $mdtransan = $row['mdtransan'];

                                    $rpastdtrans = 0;
                                    if($mdtransan != 0)
                                        $rpastdtrans = round((($mdtransac / $mdtransan) - 1) * 100);

                                    if ($rpastdtrans > 0)
                                        $colordtrans = 'label label-success';
                                    else
                                        $colordtrans = 'label label-danger';

                                    $mgiftac = $row['mgiftac'];

                                    $mgiftan = $row['mgiftan'];

                                    $rpastgift = 0;
                                    if($mgiftan != 0)
                                        $rpastgift = round((($mgiftac / $mgiftan) - 1) * 100);

                                    if ($rpastgift > 0)
                                        $colorgift = 'label label-success';
                                    else
                                        $colorgift = 'label label-danger';

                                    $mcempresaac = $row['mcempresaac'];

                                    $mcempresaan = $row['mcempresaan'];

                                    $rpastcempresa = 0;
                                    if($mcempresaan != 0)
                                        $rpastcempresa = round((($mcempresaac / $mcempresaan) - 1) * 100);

                                    if ($rpastcempresa > 0)
                                        $colorcempresa = 'label label-success';
                                    else
                                        $colorcempresa = 'label label-danger';

                                    $totalpagoac = $row['totalpagoac'];

                                    $totalpagoan = $row['totalpagoan'];

                                    $rpasttotalpago = 0;
                                    if($totalpagoan != 0)
                                        $rpasttotalpago = round((($totalpagoac / $totalpagoan) - 1) * 100);

                                    if ($rpasttotalpago > 0)
                                        $colorpagos = 'label label-success';
                                    else
                                        $colorpagos = 'label label-danger';

                                    $colorhora2 = '';
                                    if($inicio->format("H") == date("H") && $hora->format("H") == date("H") && $buscaract == date("Ymd"))
                                        $colorhora2 = 'label label-primary';

                                    echo "<tr><td nowrap='100px' class='text-center'><h6 class='text-center " . $colorhora2 . "'>" . $inicio->format("H:i:s") . " - " . $hora->format("H:i:s") . "</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mcatac, 0, ',', '.') . "</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mcatan, 0, ',', '.') . "</h6></td>";
                                    echo "<td class='text-center' style='border-right-color: black;'><h6 class='text-center " . $colorcat . "'>" . number_format($rpastcat, 0, ',', '.') . " %</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mctransac, 0, ',', '.') . "</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mctransan, 0, ',', '.') . "</h6></td>";
                                    echo "<td class='text-center' style='border-right-color: black;'><h6 class='text-center " . $colorctrans . "'>" . number_format($rpastctrans, 0, ',', '.') . " %</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mdtransac, 0, ',', '.') . "</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mdtransan, 0, ',', '.') . "</h6></td>";
                                    echo "<td class='text-center' style='border-right-color: black;'><h6 class='text-center " . $colordtrans . "'>" . number_format($rpastdtrans, 0, ',', '.') . " %</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mgiftac, 0, ',', '.') . "</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mgiftan, 0, ',', '.') . "</h6></td>";
                                    echo "<td class='text-center' style='border-right-color: black;'><h6 class='text-center " . $colorgift . "'>" . number_format($rpastgift, 0, ',', '.') . " %</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mcempresaac, 0, ',', '.') . "</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($mcempresaan, 0, ',', '.') . "</h6></td>";
                                    echo "<td class='text-center' style='border-right-color: black;'><h6 class='text-center " . $colorcempresa . "'>" . number_format($rpastcempresa, 0, ',', '.') . " %</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($totalpagoac, 0, ',', '.') . "</h6></td>";
                                    echo "<td><h6 class='text-center'>" . number_format($totalpagoan, 0, ',', '.') . "</h6></td>";
                                    echo "<td class='text-center' style='border-right-color: black;'><h6 class='text-center " . $colorpagos . "'>" . number_format($rpasttotalpago, 0, ',', '.') . " %</h6></td></tr>";

                                    $i++;
                                }
                            }
                        }
                        echo '</table></div>';
                    }else{
                        if($tipo == 'indicadores'){
                            echo '<div id="indicadores">';
                            echo '<table class="table table-condensed table-bordered table-hover">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th colspan="7" style="background-color: #337ab7; color: white; border-right-width: 5px; border-right-color: white;">
                                  <h6 class="text-center"><b>Día Actual ' . diasem($day->format("D")) . ", " . $day->format("d-m-Y") . ' - Día Anterior ' . diasem($dayp->format("D")) . ", " . $dayp->format("d-m-Y") . '</br></h6>

                                  </th>';
                            echo '</tr>';

                            echo '<tr>';
                            echo '<th style="background-color: #337ab7; color: white;"><h6 class="text-center"><b>Hora</b></h6></th>';

                            echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Ticket Promedio Actual<br>(Venta Acumulada)</br></h6></th>';
                            echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Ticket Promedio Anterior<br>(Venta Acumulada)</b></h6></th>';
                            echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>% R/Past Ticket Promedio<br>(Venta Acumulada)</br></h6></th>';

                            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Productos por Ticket Actual<br>(Venta Acumulada)</b></h6></th>';
                            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Productos por Ticket Anterior<br>(Venta Acumulada)</b></h6></th>';
                            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>% R/Past Productos por Ticket<br>(Venta Acumulada)</b></h6></th>';
                            echo '</tr>';

                            echo '</thead>';

                            if (isset($_POST['dia']) && isset($_POST['mes']) && isset($_POST['anio']) && isset($_POST['diaant']) && isset($_POST['mesant']) && isset($_POST['anioant'])) {
                                $dia = $_POST['dia'];
                                $mes = $_POST['mes'];
                                $anio = $_POST['anio'];
                                $buscaract = $anio . $mes . $dia;

                                $dia = $_POST['diaant'];
                                $mes = $_POST['mesant'];
                                $anio = $_POST['anioant'];
                                $buscarant = $anio . $mes . $dia;

                                $con = new mysqli('localhost', 'root', '', 'ventahora');

                                $query = "select act.hora as fin, act.inicio as inicio, act.ticketpromedio as ticketpromedioac, act.prodticket as prodticketac,
                                                 ant.ticketpromedio as ticketpromedioan, ant.prodticket as prodticketan, act.ticketpromedioh as ticketpromediohac,
                                                 act.prodticketh as prodtickethac, ant.ticketpromedioh as ticketpromediohan,
                                                 ant.prodticketh as prodtickethan

                                          from resultadosp1 ant, resultadosp1 act

                                          where ant.diaactual = $buscarant and act.diaactual = $buscaract and act.hora = ant.hora and act.inicio = ant.inicio order by act.inicio asc";

                                $res = $con->query($query);

                                if ($res) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $inicio = $row['inicio'];

                                        if (strlen($inicio) == 1)
                                            $inicio = '00000' . $inicio;
                                        if (strlen($inicio) == 2)
                                            $inicio = '0000' . $inicio;
                                        if (strlen($inicio) == 3)
                                            $inicio = '000' . $inicio;
                                        if (strlen($inicio) == 4)
                                            $inicio = '00' . $inicio;
                                        if (strlen($inicio) == 5)
                                            $inicio = '0' . $inicio;

                                        $inicio = new DateTime($inicio);

                                        $hora = $row['fin'];

                                        if (strlen($hora) == 1)
                                            $hora = '00000' . $hora;
                                        if (strlen($hora) == 2)
                                            $hora = '0000' . $hora;
                                        if (strlen($hora) == 3)
                                            $hora = '000' . $hora;
                                        if (strlen($hora) == 4)
                                            $hora = '00' . $hora;
                                        if (strlen($hora) == 5)
                                            $hora = '0' . $hora;

                                        $hora = new DateTime($hora);

                                        $ticketpromedioac = $row['ticketpromedioac'];
                                        $ticketpromedioan = $row['ticketpromedioan'];

                                        $rpastticketpromedio = 0;
                                        if($ticketpromedioan > 0)
                                            $rpastticketpromedio = round((($ticketpromedioac / $ticketpromedioan) - 1) * 100, 1);

                                        $prodticketac = $row['prodticketac'];
                                        $prodticketan = $row['prodticketan'];

                                        $rpastprodticket = 0;
                                        if($prodticketan > 0)
                                            $rpastprodticket = round((($prodticketac / $prodticketan) - 1) * 100, 1);

                                        $ticketpromediohac = $row['ticketpromediohac'];
                                        $ticketpromediohan = $row['ticketpromediohan'];

                                        $rpastticketpromedioh = 0;
                                        if($ticketpromediohan > 0)
                                            $rpastticketpromedioh = round((($ticketpromediohac / $ticketpromediohan) - 1) * 100, 1);

                                        $prodtickethac = $row['prodtickethac'];
                                        $prodtickethan = $row['prodtickethan'];

                                        $rpastprodticketh = 0;
                                        if($prodtickethan > 0)
                                            $rpastprodticketh = round((($prodtickethac / $prodtickethan) - 1) * 100, 1);


                                        if ($rpastticketpromedio > 0)
                                            $colorticketpromedio = 'label label-success';

                                        if ($rpastticketpromedio == 0)
                                            $colorticketpromedio = 'label label-warning';

                                        if ($rpastticketpromedio < 0)
                                            $colorticketpromedio = 'label label-danger';

                                        if ($rpastticketpromedioh > 0)
                                            $colorticketpromedioh = 'label label-success';

                                        if ($rpastticketpromedioh == 0)
                                            $colorticketpromedioh = 'label label-warning';

                                        if ($rpastticketpromedioh < 0)
                                            $colorticketpromedioh = 'label label-danger';

                                        if ($rpastprodticket > 0)
                                            $colorprodticket = 'label label-success';

                                        if ($rpastprodticket == 0)
                                            $colorprodticket = 'label label-warning';

                                        if ($rpastprodticket < 0)
                                            $colorprodticket = 'label label-danger';

                                        if ($rpastprodticketh > 0)
                                            $colorprodticketh = 'label label-success';

                                        if ($rpastprodticketh == 0)
                                            $colorprodticketh = 'label label-warning';

                                        if ($rpastprodticketh < 0)
                                            $colorprodticketh = 'label label-danger';

                                        $colorhora2 = '';
                                        if ($inicio->format("H") == date("H") && $hora->format("H") == date("H") && $buscaract == date("Ymd"))
                                            $colorhora2 = 'label label-primary';

                                        if ($ticketpromedioac == 0)
                                            $ticketpromedioac = '-';
                                        else
                                            $ticketpromedioac  = number_format($ticketpromedioac, 0, ',', '.');

                                        if ($ticketpromedioan == 0)
                                            $ticketpromedioan = '-';
                                        else
                                            $ticketpromedioan  = number_format($ticketpromedioan, 0, ',', '.');

                                        if ($ticketpromediohac == 0)
                                            $ticketpromediohac = '-';
                                        else
                                            $ticketpromediohac  = number_format($ticketpromediohac, 0, ',', '.');

                                        if ($ticketpromediohan == 0)
                                            $ticketpromediohan = '-';
                                        else
                                            $ticketpromediohan  = number_format($ticketpromediohan, 0, ',', '.');

                                        if ($prodticketac == 0)
                                            $prodticketac = '-';
                                        else
                                            $prodticketac = number_format($prodticketac, 2, ',', '.');

                                        if ($prodticketan == 0)
                                            $prodticketan = '-';
                                        else
                                            $prodticketan = number_format($prodticketan, 2, ',', '.');

                                        if ($prodtickethac == 0)
                                            $prodtickethac = '-';
                                        else
                                            $prodtickethac = number_format($prodtickethac, 2, ',', '.');

                                        if ($prodtickethan == 0)
                                            $prodtickethan = '-';
                                        else
                                            $prodtickethan = number_format($prodtickethan, 2, ',', '.');

                                        echo "<tr><td style='border-right-color: black;' nowrap='100px' class='text-center'><h6 class='text-center " . $colorhora2 . "'>" . $inicio->format("H:i:s") . " - " . $hora->format("H:i:s") . "</h6></td>";


                                        echo "<td><h6 class='text-center'>" . $ticketpromedioac . "</h6></td>";
                                        echo "<td><h6 class='text-center'>" . $ticketpromedioan . "</h6></td>";
                                        echo "<td class='text-center'><h6 class='text-center " . $colorticketpromedio . "'>" . number_format($rpastticketpromedio, 1 , ',', '.') . " %</h6></td>";



                                        echo "<td><h6 class='text-center'>" . $prodticketac . "</h6></td>";
                                        echo "<td><h6 class='text-center'>" . $prodticketan . "</h6></td>";
                                        echo "<td class='text-center'><h6 class='text-center " . $colorprodticket . "'>" . number_format($rpastprodticket, 1, ',', '.') . " %</h6></td></tr>";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }else{

            require_once 'fechas.php';
            echo '<div id="ventas">';
            echo '<table class="table table-condensed table-bordered table-hover">';
            echo '<thead>';
            echo '<tr>';
            echo '<th rowspan="1" colspan="14" style="background-color: #337ab7; color: white; border-right-width: 5px; border-right-color: white;">
                      <h6 class="text-center"><b>Día Actual<br><br>' . diasem($day->format("D")) . ", " . $day->format("d-m-Y") . '</b></h6></th>';
            echo '<th colspan="2" style="background-color: #5A82D7; color: white;">
                      <h6 class="text-center"><b>Día Anterior<br><br>' . diasem($dayp->format("D")) . ", " . $dayp->format("d-m-Y") . '</br></h6></th>';
            echo '<th rowspan="3" style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>% R/Past</b></h6></th>';
            echo '<th rowspan="3" style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>% Peso <br>acumulada</br></h6></th>';
            echo '</tr>';

            echo '<tr>';
            echo '<th rowspan="2" colspan="1" style="background-color: #337ab7; color: white;"><h6 class="text-center"><b>Hora</b></h6></th>';
            echo '<th colspan="2" style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Ingreso Bruto</b></h6></th>';
            echo '<th colspan="2" style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Click & Collect</b></h6></th>';
            echo '<th colspan="2" style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Pendiente Validación</br></h6></th>';
            echo '<th colspan="2" style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Anulaciones</b></h6></th>';
            echo '<th colspan="2" style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Novios</b></h6></th>';
            echo '<th colspan="3" style="background-color: #337ab7; color: white; border-right-width: 5px; border-right-color: white;"><h6 class="text-center"><b>Ingreso Neto (Sin IVA)</b></h6></th>';

            echo '<th colspan="2" style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>Ingreso Neto (Sin IVA)</b></h6></th>';
            echo '</tr>';

            echo '<tr>';
            echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
            echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
            echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
            echo '<th style="background-color: #4E85FC; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>Monto $</b></h6></th>';
            echo '<th style="background-color: #7E9FE7; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
            echo '<th style="background-color: #337ab7; color: white;"><h6 class="text-center"><b>Monto por Hora $</b></h6></th>';
            echo '<th style="background-color: #337ab7; color: white;"><h6 class="text-center"><b>Monto Acumulado $</b></h6></th>';
            echo '<th style="background-color: #337ab7; color: white; border-right-width: 5px; border-right-color: white;""><h6 class="text-center"><b>#</b></h6></th>';

            echo '<th style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>Monto Acumulado $</b></h6></th>';
            echo '<th style="background-color: #5A82D7; color: white;"><h6 class="text-center"><b>#</b></h6></th>';
            echo '</tr>';
            echo '</thead>';

            $buscaract = date("Ymd");

            $buscarant = fecha($buscaract);

            $con = new mysqli('localhost', 'root', '', 'ventahora');

            $total = "select mingresobrutoacum, ordingresobrutoacum, mclickacum, ordclickacum, mpendacum, ordpendacum,
                                     manulacum, ordanulacum, mnoviosacum, ordnoviosacum, mingresonetoacum, ordingresonetoacum

                              from resultadosp1

                              where diaactual = $buscaract and mingresonetoacum <> 0 order by inicio desc limit 1";

            $res = $con->query($total);

            $mingresobrutoacum = 0;
            $ordingresobrutoacum = 0;
            $mclickacum = 0;
            $ordclickacum = 0;
            $mpendacum = 0;
            $ordpendacum = 0;
            $manulacum = 0;
            $ordanulacum = 0;
            $mnoviosacum = 0;
            $ordnoviosacum = 0;
            $mingresonetoacum = 0;
            $ordingresonetoacum = 0;

            while($row = mysqli_fetch_assoc($res)){
                $mingresobrutoacum = $row['mingresobrutoacum'];
                $ordingresobrutoacum = $row['ordingresobrutoacum'];
                $mclickacum = $row['mclickacum'];
                $ordclickacum = $row['ordclickacum'];
                $mpendacum = $row['mpendacum'];
                $ordpendacum = $row['ordpendacum'];
                $manulacum = $row['manulacum'];
                $ordanulacum = $row['ordanulacum'];
                $mnoviosacum = $row['mnoviosacum'];
                $ordnoviosacum = $row['ordnoviosacum'];
                $mingresonetoacum = $row['mingresonetoacum'];
                $ordingresonetoacum = $row['ordingresonetoacum'];
            }

            $total = "select mingresonetoacum, ordingresonetoacum

                              from resultadosp1

                              where diaactual = $buscarant and mingresonetoacum <> 0 order by inicio desc limit 1";

            $res = $con->query($total);

            $mingresonetopacum = 0;
            $ordingresonetopacum = 0;

            while($row = mysqli_fetch_assoc($res)){
                $mingresonetopacum = $row['mingresonetoacum'];
                $ordingresonetopacum = $row['ordingresonetoacum'];
            }

            $rpast            = 0;
            if($mingresonetopacum != 0)
                $rpast        = round((($mingresonetoacum/$mingresonetopacum)-1)*100);

            if($mingresobrutoacum == 0)
                $mingresobrutoacum = '-';
            else
                $mingresobrutoacum = number_format($mingresobrutoacum, 0, ',', '.');

            if($ordingresobrutoacum == 0)
                $ordingresobrutoacum = '-';
            else
                $ordingresobrutoacum = number_format($ordingresobrutoacum, 0, ',', '.');

            if($mclickacum == 0)
                $mclickacum = '-';
            else
                $mclickacum = number_format($mclickacum, 0, ',', '.');

            if($ordclickacum == 0)
                $ordclickacum = '-';
            else
                $ordclickacum = number_format($ordclickacum, 0, ',', '.');

            if($mpendacum == 0)
                $mpendacum = '-';
            else
                $mpendacum = number_format($mpendacum, 0, ',', '.');

            if($ordpendacum == 0)
                $ordpendacum = '-';
            else
                $ordpendacum = number_format($ordpendacum, 0, ',', '.');

            if($manulacum == 0)
                $manulacum = '-';
            else
                $manulacum = number_format($manulacum, 0, ',', '.');

            if($ordanulacum == 0)
                $ordanulacum = '-';
            else
                $ordanulacum = number_format($ordanulacum, 0, ',', '.');

            if($mnoviosacum == 0)
                $mnoviosacum = '-';
            else
                $mnoviosacum = number_format($mnoviosacum, 0, ',', '.');

            if($ordnoviosacum == 0)
                $ordnoviosacum = '-';
            else
                $ordnoviosacum = number_format($ordnoviosacum, 0, ',', '.');

            if($mingresonetoacum == 0)
                $mingresonetoacum = '-';
            else
                $mingresonetoacum = number_format($mingresonetoacum, 0, ',', '.');

            if($ordingresonetoacum == 0)
                $ordingresonetoacum = '-';
            else
                $ordingresonetoacum = number_format($ordingresonetoacum, 0, ',', '.');

            if ($rpast > 0)
                $color = 'label label-success';

            if ($rpast == 0)
                $color = 'label label-warning';

            if ($rpast < 0)
                $color = 'label label-danger';


            echo "<tr><td style='border-right-color: black; background-color: #C3CEFF;' nowrap='100px'><h6 class='text-center'><b>Total Actual</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mingresobrutoacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordingresobrutoacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mclickacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordclickacum . "</b></h6></td>";

            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mpendacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordpendacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $manulacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordanulacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mnoviosacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordnoviosacum . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>-</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . $mingresonetoacum . "</b></h6></td>";
            echo "<td style='border-right-width: 5px; border-right-color: white; background-color: #C3CEFF;'><h6 class='text-center'><b>" . $ordingresonetoacum . "</b></h6></td>";

            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($mingresonetopacum, 0, ',', '.') . "</b></h6></td>";
            echo "<td style='background-color: #C3CEFF;'><h6 class='text-center'><b>" . number_format($ordingresonetopacum, 0, ',', '.') . "</b></h6></td>";
            echo "<td class='text-center' style='background-color: #C3CEFF;'><h6 class='text-center " . $color . "'><b>" . $rpast . " %</b></h6></td>";
            echo "<td class='text-center text-primary' style='background-color: #C3CEFF;'><h6 class='text-center label label-default'><b>100 %</b></h6></td></tr>";

            $query = "select act.hora as fin, act.inicio as inicio, act.mingresobrutoacum as brutoactual, act.ordingresobrutoacum as ordbrutoactual,
                                 act.mclickacum as clickactual, act.ordclickacum as ordclickactual, act.mpendacum as pendactual, act.ordpendacum as ordpendactual,
                                 act.manulacum as anulactual, act.ordanulacum as ordanulactual, act.mnoviosacum as noviosactual, act.ordnoviosacum as ordnoviosactual,
                                 act.mingresonetohora as netohoraactual, act.mingresonetoacum as netoactual, act.ordingresonetoacum as ordnetoactual,
                                 act.rpastacum as peso, ant.mingresonetoacum as netoanterior, ant.ordingresonetoacum ordnetoanterior

                          from resultadosp1 ant, resultadosp1 act

                          where ant.diaactual = $buscarant and act.diaactual = $buscaract and act.hora = ant.hora and act.inicio = ant.inicio order by act.hora asc";

            $res = $con->query($query);

            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $inicio = $row['inicio'];

                    if(strlen($inicio) == 1)
                        $inicio = '00000' . $inicio;
                    if(strlen($inicio) == 2)
                        $inicio = '0000' . $inicio;
                    if(strlen($inicio) == 3)
                        $inicio = '000' . $inicio;
                    if(strlen($inicio) == 4)
                        $inicio = '00' . $inicio;
                    if(strlen($inicio) == 5)
                        $inicio = '0' . $inicio;

                    $inicio = new DateTime($inicio);

                    $hora = $row['fin'];

                    if(strlen($hora) == 1)
                        $hora = '00000' . $hora;
                    if(strlen($hora) == 2)
                        $hora = '0000' . $hora;
                    if(strlen($hora) == 3)
                        $hora = '000' . $hora;
                    if(strlen($hora) == 4)
                        $hora = '00' . $hora;
                    if(strlen($hora) == 5)
                        $hora = '0' . $hora;

                    $hora = new DateTime($hora);

                    $mingresobruto    = $row['brutoactual'];
                    $ordingresobruto  = $row['ordbrutoactual'];
                    $mclick           = $row['clickactual'];
                    $ordclick         = $row['ordclickactual'];
                    $mpend            = $row['pendactual'];
                    $ordpend          = $row['ordpendactual'];
                    $manul            = $row['anulactual'];
                    $ordanul          = $row['ordanulactual'];
                    $mnovios          = $row['noviosactual'];
                    $ordnovios        = $row['ordnoviosactual'];
                    $mingresonetohora = $row['netohoraactual'];
                    $mingresonetoacum = $row['netoactual'];
                    $ordingresoneto   = $row['ordnetoactual'];
                    $pesoventa        = $row['peso'];
                    $mingresonetop    = $row['netoanterior'];
                    $ordingresonetop  = $row['ordnetoanterior'];

                    $rpast            = 0;
                    if($mingresonetop != 0)
                        $rpast        = round((($mingresonetoacum/$mingresonetop)-1)*100);

                    if ($rpast > 0)
                        $color = 'label label-success';

                    if ($rpast == 0)
                        $color = 'label label-warning';

                    if ($rpast < 0)
                        $color = 'label label-danger';

                    $colorhora2 = '';
                    if($inicio->format("H") == date("H") && $hora->format("H") == date("H"))
                        $colorhora2 = 'label label-primary';

                    if($mingresobruto == 0)
                        $mingresobruto = '-';
                    else
                        $mingresobruto = number_format($mingresobruto, 0, ',', '.');

                    if($ordingresobruto == 0)
                        $ordingresobruto = '-';
                    else
                        $ordingresobruto = number_format($ordingresobruto, 0, ',', '.');

                    if($mclick == 0)
                        $mclick = '-';
                    else
                        $mclick = number_format($mclick, 0, ',', '.');

                    if($ordclick == 0)
                        $ordclick = '-';
                    else
                        $ordclick = number_format($ordclick, 0, ',', '.');

                    if($mpend == 0)
                        $mpend = '-';
                    else
                        $mpend = number_format($mpend, 0, ',', '.');

                    if($ordpend == 0)
                        $ordpend = '-';
                    else
                        $ordpend = number_format($ordpend, 0, ',', '.');

                    if($manul == 0)
                        $manul = '-';
                    else
                        $manul = number_format($manul, 0, ',', '.');

                    if($ordanul == 0)
                        $ordanul = '-';
                    else
                        $ordanul = number_format($ordanul, 0, ',', '.');

                    if($mnovios == 0)
                        $mnovios = '-';
                    else
                        $mnovios = number_format($mnovios, 0, ',', '.');

                    if($ordnovios == 0)
                        $ordnovios = '-';
                    else
                        $ordnovios = number_format($ordnovios, 0, ',', '.');

                    if($mingresonetohora == 0)
                        $mingresonetohora = '-';
                    else
                        $mingresonetohora = number_format($mingresonetohora, 0, ',', '.');

                    if($mingresonetoacum == 0)
                        $mingresonetoacum = '-';
                    else
                        $mingresonetoacum = number_format($mingresonetoacum, 0, ',', '.');

                    if($ordingresoneto == 0)
                        $ordingresoneto = '-';
                    else
                        $ordingresoneto = number_format($ordingresoneto, 0, ',', '.');

                    echo "<tr><td style='border-right-color: black;' nowrap='100px' class='text-center'><h6 class='text-center " . $colorhora2 . "'>" . $inicio->format("H:i:s") . " - " . $hora->format("H:i:s") . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $mingresobruto . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $ordingresobruto . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $mclick . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $ordclick . "</h6></td>";

                    echo "<td><h6 class='text-center'>" . $mpend . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $ordpend . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $manul . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $ordanul . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $mnovios . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $ordnovios . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $mingresonetohora . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . $mingresonetoacum . "</h6></td>";
                    echo "<td style='border-right-width: 5px; border-right-color: white;'><h6 class='text-center'>" . $ordingresoneto . "</h6></td>";

                    echo "<td><h6 class='text-center'>" . number_format($mingresonetop, 0, ',', '.') . "</h6></td>";
                    echo "<td><h6 class='text-center'>" . number_format($ordingresonetop, 0, ',', '.') . "</h6></td>";
                    echo "<td class='text-center'><h6 class='text-center " . $color . "'>" . $rpast . " %</h6></td>";
                    echo "<td class='text-center'><h6 class='text-center label label-default'>" . $pesoventa . " %</h6></td></tr>";
                }
            }
            echo '</table></div>';
        }
        ?>

        <script src="jquery-1.12.0.min.js"></script>
        <script src="bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
        <script src="bootstrap-select-1.9.4/dist/js/bootstrap-select.min.js"></script>
        <script src="jquery.stickytableheaders.js"></script>
        <script>
            $('table').stickyTableHeaders();
        </script>
    </body>
</html>