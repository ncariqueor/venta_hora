<?php

date_default_timezone_set("America/Santiago");

require_once 'fechas.php';

ini_set("max_execution_time", 0);

$local = new mysqli('localhost', 'root', '', 'ventahora');

$roble = odbc_connect('CECEBUGD', 'USRVNP', 'USRVNP');

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

                echo $rango1 . "\n";

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
    $pend = array(0, 0);
    $query = "select sum(pxq) as sumpen from ingresos where fechant = $dia and horant between $inicio and $fin
              and estorden not in (99, 80)";

    $res = $con->query($query);

    while ($row = mysqli_fetch_assoc($res))
        $pend[0] += $row['sumpen'];

    $query = "select numorden from ingresos where fechant = $dia and horant between $inicio and $fin
              and estorden not in (99, 80) group by numorden having count(numorden) >= 1";

    $pend[1] = mysqli_num_rows($con->query($query));

    return $pend;
} //Función monto y cantidad órdenes de pendientes de validación

function Anulaciones($con, $inicio, $fin, $dia)
{
    $monto = "select (montovta/1.19) as monto from ingresos where fechant = $dia and horant between $inicio and $fin
                      and (estorden = 80 or (estorden = 99 and subestoc = '99')) group by numorden";

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

for($dia = new DateTime(20151101); $dia->format("Ymd") <= 20161127; $dia->modify("+1 day")) {

    $day = $dia->format("Ymd");

    echo "Actualizando día $day \n";

    actualizar($local, $roble, $day);
}