<?php

date_default_timezone_set("America/Santiago");

require_once 'fechas.php';

$fin = date("H", strtotime("-1 hour")) . 59 . 59;

$inicio = date("His", strtotime("{$fin} -59 minutes -59 seconds"));

$day = date("Ymd");

ini_set("max_execution_time", 0);

$roble = odbc_connect('cecebugd', 'USRVNP', 'USRVNP');

$local = new mysqli('localhost', 'root', '', 'ventahora');

$validar = new mysqli('localhost', 'root', '', 'validacion');

function actualizar($local, $roble, $day)
{
    $inicio = array(0, 10000, 20000, 30000, 40000, 50000, 60000, 70000, 80000, 90000, 100000, 110000, 120000, 130000,
                    140000, 150000, 160000, 170000, 180000, 190000, 200000, 210000, 220000, 230000);

    $fin    = array(5959, 15959, 25959, 35959, 45959, 55959, 65959, 75959, 85959, 95959, 105959, 115959,
                   125959, 135959, 145959, 155959, 165959, 175959, 185959, 195959, 205959, 215959, 225959, 235959);

    $local->query("delete from ingresos where fechant = $day");

    $query = "SELECT SVVIF03.NUMORDEN, SVVIF03.FECHANT, SVVIF03.HORANT, SVVIF03.TIPVTA, SVVIF03.MONTOVTA, SVVIF03.NROCUENTA,
                 SVVIF03.TIPOPAG, SVVIF04.CODDESP, SVVIF03.ESTORDEN, SVVIF03.SUBESTOC, Svvif04.Canvend*Svvif04.Precuni AS PXQ,
                 svvif04.codsku, Svvif04.Canvend, Svvif04.Precuni, svvif04.depto1

          FROM RDBPARIS2.CECEBUGD.SVVIF03 SVVIF03, RDBPARIS2.CECEBUGD.SVVIF04 SVVIF04

          WHERE (SVVIF03.NUMORDEN = SVVIF04.NUMORDEN) AND (SVVIF03.TIPVTA = SVVIF04.TIPVTA)
                AND (SVVIF03.TIPVTA IN (1, 2, 15)) AND (SVVIF03.fechant = $day)";

    $resultado = odbc_exec($roble, $query);

    while (odbc_fetch_row($resultado)) {
        $numorden = odbc_result($resultado, 1);
        $fechant = odbc_result($resultado, 2);
        $horant = odbc_result($resultado, 3);
        $tipvta = odbc_result($resultado, 4);
        $montovta = odbc_result($resultado, 5);
        $nrocuenta = odbc_result($resultado, 6);
        $tipopag = odbc_result($resultado, 7);
        $coddesp = odbc_result($resultado, 8);
        $estorden = odbc_result($resultado, 9);
        $subestoc = odbc_result($resultado, 10);
        $pxq = odbc_result($resultado, 11);
        $codsku = odbc_result($resultado, 12);
        $canvend = odbc_result($resultado, 13);
        $precuni = odbc_result($resultado, 14);
        $depto = odbc_result($resultado, 15);

        $tip = str_split($codsku);

        if ($tip[0] == '0')
            $tipocompra = "Despacho";
        else
            $tipocompra = "Producto";

        if ($tipvta == 15 && $nrocuenta == 5000001503151000)
            $fidelidad = 0;
        else
            $fidelidad = 1;

        $id = $numorden . $codsku;

        $count = count($inicio);

        $rango1 = 0;

        $rango2 = 0;

        for($i = 0; $i < $count; $i++){
            if($horant >= $inicio[$i] && $horant <= $fin[$i]){
                $rango1 = "" . $inicio[$i] . "";

                if(strlen($rango1) == 1)
                    $rango1 = "00000" . $rango1;

                if(strlen($rango1) == 2)
                    $rango1 = "0000" . $rango1;

                if(strlen($rango1) == 3)
                    $rango1 = "000" . $rango1;

                if(strlen($rango1) == 4)
                    $rango1 = "00" . $rango1;

                if(strlen($rango1) == 5)
                    $rango1 = "0" . $rango1;

                $rango2 = "" . $fin[$i] . "";

                if(strlen($rango2) == 1)
                    $rango2 = "00000" . $rango2;

                if(strlen($rango2) == 2)
                    $rango2 = "0000" . $rango2;

                if(strlen($rango2) == 3)
                    $rango2 = "000" . $rango2;

                if(strlen($rango2) == 4)
                    $rango2 = "00" . $rango2;

                if(strlen($rango2) == 5)
                    $rango2 = "0" . $rango2;

                break;
            }
        }

        $query = "insert into ingresos values ('$id',
                                            $numorden,
                                           $fechant,
                                           $horant,
                                           $tipvta,
                                           $montovta,
                                           $fidelidad,
                                           $tipopag,
                                           $coddesp,
                                           $estorden,
                                           '$subestoc',
                                           $pxq,
                                           '$tipocompra',
                                           $canvend,
                                           $precuni,
                                           $depto,
                                           '$rango1',
                                           '$rango2')";

        $res = $local->query($query);

        if (!$res)
            return false;
    }
    return true;
}

