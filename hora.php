<?php

date_default_timezone_set("America/Santiago");

$fin = date("H", strtotime("-1 hour")) . 59 . 59;

$inicio = date("His", strtotime("{$fin} -59 minutes -59 seconds"));

$day = date("Ymd");

ini_set("max_execution_time", 0);

$local = new mysqli('localhost', 'root', '', 'ventahora');

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

for($dia = new DateTime(20160413); $dia->format("Ymd") >= 20140101; $dia->modify("-1 day")) {

    $day = $dia->format("Ymd");

    echo "Actualizando dia " . $day . " <br> ";

    $i = 0;

    $hora = new DateTime('000000');

    $inicio = $hora->format("His");

    while ($i <= 23) {

        $fin = $hora->format("H") . 59 . 59;

        $ini = $hora->format("His");

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

}
