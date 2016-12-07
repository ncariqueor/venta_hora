<?php

function deptoXhoras($fecha, $fechaAnt, $venta){
    ini_set("max_execution_time", 0);

    $ventas = new mysqli('localhost', 'root', '', 'ventas');

    $dep = array();

    $division = array();

    $nomdepto = array();

    $ingreso_bruto_act = array(); $ord_bruto_act = array();
    $click_collect_act = array(); $ord_cc_act    = array();
    $pendiente_val_act = array(); $ord_val_act   = array();
    $anulaciones_act   = array(); $ord_anu_act   = array();
    $novios_act        = array(); $ord_nov_act   = array();

    $ingreso_bruto_ant = array(); $ord_bruto_ant = array();
    /*$click_collect_ant = array(); $ord_cc_ant    = array();
    $pendiente_val_ant = array(); $ord_val_ant   = array();
    $anulaciones_ant   = array(); $ord_anu_ant   = array();
    $novios_ant        = array(); $ord_nov_ant   = array();*/

    $query = "select depto1, nomdepto, division from depto where division <> '' order by depto1 asc";

    $res = $ventas->query($query);

    $i = 0;

    while($row = mysqli_fetch_assoc($res)){
        $dep[$i]               = $row['depto1'];
        $division[$i]          = $row['division'];
        $nomdepto[$i]          = $row['nomdepto'];

        $ingreso_bruto_act[$i] = 0;
        $ord_bruto_act[$i]     = 0;
        $click_collect_act[$i] = 0;
        $ord_cc_act[$i]        = 0;
        $pendiente_val_act[$i] = 0;
        $ord_val_act[$i]       = 0;
        $anulaciones_act[$i]   = 0;
        $ord_anu_act[$i]       = 0;
        $novios_act[$i]        = 0;
        $ord_nov_act[$i]       = 0;

        $ingreso_bruto_ant[$i] = 0;
        $ord_bruto_ant[$i]     = 0;
        /*$click_collect_ant[$i] = 0;
        $ord_cc_ant[$i]        = 0;
        $pendiente_val_ant[$i] = 0;
        $ord_val_ant[$i]       = 0;
        $anulaciones_ant[$i]   = 0;
        $ord_anu_ant[$i]       = 0;
        $novios_ant[$i]        = 0;
        $ord_nov_ant[$i]       = 0;*/
        $i++;
    }

    $val = new mysqli('localhost', 'root', '', 'validacion');

    $horant = 235959;

    $query = "select max(horant) as horant from ingresos where fechant = $fecha";

    $res = $venta->query($query);

    while ($row = mysqli_fetch_assoc($res)) {
        $horant = $row['horant'];
    }

    //======================================== COMIENZO INGRESO BRUTO ==================================================

    $query = "select sum(pxq) as ingresobruto, count(distinct numorden) as ordenes, depto

              from ingresos

              where fechant = $fecha and horant <= $horant and coddesp <> 18 group by depto order by depto asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['depto'], $dep);
        $ingreso_bruto_act[$i] = $row['ingresobruto'];
        $ord_bruto_act[$i]     = $row['ordenes'];
    }

    $query = "select sum(pxq) as ingresobruto, count(distinct numorden) as ordenes, depto

              from ingresos

              where fechant = $fechaAnt and horant <= $horant and coddesp <> 18 group by depto order by depto asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['depto'], $dep);
        $ingreso_bruto_ant[$i] = $row['ingresobruto'];
        $ord_bruto_ant[$i]     = $row['ordenes'];
    }

    //======================================== COMIENZO CLICK & COLLECT ================================================

    $query = "select sum(pxq) as clickcollect, count(distinct numorden) as ordenes, depto

              from ingresos

              where fechant = $fecha and horant <= $horant and coddesp = 22 group by depto order by depto asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['depto'], $dep);
        $click_collect_act[$i] = $row['clickcollect'];
        $ord_cc_act[$i]        = $row['ordenes'];
    }

    /*$query = "select sum(pxq) as clickcollect, count(distinct numorden) as ordenes, depto

              from ingresos

              where fechant = $fechaAnt and horant <= $horant and coddesp = 22 group by depto order by depto asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['depto'], $dep);
        $click_collect_ant[$i] = $row['clickcollect'];
        $ord_cc_ant[$i]        = $row['ordenes'];
    }*/

    //========================================== COMIENZO VALIDACION ===================================================

    $horatmp = $horant . "00";

    $query = "select sum(pxq) as sumpen, count(distinct numorden) as ordenes, depto1

              from validar where fecorden = $fecha and horaorden <= $horatmp

              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80) group by depto1 order by depto1 asc";

    $activo = "select active from activo";

    $res = $val->query($activo);

    while($row = mysqli_fetch_assoc($res)){
        if($row['active'] == 1)
            $query = "select sum(pxq) as sumpen, count(distinct numorden) as ordenes, depto1

              from auxvalidar where fecorden = $fecha and horaorden <= $horatmp

              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80) group by depto1 order by depto1 asc";
    }

    $res = $val->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['depto1'], $dep);
        $pendiente_val_act[$i] = $row['sumpen'];
        $ord_val_act[$i]       = $row['ordenes'];
    }

    /// VALIDACIONES

    /*$query = "select sum(pxq) as sumpen, count(distinct numorden) as ordenes, depto1

              from validar where fecorden = $fechaAnt and horaorden <= $horatmp

              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80) group by depto1 order by depto1 asc";

    $activo = "select active from activo";

    $res = $val->query($activo);

    while($row = mysqli_fetch_assoc($res)){
        if($row['active'] == 1)
            $query = "select sum(pxq) as sumpen, count(distinct numorden) as ordenes, depto1

              from auxvalidar where fecorden = $fechaAnt and horaorden <= $horatmp

              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80) group by depto1 order by depto1 asc";
    }

    $res = $val->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['depto1'], $dep);
        $pendiente_val_ant[$i] = $row['sumpen'];
        $ord_val_ant[$i]       = $row['ordenes'];
    }*/

    //======================================== COMIENZO ANULACIONES ====================================================

    $query = "select sum((montovta/1.19)) as monto, count(distinct numorden) as ordenes, depto

              from ingresos

              where fechant = $fecha and horant <= $horant and (estorden = 80 or (estorden = 99 and subestoc = '99'))

              group by depto order by depto asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                   = array_search($row['depto'], $dep);
        $anulaciones_act[$i] = round($row['monto']);
        $ord_anu_act[$i]     = $row['ordenes'];
    }

    /*$query = "select sum((montovta/1.19)) as monto, count(distinct numorden) as ordenes, depto

              from ingresos

              where fechant = $fechaAnt and horant <= $horant and (estorden = 80 or (estorden = 99 and subestoc = '99'))

              group by depto order by depto asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                   = array_search($row['depto'], $dep);
        $anulaciones_ant[$i] = $row['monto'];
        $ord_anu_ant[$i]     = $row['ordenes'];
    }*/

    //=========================================== COMIENZO NOVIOS ======================================================

    $query = "select sum(pxq) as monto, count(distinct numorden) as ordenes, depto

              from ingresos

              where fechant = $fecha and horant <= $horant and coddesp = 18 group by depto order by depto asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i               = array_search($row['depto'], $dep);
        $novios_act[$i]  = $row['monto'];
        $ord_nov_act[$i] = $row['ordenes'];
    }

    /*$query = "select sum(pxq) as monto, count(distinct numorden) as ordenes, depto

              from ingresos

              where fechant = $fechaAnt and horant <= $horant and coddesp = 18 group by depto order by depto asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i               = array_search($row['depto'], $dep);
        $novios_ant[$i]  = $row['monto'];
        $ord_nov_ant[$i] = $row['ordenes'];
    }*/

    //======================================== FIN DE CALCULOS =========================================================

    $cant = count($dep);

    $total_ingreso_bruto_act = 0;
    $total_ord_bruto_act     = 0;
    $total_click_collect_act = 0;
    $total_ord_cc_act        = 0;
    $total_pendiente_val_act = 0;
    $total_ord_val_act       = 0;
    $total_anulaciones_act   = 0;
    $total_ord_anu_act       = 0;
    $total_novios_act        = 0;
    $total_ord_nov_act       = 0;

    $total_ingreso_bruto_ant = 0;
    $total_ord_bruto_ant     = 0;
    /*$total_click_collect_ant = 0;
    $total_ord_cc_ant        = 0;
    $total_pendiente_val_ant = 0;
    $total_ord_val_ant       = 0;
    $total_anulaciones_ant   = 0;
    $total_ord_anu_ant       = 0;
    $total_novios_ant        = 0;
    $total_ord_nov_ant       = 0;*/

    for($i = 0; $i < $cant; $i++){
        $total_ingreso_bruto_act += $ingreso_bruto_act[$i];
        $total_ord_bruto_act     += $ord_bruto_act[$i];
        $total_click_collect_act += $click_collect_act[$i];
        $total_ord_cc_act        += $ord_cc_act[$i];
        $total_pendiente_val_act += $pendiente_val_act[$i];
        $total_ord_val_act       += $ord_val_act[$i];
        $total_anulaciones_act   += $anulaciones_act[$i];
        $total_ord_anu_act       += $ord_anu_act[$i];
        $total_novios_act        += $novios_act[$i];
        $total_ord_nov_act       += $ord_nov_act[$i];

        $total_ingreso_bruto_ant += $ingreso_bruto_ant[$i];
        $total_ord_bruto_ant     += $ord_bruto_ant[$i];
        /*$total_click_collect_ant += $click_collect_ant[$i];
        $total_ord_cc_ant        += $ord_cc_ant[$i];
        $total_pendiente_val_ant += $pendiente_val_ant[$i];
        $total_ord_val_ant       += $ord_val_ant[$i];
        $total_anulaciones_ant   += $anulaciones_ant[$i];
        $total_ord_anu_ant       += $ord_anu_ant[$i];
        $total_novios_ant        += $novios_ant[$i];
        $total_ord_nov_ant       += $ord_nov_ant[$i];*/
    }

    $total_ingreso_neto_act = round($total_ingreso_bruto_act / 1.19);

    $total_ingreso_neto_ant = round($total_ingreso_bruto_ant / 1.19);

    $rpast = 0;
    if($total_ingreso_neto_ant != 0)
        $rpast = round((($total_ingreso_neto_act / $total_ingreso_neto_ant) - 1) * 100);

    $label = "";

    if($rpast > 0)
        $label = "label label-success";

    if($rpast == 0)
        $label = "label label-warning";

    if($rpast < 0)
        $label = "label label-danger";

    echo "<tr><td><h5 class='text-center'><b>Total</b></h5></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ingreso_bruto_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_bruto_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_click_collect_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_cc_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_pendiente_val_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_val_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_anulaciones_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_anu_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_novios_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_nov_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ingreso_neto_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_bruto_act, 0, ',', '.') . "</b></h6></td>";

    echo "<td><h6 class='text-center'><b>" . number_format($total_ingreso_neto_ant, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_bruto_ant, 0, ',', '.') . "</b></h6></td>";
    echo "<td class='text-center'><h6 class='$label'>" . number_format($rpast, 0, ',', '.') . " %</h6></td></tr>";

    $divisiones = array('HOMBRES', 'DEPORTES', 'MUJER', 'ACCESORIOS', 'INFANTIL', 'ELECTRO-HOGAR', 'TECNOLOGIA', 'DECO-HOGAR', 'OTROS');

    foreach($divisiones as $item){
        $total_ingreso_bruto_act = 0;
        $total_ord_bruto_act     = 0;
        $total_click_collect_act = 0;
        $total_ord_cc_act        = 0;
        $total_pendiente_val_act = 0;
        $total_ord_val_act       = 0;
        $total_anulaciones_act   = 0;
        $total_ord_anu_act       = 0;
        $total_novios_act        = 0;
        $total_ord_nov_act       = 0;

        $total_ingreso_bruto_ant = 0;
        $total_ord_bruto_ant     = 0;

        for($i = 0; $i < $cant; $i++){
            if($division[$i] == $item){
                $total_ingreso_bruto_act += $ingreso_bruto_act[$i];
                $total_ord_bruto_act     += $ord_bruto_act[$i];
                $total_click_collect_act += $click_collect_act[$i];
                $total_ord_cc_act        += $ord_cc_act[$i];
                $total_pendiente_val_act += $pendiente_val_act[$i];
                $total_ord_val_act       += $ord_val_act[$i];
                $total_anulaciones_act   += $anulaciones_act[$i];
                $total_ord_anu_act       += $ord_anu_act[$i];
                $total_novios_act        += $novios_act[$i];
                $total_ord_nov_act       += $ord_nov_act[$i];

                $total_ingreso_bruto_ant += $ingreso_bruto_ant[$i];
                $total_ord_bruto_ant     += $ord_bruto_ant[$i];
            }
        }

        $total_ingreso_neto_act = round($total_ingreso_bruto_act / 1.19);

        $total_ingreso_neto_ant = round($total_ingreso_bruto_ant / 1.19);

        $clase = $item;

        $rpast = 0;
        if($total_ingreso_neto_ant != 0)
            $rpast = round((($total_ingreso_neto_act / $total_ingreso_neto_ant) - 1) * 100);

        $label = "";

        if($rpast > 0)
            $label = "label label-success";

        if($rpast == 0)
            $label = "label label-warning";

        if($rpast < 0)
            $label = "label label-danger";

        echo '<tr style=\'height: 45px;\'><td class="text-center"><h5><a href="#" style="text-decoration: none;" onclick="mostrar'; echo "('.$clase'); return false;"; echo '"><b>' . $item . '</b> <span class="glyphicon glyphicon-collapse-down" aria-hidden="true"></span></h5></a></td>';
        echo "<td><h6 class='text-center'>" . number_format($total_ingreso_bruto_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_bruto_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_click_collect_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_cc_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_pendiente_val_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_val_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_anulaciones_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_anu_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_novios_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_nov_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ingreso_neto_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_bruto_act, 0, ',', '.') . "</h6></td>";

        echo "<td><h6 class='text-center'>" . number_format($total_ingreso_neto_ant, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_bruto_ant, 0, ',', '.') . "</h6></td>";
        echo "<td class='text-center'><h6 class='$label'>" . number_format($rpast, 0, ',', '.') . " %</h6></td></tr>";

        for($i = 0; $i < $cant; $i++){
            if($division[$i] == $item){
                $depto = $dep[$i] . " - " . $nomdepto[$i];

                $ingreso_neto_act = round(($ingreso_bruto_act[$i] / 1.19));

                $ingreso_neto_ant = round(($ingreso_bruto_ant[$i] / 1.19));

                $rpast = 0;
                if($ingreso_neto_ant != 0)
                    $rpast = round((($ingreso_neto_act / $ingreso_neto_ant) - 1) * 100);

                $label = "";

                if($rpast > 0)
                    $label = "label label-success";

                if($rpast == 0)
                    $label = "label label-warning";

                if($rpast < 0)
                    $label = "label label-danger";

                echo "<tr><td class='$clase' style='display: none'><h5 class='text-center'>$depto</h5></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ingreso_bruto_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_bruto_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($click_collect_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_cc_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($pendiente_val_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_val_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($anulaciones_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_anu_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($novios_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_nov_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ingreso_neto_act, 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_bruto_act[$i], 0, ',', '.') . "</h6></td>";

                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ingreso_neto_ant, 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_bruto_ant[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='text-center $clase' style='display: none'><h6 class='$label'>" . number_format($rpast, 0, ',', '.') . " %</h6></td></tr>";
            }
        }

    }



}