// =============================== Panel de Ingresos =======================================

function ingresoBruto($con, $inicio, $fin, $dia)
{
    $monto = "select sum(pxq) as monto from ingresos where fechant = $dia and horant between $inicio and $fin and coddesp <> 18";

    $res = $con->query($monto);

    $sum = 0;

    while ($row = mysqli_fetch_assoc($res)) {
        $sum += $row['monto'];
    }

    return $sum;
} //Función monto ingreso bruto

function ordenesIngesoBruto($con, $inicio, $fin, $dia)
{
    $query = "select numorden from ingresos where fechant = $dia and coddesp <> 18 and horant between $inicio and $fin group by numorden having count(numorden) >= 1";

    $res = $con->query($query);

    return mysqli_num_rows($res);
}   //Función cantidad órdenes ingreso bruto

function clickCollect($con, $inicio, $fin, $dia)
{
    $query = "select sum(pxq) as monto from ingresos where fechant = $dia and horant between $inicio and $fin and coddesp = 22";

    $res = $con->query($query);

    $sum = 0;

    while ($row = mysqli_fetch_assoc($res))
        $sum += $row['monto'];

    return $sum;
} //Función monto Click & Collect

function ordClickCollect($con, $inicio, $fin, $dia)
{
    $query = "select numorden from ingresos where coddesp = 22 and fechant = $dia and horant between $inicio and $fin group by numorden having count(numorden) >= 1";

    $res = $con->query($query);

    return mysqli_num_rows($res);
} //Función cantidad órdenes Click & Collect

function Pendientes($con, $inicio, $fin, $dia)
{
    $fin = $fin . "00";

    $inicio = $inicio . "00";

    $pend = array(0, 0);

    $query = "select sum(pxq) as sumpen from validar where fecorden = $dia and horaorden between $inicio and $fin
              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80)";

    $activo = "select active from activo";

    $res = $con->query($activo);

    while($row = mysqli_fetch_assoc($res)){
        if($row['active'] == 1)
            $query = "select sum(pxq) as sumpen from auxvalidar where fecorden = $dia and horaorden between $inicio and $fin
              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80)";
    }

    $res = $con->query($query);

    while ($row = mysqli_fetch_assoc($res))
        $pend[0] += $row['sumpen'];

    $query = "select numorden from validar where fecorden = $dia and horaorden between $inicio and $fin
              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80) group by numorden having count(numorden) >= 1";


    $activo = "select active from activo";

    $res = $con->query($activo);

    while($row = mysqli_fetch_assoc($res)){
        if($row['active'] == 1)
            $query = "select numorden from auxvalidar where fecorden = $dia and horaorden between $inicio and $fin
              and estanter in (0, 1, 2, 34, 81) and estorden not in (99, 80) group by numorden having count(numorden) >= 1";
    }

    $pend[1] = mysqli_num_rows($con->query($query));

    return $pend;
} //Función monto y cantidad órdenes de pendientes de validación

function Anulaciones($con, $inicio, $fin, $dia)
{
    $monto = "select (montovta/1.19) as monto from ingresos where fechant = $dia and horant between $inicio and $fin
                      and (estorden = 80 or (estorden = 99 and subestoc = '99'))";

    $res = $con->query($monto);

    $i = 0;
    while ($row = mysqli_fetch_assoc($res))
        $i += $row['monto'];

    return round($i);
} // Función monto anulaciones

function ordenesAnulaciones($con, $inicio, $fin, $dia)
{
    $query = "select numorden from ingresos where fechant = $dia and horant between $inicio and $fin
                      and (estorden = 80 or (estorden = 99 and subestoc = '99')) group by numorden having count(numorden) >= 1";

    $res = $con->query($query);

    return mysqli_num_rows($res);
} //Función cantidad órdenes anulaciones

function Novios($con, $inicio, $fin, $dia)
{
    $monto = "select sum(pxq) as monto from ingresos where fechant = $dia and horant between $inicio and $fin and coddesp = 18";

    $res = $con->query($monto);

    $sum = 0;

    while ($row = mysqli_fetch_assoc($res)) {
        $sum += $row['monto'];
    }

    return $sum;
} //Función monto novios

function ordenesNovios($con, $inicio, $fin, $dia)
{
    $query = "select numorden from ingresos where fechant = $dia and horant between $inicio and $fin and coddesp = 18
              group by numorden having count(numorden) >= 1";

    $res = $con->query($query);

    return mysqli_num_rows($res);
} //Función cantidad órdenes novios