function tipoventa($buscaract, $buscarant, $con){

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

            $ingresositioac = $row['ingresositioac'];

            $ingresositioan = $row['ingresositioan'];

            $rpastingresositio = 0;
            if ($ingresositioan != 0)
                $rpastingresositio = round((($ingresositioac / $ingresositioan) - 1) * 100);

            if ($rpastingresositio > 0)
                $colorsitio = 'label label-success';
            else
                $colorsitio = 'label label-danger';

            $ingresofonoac = $row['ingresofonoac'];

            $ingresofonoan = $row['ingresofonoan'];

            $rpastingresofono = 0;
            if ($ingresofonoan != 0)
                $rpastingresofono = round((($ingresofonoac / $ingresofonoan) - 1) * 100);

            if ($rpastingresofono > 0)
                $colorfono = 'label label-success';
            else
                $colorfono = 'label label-danger';

            $empresaac = $row['empresaac'];

            $empresaan = $row['empresaan'];

            $rpastempresa = 0;
            if ($empresaan != 0)
                $rpastempresa = round((($empresaac / $empresaan) - 1) * 100);

            if ($rpastempresa > 0)
                $colorempresa = 'label label-success';
            else
                $colorempresa = 'label label-danger';

            $puntosac = $row['puntosac'];

            $puntosan = $row['puntosan'];

            $rpastpuntos = 0;
            if ($puntosan != 0)
                $rpastpuntos = round((($puntosac / $puntosan) - 1) * 100);

            if ($rpastpuntos > 0)
                $colorpuntos = 'label label-success';
            else
                $colorpuntos = 'label label-danger';

            $totalventasac = $row['totalventaac'];

            $totalventasan = $row['totalventaan'];

            $rpasttotalventas = 0;
            if ($totalventasan != 0)
                $rpasttotalventas = round((($totalventasac / $totalventasan) - 1) * 100);

            if ($rpasttotalventas > 0)
                $colorventas = 'label label-success';
            else
                $colorventas = 'label label-danger';

            $colorhora2 = '';
            if ($inicio->format("H") == date("H") && $hora->format("H") == date("H") && $buscaract == date("Ymd"))
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

function tipopago($buscaract, $buscarant, $con){

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

            $mcatac = $row['mcatac'];

            $mcatan = $row['mcatan'];

            $rpastcat = 0;
            if ($mcatan != 0)
                $rpastcat = round((($mcatac / $mcatan) - 1) * 100);

            if ($rpastcat > 0)
                $colorcat = 'label label-success';
            else
                $colorcat = 'label label-danger';

            $mctransac = $row['mctransac'];

            $mctransan = $row['mctransan'];

            $rpastctrans = 0;
            if ($mctransan != 0)
                $rpastctrans = round((($mctransac / $mctransan) - 1) * 100);

            if ($rpastctrans > 0)
                $colorctrans = 'label label-success';
            else
                $colorctrans = 'label label-danger';

            $mdtransac = $row['mdtransac'];

            $mdtransan = $row['mdtransan'];

            $rpastdtrans = 0;
            if ($mdtransan != 0)
                $rpastdtrans = round((($mdtransac / $mdtransan) - 1) * 100);

            if ($rpastdtrans > 0)
                $colordtrans = 'label label-success';
            else
                $colordtrans = 'label label-danger';

            $mgiftac = $row['mgiftac'];

            $mgiftan = $row['mgiftan'];

            $rpastgift = 0;
            if ($mgiftan != 0)
                $rpastgift = round((($mgiftac / $mgiftan) - 1) * 100);

            if ($rpastgift > 0)
                $colorgift = 'label label-success';
            else
                $colorgift = 'label label-danger';

            $mcempresaac = $row['mcempresaac'];

            $mcempresaan = $row['mcempresaan'];

            $rpastcempresa = 0;
            if ($mcempresaan != 0)
                $rpastcempresa = round((($mcempresaac / $mcempresaan) - 1) * 100);

            if ($rpastcempresa > 0)
                $colorcempresa = 'label label-success';
            else
                $colorcempresa = 'label label-danger';

            $totalpagoac = $row['totalpagoac'];

            $totalpagoan = $row['totalpagoan'];

            $rpasttotalpago = 0;
            if ($totalpagoan != 0)
                $rpasttotalpago = round((($totalpagoac / $totalpagoan) - 1) * 100);

            if ($rpasttotalpago > 0)
                $colorpagos = 'label label-success';
            else
                $colorpagos = 'label label-danger';

            $colorhora2 = '';
            if ($inicio->format("H") == date("H") && $hora->format("H") == date("H") && $buscaract == date("Ymd"))
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

function ventahora($buscaract, $buscarant, $con){

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

function deptoXhora($fecha, $fechaAnt, $venta, $depto){

    $val = new mysqli('localhost', 'root', '', 'validacion');

    $rangos = array(
        "000000 - 005959",
        "010000 - 015959",
        "020000 - 025959",
        "030000 - 035959",
        "040000 - 045959",
        "050000 - 055959",
        "060000 - 065959",
        "070000 - 075959",
        "080000 - 085959",
        "090000 - 095959",
        "100000 - 105959",
        "110000 - 115959",
        "120000 - 125959",
        "130000 - 135959",
        "140000 - 145959",
        "150000 - 155959",
        "160000 - 165959",
        "170000 - 175959",
        "180000 - 185959",
        "190000 - 195959",
        "200000 - 205959",
        "210000 - 215959",
        "220000 - 225959",
        "230000 - 235959");

      $ingreso_bruto_act = array(); $ord_bruto_act = array();
      $click_collect_act = array(); $ord_cc_act    = array();
      $pendiente_val_act = array(); $ord_val_act   = array();
      $anulaciones_act   = array(); $ord_anu_act   = array();
      $novios_act        = array(); $ord_nov_act   = array();

      $ingreso_bruto_ant = array(); $ord_bruto_ant = array();

    $cant = count($rangos);

    for($i = 0; $i < $cant; $i++){
        $ingreso_bruto_act[$i] = 0;
        $ord_bruto_act[$i]     = 0;
        $click_collect_act[$i] = 0;
        $ord_cc_act[$i]        = 0;
        $pendiente_val_act[$i] = 0;
        $ord_val_act[$i]       = 0;
        $anulaciones_act[$i]   = 0;
        $ord_anu_act[$i]       = 0;
        $novios_act[$i]        = 0;
        $ord_nov_act[$i]       = 0;

        $ingreso_bruto_ant[$i] = 0;
        $ord_bruto_ant[$i]     = 0;
    }

    //============================================= INGRESO BRUTO ACTUAL Y ANTERIOR ====================================

    $query = "select sum(pxq) as ingresobruto, count(distinct numorden) as ordenes, concat(inicio, ' - ', fin) as rango

              from ingresos

              where fechant = $fecha and coddesp <> 18 and depto = $depto group by rango order by rango asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['rango'], $rangos);
        $ingreso_bruto_act[$i] = $row['ingresobruto'];
        $ord_bruto_act[$i]     = $row['ordenes'];
    }

    $query = "select sum(pxq) as ingresobruto, count(distinct numorden) as ordenes, concat(inicio, ' - ', fin) as rango

              from ingresos

              where fechant = $fechaAnt and coddesp <> 18 and depto = $depto group by rango order by rango asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['rango'], $rangos);
        $ingreso_bruto_ant[$i] = $row['ingresobruto'];
        $ord_bruto_ant[$i]     = $row['ordenes'];
    }

    //======================================== COMIENZO CLICK & COLLECT ================================================

    $query = "select sum(pxq) as clickcollect, count(distinct numorden) as ordenes, concat(inicio, ' - ', fin) as rango

              from ingresos

              where fechant = $fecha and coddesp = 22 and depto = $depto group by rango order by rango asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['rango'], $rangos);
        $click_collect_act[$i] = $row['clickcollect'];
        $ord_cc_act[$i]        = $row['ordenes'];
    }

    //======================================== COMIENZO PENDIENTE VALIDACIÓN ================================================

    $query = "select sum(pxq) as sumpen, count(distinct numorden) as ordenes, concat(inicio, ' - ', fin) as rango

              from validar where fecorden = $fecha

              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80) and depto1 = $depto group by rango order by rango asc";

    $activo = "select active from activo";

    $res = $val->query($activo);

    while($row = mysqli_fetch_assoc($res)){
        if($row['active'] == 1)
            $query = "select sum(pxq) as sumpen, count(distinct numorden) as ordenes, concat(inicio, ' - ', fin) as rango

              from auxvalidar where fecorden = $fecha and horaorden <= $horatmp

              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80) and depto = $depto group by rango order by rango asc";
    }

    $res = $val->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                     = array_search($row['rango'], $rangos);
        $pendiente_val_act[$i] = $row['sumpen'];
        $ord_val_act[$i]       = $row['ordenes'];
    }


    //======================================== COMIENZO ANULACIONES ================================================

    $query = "select sum((montovta/1.19)) as monto, count(distinct numorden) as ordenes, concat(inicio, ' - ', fin) as rango

              from ingresos

              where fechant = $fecha and (estorden = 80 or (estorden = 99 and subestoc = '99'))

              and depto = $depto group by rango order by rango asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i                   = array_search($row['rango'], $rangos);
        $anulaciones_act[$i] = round($row['monto']);
        $ord_anu_act[$i]     = $row['ordenes'];
    }

    //======================================== COMIENZO NOVIOS ================================================

    $query = "select sum(pxq) as monto, count(distinct numorden) as ordenes, concat(inicio, ' - ', fin) as rango

              from ingresos

              where fechant = $fecha and coddesp = 18 and depto = $depto group by rango order by rango asc";

    $res = $venta->query($query);

    while($row = mysqli_fetch_assoc($res)){
        $i               = array_search($row['rango'], $rangos);
        $novios_act[$i]  = $row['monto'];
        $ord_nov_act[$i] = $row['ordenes'];
    }

    //===================================== FIN DE CALCULOS ==================================================

    $cant = count($rangos);

    $total_ingreso_bruto_act = 0;
    $total_ord_bruto_act     = 0;
    $total_click_collect_act = 0;
    $total_ord_cc_act        = 0;
    $total_pendiente_val_act = 0;
    $total_ord_val_act       = 0;
    $total_anulaciones_act   = 0;
    $total_ord_anu_act       = 0;
    $total_novios_act        = 0;
    $total_ord_nov_act       = 0;

    $total_ingreso_bruto_ant = 0;
    $total_ord_bruto_ant     = 0;
    $total_peso_acumulado    = 0;

    for($i = 0; $i < $cant; $i++){
        $total_ingreso_bruto_act += $ingreso_bruto_act[$i];
        $total_ord_bruto_act     += $ord_bruto_act[$i];
        $total_click_collect_act += $click_collect_act[$i];
        $total_ord_cc_act        += $ord_cc_act[$i];
        $total_pendiente_val_act += $pendiente_val_act[$i];
        $total_ord_val_act       += $ord_val_act[$i];
        $total_anulaciones_act   += $anulaciones_act[$i];
        $total_ord_anu_act       += $ord_anu_act[$i];
        $total_novios_act        += $novios_act[$i];
        $total_ord_nov_act       += $ord_nov_act[$i];

        $total_ingreso_bruto_ant += $ingreso_bruto_ant[$i];
        $total_ord_bruto_ant     += $ord_bruto_ant[$i];
        //$total_monto_x_hora       = 0;
        //$total_monto_x_hora       = round($ingreso_bruto_act[$i]/1.19);
    }

    $total_ingreso_neto_act = round($total_ingreso_bruto_act / 1.19);
    $total_ingreso_neto_ant = round($total_ingreso_bruto_ant / 1.19);
    $total_peso_acumulado   = 100;

    $rpast = 0;
    if($total_ingreso_neto_ant != 0)
        $rpast = round((($total_ingreso_neto_act / $total_ingreso_neto_ant) - 1) * 100);

    $label = "";

    if($rpast > 0)
        $label = "label label-success";

    if($rpast == 0)
        $label = "label label-warning";

    if($rpast < 0)
        $label = "label label-danger";


    //-------------------- FILA TOTAL ----------------------
    echo "<tr><td><h5 class='text-center'><b>Total</b></h5></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ingreso_bruto_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_bruto_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_click_collect_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_cc_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_pendiente_val_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_val_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_anulaciones_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_anu_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_novios_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_nov_act, 0, ',', '.') . "</b></h6></td>";
    //Total monto_x_hora
    echo "<td><h6 class='text-center'><b>" . number_format($total_ingreso_neto_act, 0, ',', '.') . "</b></h6></td>";

    echo "<td><h6 class='text-center'><b>" . number_format($total_ingreso_neto_act, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_bruto_act, 0, ',', '.') . "</b></h6></td>";

    echo "<td><h6 class='text-center'><b>" . number_format($total_ingreso_neto_ant, 0, ',', '.') . "</b></h6></td>";
    echo "<td><h6 class='text-center'><b>" . number_format($total_ord_bruto_ant, 0, ',', '.') . "</b></h6></td>";
    echo "<td class='text-center'><h6 class='$label'>" . number_format($rpast, 0, ',', '.') . " %</h6></td>";
    echo "<td class='text-center'><h6 class='label label-default'>" . number_format($total_peso_acumulado, 0, ',', '.') . " %</h6></td></tr>";

    //--------------------- LLENADO DE TABLA --------------------------
    $total_ingreso_bruto_act = 0;
    $total_ord_bruto_act     = 0;
    $total_click_collect_act = 0;
    $total_ord_cc_act        = 0;
    $total_pendiente_val_act = 0;
    $total_ord_val_act       = 0;
    $total_anulaciones_act   = 0;
    $total_ord_anu_act       = 0;
    $total_novios_act        = 0;
    $total_ord_nov_act       = 0;

    $total_ingreso_bruto_ant = 0;
    $total_ord_bruto_ant     = 0;
    $total_peso_acumulado    = 0;
    //Variable auxiliar para guardar el total neto
    $aux                     = $total_ingreso_neto_act;

    foreach($rangos as $item){
        for($i = 0; $i < $cant; $i++){
            if($rangos[$i] == $item){
              $total_ingreso_bruto_act += $ingreso_bruto_act[$i];
              $total_ord_bruto_act     += $ord_bruto_act[$i];
              $total_click_collect_act += $click_collect_act[$i];
              $total_ord_cc_act        += $ord_cc_act[$i];
              $total_pendiente_val_act += $pendiente_val_act[$i];
              $total_ord_val_act       += $ord_val_act[$i];
              $total_anulaciones_act   += $anulaciones_act[$i];
              $total_ord_anu_act       += $ord_anu_act[$i];
              $total_novios_act        += $novios_act[$i];
              $total_ord_nov_act       += $ord_nov_act[$i];

              $total_ingreso_bruto_ant += $ingreso_bruto_ant[$i];
              $total_ord_bruto_ant     += $ord_bruto_ant[$i];
              $total_monto_x_hora       = 0;
              $total_peso_acumulado     = 0;
              if($aux == 0){
                $total_monto_x_hora       = round($ingreso_bruto_act[$i]/1.19);
                $total_peso_acumulado    += 0;
              }
              else{
                $total_monto_x_hora       = round($ingreso_bruto_act[$i]/1.19);
                $total_peso_acumulado    += ($total_monto_x_hora/$aux)*100;
              }

          }
        }
        $total_ingreso_neto_act = round($total_ingreso_bruto_act / 1.19);
        $total_ingreso_neto_ant = round($total_ingreso_bruto_ant / 1.19);

        $clase = $item;

        $rpast = 0;
        if($total_ingreso_neto_ant != 0)
            $rpast = round((($total_ingreso_neto_act / $total_ingreso_neto_ant) - 1) * 100);

        $label = "";

        if($rpast > 0)
            $label = "label label-success";

        if($rpast == 0)
            $label = "label label-warning";

        if($rpast < 0)
            $label = "label label-danger";

        echo '<tr style=\'height: 45px;\'><td class="text-center"><h5><b>' . $item . '</b></td>';
        echo "<td><h6 class='text-center'>" . number_format($total_ingreso_bruto_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_bruto_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_click_collect_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_cc_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_pendiente_val_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_val_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_anulaciones_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_anu_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_novios_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_nov_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_monto_x_hora, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ingreso_neto_act, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_bruto_act, 0, ',', '.') . "</h6></td>";

        echo "<td><h6 class='text-center'>" . number_format($total_ingreso_neto_ant, 0, ',', '.') . "</h6></td>";
        echo "<td><h6 class='text-center'>" . number_format($total_ord_bruto_ant, 0, ',', '.') . "</h6></td>";
        echo "<td class='text-center'><h6 class='$label'>" . number_format($rpast, 0, ',', '.') . " %</h6></td>";
        echo "<td class='text-center'><h6 class='label label-default'>" . number_format($total_peso_acumulado, 0, ',', '.') . " %</h6></td></tr>";

        for($i = 0; $i < $cant; $i++){
            if($rangos[$i] == $item){
                $ingreso_neto_act = round(($ingreso_bruto_act[$i] / 1.19));

                $ingreso_neto_ant = round(($ingreso_bruto_ant[$i] / 1.19));

                $rpast = 0;
                if($ingreso_neto_ant != 0)
                    $rpast = round((($ingreso_neto_act / $ingreso_neto_ant) - 1) * 100);

                $label = "";

                if($rpast > 0)
                    $label = "label label-success";

                if($rpast == 0)
                    $label = "label label-warning";

                if($rpast < 0)
                    $label = "label label-danger";

                echo "<tr><td class='$clase' style='display: none'><h5 class='text-center'></h5></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ingreso_bruto_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_bruto_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($click_collect_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_cc_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($pendiente_val_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_val_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($anulaciones_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_anu_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($novios_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_nov_act[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ingreso_neto_act, 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ingreso_neto_act, 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_bruto_act[$i], 0, ',', '.') . "</h6></td>";

                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ingreso_neto_ant, 0, ',', '.') . "</h6></td>";
                echo "<td class='$clase' style='display: none'><h6 class='text-center'>" . number_format($ord_bruto_ant[$i], 0, ',', '.') . "</h6></td>";
                echo "<td class='text-center $clase' style='display: none'><h6 class='$label'>" . number_format($rpast, 0, ',', '.') . " %</h6></td>";
                echo "<td class='text-center $clase' style='display: none'><h6 class='label label-default'>" . number_format($total_peso_acumulado, 0, ',', '.') . " %</h6></td></tr>";
            }
        }
    }
  }