function prodPorTicket($con, $inicio, $fin, $dia){
    $query = "select sum(canvend) as cant from ingresos where tipocompra = 'Producto' and horant between $inicio and $fin and fechant = $dia and coddesp <> 18 and tipvta <> 15";

    $res = $con->query($query);

    $num = 0;
    while($row = mysqli_fetch_assoc($res))
        $num = $row['cant'];

    $query = "select numorden from ingresos where fechant = $dia and horant between $inicio and $fin and coddesp <> 18 and tipvta <> 15 group by numorden having count(numorden) >= 1";

    $res = $con->query($query);

    $num2 = mysqli_num_rows($res);

    if($num2 > 0)
        return round($num / $num2, 2);

    return 0;
} //Ok

function ticketPromedio($con, $inicio, $fin, $dia){
    $query = "select sum(pxq) as cant from ingresos where horant between $inicio and $fin and fechant = $dia and coddesp <> 18";

    $res = $con->query($query);

    $num = 0;
    while($row = mysqli_fetch_assoc($res))
        $num = $row['cant'];

    $num = round($num / 1.19);

    $query = "select numorden from ingresos where fechant = $dia and horant between $inicio and $fin and coddesp <> 18 group by numorden having count(numorden) >= 1";

    $res = $con->query($query);

    $num2 = mysqli_num_rows($res);

    if($num2 > 0)
        return round($num / $num2);

    return 0;
} //Ok

// =================================== FIN Panel de ingresos =========================================

// =================================== Ingresos por canal Tipo Venta ===========================================

function TipoVenta($con, $inicio, $fin, $dia, $tipvta, $fidelidad){

    $fide = " and fidelidad = $fidelidad";

    $query = "select sum(pxq) as monto from ingresos where tipvta = $tipvta and fechant = $dia and horant between $inicio and $fin
                     and coddesp not in (18)";

    if($tipvta == 15)
        $query = $query . $fide;

    $res = $con->query($query);

    $sum = 0;

    while ($row = mysqli_fetch_assoc($res))
        $sum = $row['monto'];

    return round($sum);

}

function ordTipVta($con, $inicio, $fin, $dia, $tipvta, $fidelidad){

    $fide = " and fidelidad = $fidelidad";

    $query = "select numorden from ingresos where tipvta = $tipvta and fechant = $dia and horant between $inicio and $fin
              and coddesp not in (18) group by numorden having count(numorden) >= 1";

    if($tipvta == 15)
        $query = "select numorden from ingresos where tipvta = $tipvta and fechant = $dia" . $fide . " and
                  coddesp not in (18) and horant between $inicio and $fin group by numorden having count(numorden) >= 1";

    $res = $con->query($query);

    return mysqli_num_rows($res);
}

// =================================== Fin Ingresos por canal Tipo Venta ===========================================

// =================================== Ingresos por canal Tipo Pago ===========================================

function TipoPago($con, $inicio, $fin, $dia, $tipopag){
    $query = "select sum(pxq) as monto from ingresos where tipopag = $tipopag and fechant = $dia
              and horant between $inicio and $fin and coddesp not in (18)";

    $res = $con->query($query);

    while($row = mysqli_fetch_assoc($res)){
        if($row['monto'] != null)
            return $row['monto'];
        else
            return 0;
    }

}

function ordTipPag($con, $inicio, $fin, $dia, $tipopag){
    $query = "select numorden from ingreso where tipopag = $tipopag and fectrantsl = $dia and coddesp not in (18)
              group by numorden having count(numorden) >= 1";

    $res = $con->query($query);

    return mysqli_num_rows($res);
}

// =================================== Fin Ingresos por canal Tipo Pago ===========================================


if(actualizar($local, $roble, $day)) {

    echo "Actualizando dia " . $day . " <br> ";

    $i = 0;

    $hora = new DateTime('000000');

    $total = ingresoBruto($local, 0, 235959, $day);

    $total = round($total / 1.19);

    $inicio = $hora->format("His");

    while ($i <= 23) {

        $fin = $hora->format("H") . 59 . 59;

        $ini = $hora->format("His");

        if($i <= date("H")) {
            $mingresobruto = ingresoBruto($local, $inicio, $fin, $day);

            $mingresonetohora = round(ingresoBruto($local, $ini, $fin, $day) / 1.19);

            $ordingresobruto = ordenesIngesoBruto($local, $inicio, $fin, $day);

            $mclick = clickCollect($local, $inicio, $fin, $day);

            $ordclick = ordClickCollect($local, $inicio, $fin, $day);

            $pendientes = Pendientes($validar, $inicio, $fin, $day);

            $manul = Anulaciones($local, $inicio, $fin, $day);

            $ordanul = ordenesAnulaciones($local, $inicio, $fin, $day);

            $mnovios = Novios($local, $inicio, $fin, $day);

            $ordnovios = ordenesNovios($local, $inicio, $fin, $day);

            $mingresoneto = round($mingresobruto / 1.19);

            $ticketpromedio = ticketPromedio($local, $inicio, $fin, $day);

            $prodticket = prodPorTicket($local, $inicio, $fin, $day);

            $ticketpromedioh = ticketPromedio($local, $ini, $fin, $day);

            $prodticketh = prodPorTicket($local, $ini, $fin, $day);

            $rpastacum = 0;
            if ($total != 0) {
                $rpastacum = round(($mingresonetohora / $total) * 100, 1);
            }

            //Ingreso Tipo Venta

            $ingresositio = TipoVenta($local, $inicio, $fin, $day, 1, 0);

            $ingresofono  = TipoVenta($local, $inicio, $fin, $day, 2, 0);

            $empresa      = TipoVenta($local, $inicio, $fin, $day, 15, 1);

            $puntos       = TipoVenta($local, $inicio, $fin, $day, 15, 0);

            $totalventa   = $ingresositio + $ingresofono + $empresa + $puntos;

            //Fin Ingreso Tipo Venta

            //Ingreso Tipo Pago

            $cat          = TipoPago($local, $inicio, $fin, $day, 22);

            $ctrans       = TipoPago($local, $inicio, $fin, $day, 20);

            $dtrans       = TipoPago($local, $inicio, $fin, $day, 21);

            $gift         = TipoPago($local, $inicio, $fin, $day, 0);

            $cempresa     = TipoPago($local, $inicio, $fin, $day, 30);

            $totalpago    = $cat + $ctrans + $dtrans + $gift + $cempresa;

            //Fin Ingreso Tipo Pago

        }else{
            $mingresobruto    = 0;

            $mingresonetohora = 0;

            $ordingresobruto  = 0;

            $mclick           = 0;

            $ordclick         = 0;

            $pendientes       = array(0, 0);

            $manul            = 0;

            $ordanul          = 0;

            $mnovios          = 0;

            $ordnovios        = 0;

            $mingresoneto     = 0;

            $ticketpromedio = 0;

            $prodticket = 0;

            $ticketpromedioh = 0;

            $prodticketh = 0;

            $rpastacum        = 0;

            //Ingreso Tipo Venta

            $ingresositio = 0;

            $ingresofono  = 0;

            $empresa      = 0;

            $puntos       = 0;

            $totalventa   = 0;

            //Fin Ingreso Tipo Venta

            //Ingreso Tipo Pago

            $cat          = 0;

            $ctrans       = 0;

            $dtrans       = 0;

            $gift         = 0;

            $cempresa     = 0;

            $totalpago    = 0;

            //Fin Ingreso Tipo Pago
        }

        $local->query("delete from resultadosp1 where diaactual = $day and hora = $fin");

        $insertar = "insert into resultadosp1 values ($day,
                                                  $ini,
                                                  $fin,
                                                  $mingresobruto,
                                                  $ordingresobruto,
                                                  $mclick,
                                                  $ordclick,
                                                  $pendientes[0],
                                                  $pendientes[1],
                                                  $manul,
                                                  $ordanul,
                                                  $mnovios,
                                                  $ordnovios,
                                                  $mingresonetohora,
                                                  $mingresoneto,
                                                  $ordingresobruto,
                                                  $rpastacum,
                                                  $ticketpromedio,
                                                  $prodticket,
                                                  $ticketpromedioh,
                                                  $prodticketh)";

        $res = $local->query($insertar);

        if ($res)
            echo "Se insertó correctamente hora inicio " . $inicio . " hora fin " . $fin . " <br> ";
        else
            echo "Error hora inicio " . $inicio . " hora fin " . $fin;

        //==================================================================================================================


        $local->query("delete from resultadosp2 where diaactual = $day and inicio = $ini and fin = $fin");

        $insertar = "insert into resultadosp2 values ($day,
                                                      $ini,
                                                      $fin,
                                                      $ingresositio,
                                                      $ingresofono,
                                                      $empresa,
                                                      $puntos,
                                                      $totalventa,
                                                      $cat,
                                                      $ctrans,
                                                      $dtrans,
                                                      $gift,
                                                      $cempresa,
                                                      $totalpago)";

        $res = $local->query($insertar);

        if(!$res)
            echo "Error ingresos por canal";


        //==================================================================================================================

        $hora->modify("+1 hour");

        $i++;
    }

}else{
    echo "No se actualizó";
}

$hora = date("His");

$fecha = date("Ymd");

$query = "update actualizar set hora = $hora where indicador = 0";

$local->query($query);

$query = "update actualizar set fecha = $fecha where indicador = 0";

$local->query($query);

