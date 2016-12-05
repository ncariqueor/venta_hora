<?php
date_default_timezone_set("America/Santiago");
ini_set("max_execution_time", 0);
require_once 'Classes/PHPExcel.php';

$mes  = $_GET['mes'];
$anio = $_GET['anio'];
$dia  = $_GET['dia'];

$buscaract = $anio . $mes . $dia;

$mes  = $_GET['mesant'];
$anio = $_GET['anioant'];
$dia  = $_GET['diaant'];

$buscarant = $anio . $mes . $dia;

$buscaract = new DateTime($buscaract);

$buscarant = new DateTime($buscarant);

$con = new mysqli('localhost', 'root', '', 'ventahora');

$excel = new PHPExcel();

$excel->getProperties()->setCreator("Operaciones")
    ->setLastModifiedBy("Operaciones")
    ->setTitle("Panel Venta por Hora");

$titulo = "Panel Venta por Hora";

$titulos1 = array('Hora', 'Ingresos Tipo de Venta Día Actual ' . diasem($buscaract->format("D")) . ", " . $buscaract->format("d-m-Y") .
    ' - Día Anterior ' . diasem($buscarant->format("D")) . ", " . $buscarant->format("d-m-Y"));

$titulos2 = array('Ingresos Sitio', 'Ingresos Fonocompras', 'Empresa', 'Ingresos Puntos Cencosud', 'Total');

$titulos3 = array('$ Actual', '$Anterior', '% R/Past');


$excel->setActiveSheetIndex(0)
    ->mergeCells('A1:P1')

    ->mergeCells('A2:A4')
    ->mergeCells('B2:P2')

    ->mergeCells('B3:D3')
    ->mergeCells('E3:G3')
    ->mergeCells('H3:J3')
    ->mergeCells('K3:M3')
    ->mergeCells('N3:P3');

$excel->setActiveSheetIndex(0)
    ->setCellValue('A1', $titulo)

    ->setCellValue('A2', $titulos1[0])
    ->setCellValue('B2', $titulos1[1])

    ->setCellValue('B3', $titulos2[0])
    ->setCellValue('E3', $titulos2[1])
    ->setCellValue('H3', $titulos2[2])
    ->setCellValue('K3', $titulos2[3])
    ->setCellValue('N3', $titulos2[4])

    ->setCellValue('B4', $titulos3[0])
    ->setCellValue('C4', $titulos3[1])
    ->setCellValue('D4', $titulos3[2])
    ->setCellValue('E4', $titulos3[0])
    ->setCellValue('F4', $titulos3[1])
    ->setCellValue('G4', $titulos3[2])
    ->setCellValue('H4', $titulos3[0])
    ->setCellValue('I4', $titulos3[1])
    ->setCellValue('J4', $titulos3[2])
    ->setCellValue('K4', $titulos3[0])
    ->setCellValue('L4', $titulos3[1])
    ->setCellValue('M4', $titulos3[2])
    ->setCellValue('N4', $titulos3[0])
    ->setCellValue('O4', $titulos3[1])
    ->setCellValue('P4', $titulos3[2]);

$colormalo = array(
    'font' => array(
        'name'  => 'Calibri',
        'color' => array(
            'rgb' => '862828'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'D48484'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => 'dddddd'
            )
        )
    )
);

$colorbueno = array(
    'font' => array(
        'name'  => 'Calibri',
        'color' => array(
            'rgb' => '26520E'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => '76AE6C'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => 'dddddd'
            )
        )
    )
);

$actual = $buscaract->format('Ymd');

$anterior = $buscarant->format('Ymd');

$query = "select ingresositio, ingresofono, empresa, puntos, totalventa
                                  from resultadosp2
                                  where diaactual = $actual and totalventa <> 0 order by inicio desc limit 1";

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

    $totalventaac   = $row['totalventa'];
}

$query = "select ingresositio, ingresofono, empresa, puntos, totalventa
                                  from resultadosp2
                                  where diaactual = $anterior and totalventa <> 0 order by inicio desc limit 1";

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

    $totalventaan   = $row['totalventa'];
}

$rpastingresositio = 0;
if($ingresositioan != 0)
    $rpastingresositio = ($ingresositioac / $ingresositioan) - 1;

$rpastingresofono = 0;
if($ingresofonoan != 0)
    $rpastingresofono = ($ingresofonoac / $ingresofonoan) - 1;

$rpastempresa = 0;
if($empresaan != 0)
    $rpastempresa = ($empresaac / $empresaan) - 1;

$rpastpuntos = 0;
if($puntosan != 0)
    $rpastpuntos = ($puntosac / $puntosan) - 1;

$rpasttotalventas = 0;
if($totalventaan != 0)
    $rpasttotalventas = ($totalventaac / $totalventaan) - 1;

$excel->setActiveSheetIndex(0)
    ->setCellValue('A5', "Total a las " . date("H:i"))
    ->setCellValue('B5', $ingresositioac)
    ->setCellValue('C5', $ingresositioan)
    ->setCellValue('D5', $rpastingresositio)
    ->setCellValue('E5', $ingresofonoac)
    ->setCellValue('F5', $ingresofonoan)
    ->setCellValue('G5', $rpastingresofono)
    ->setCellValue('H5', $empresaac)
    ->setCellValue('I5', $empresaan)
    ->setCellValue('J5', $rpastempresa)
    ->setCellValue('K5', $puntosac)
    ->setCellValue('L5', $puntosan)
    ->setCellValue('M5', $rpastpuntos)
    ->setCellValue('N5', $totalventaac)
    ->setCellValue('O5', $totalventaan)
    ->setCellValue('P5', $rpasttotalventas);

if(($rpastingresositio*100) > 0)
    $excel->getActiveSheet()->getStyle('D5')->applyFromArray($colorbueno);
else
    $excel->getActiveSheet()->getStyle('D5')->applyFromArray($colormalo);

if(($rpastingresofono*100) > 0)
    $excel->getActiveSheet()->getStyle('G5')->applyFromArray($colorbueno);
else
    $excel->getActiveSheet()->getStyle('G5')->applyFromArray($colormalo);

if(($rpastempresa*100) > 0)
    $excel->getActiveSheet()->getStyle('J5')->applyFromArray($colorbueno);
else
    $excel->getActiveSheet()->getStyle('J5')->applyFromArray($colormalo);

if(($rpastpuntos*100) > 0)
    $excel->getActiveSheet()->getStyle('M5')->applyFromArray($colorbueno);
else
    $excel->getActiveSheet()->getStyle('M5')->applyFromArray($colormalo);

if(($rpasttotalventas*100) > 0)
    $excel->getActiveSheet()->getStyle('P5')->applyFromArray($colorbueno);
else
    $excel->getActiveSheet()->getStyle('P5')->applyFromArray($colormalo);

$query = "select act.inicio as inicio, act.fin as fin, act.ingresositio as ingresositioac, act.ingresofono as ingresofonoac,
                 act.empresa as empresaac, act.puntos as puntosac, act.totalventa as totalventaac, ant.ingresositio as ingresositioan,
                 ant.ingresofono as ingresofonoan, ant.empresa as empresaan, ant.puntos as puntosan, ant.totalventa as totalventaan

          from resultadosp2 act, resultadosp2 ant

          where act.diaactual = $actual and ant.diaactual = $anterior and act.inicio = ant.inicio and act.fin = ant.fin order by act.inicio asc";

$res = $con->query($query);

$i = 6;
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
    $ingresofonoac  = $row['ingresofonoac'];
    $ingresofonoan  = $row['ingresofonoan'];
    $empresaac      = $row['empresaac'];
    $empresaan      = $row['empresaan'];
    $puntosac       = $row['puntosac'];
    $puntosan       = $row['puntosan'];
    $totalventaac   = $row['totalventaac'];
    $totalventaan   = $row['totalventaan'];

    $rpastingresositio = 0;
    if($ingresositioan != 0)
        $rpastingresositio = ($ingresositioac / $ingresositioan) - 1;

    $rpastingresofono = 0;
    if($ingresofonoan != 0)
        $rpastingresofono = ($ingresofonoac / $ingresofonoan) - 1;

    $rpastempresa = 0;
    if($empresaan != 0)
        $rpastempresa = ($empresaac / $empresaan) - 1;

    $rpastpuntos = 0;
    if($puntosan != 0)
        $rpastpuntos = ($puntosac / $puntosan) - 1;

    $rpasttotalventas = 0;
    if($totalventaan != 0)
        $rpasttotalventas = ($totalventaac / $totalventaan) - 1;

    $excel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $inicio->format("H:i:s") . " - " . $hora->format("H:i:s"))
        ->setCellValue('B'.$i, $ingresositioac)
        ->setCellValue('C'.$i, $ingresositioan)
        ->setCellValue('D'.$i, $rpastingresositio)
        ->setCellValue('E'.$i, $ingresofonoac)
        ->setCellValue('F'.$i, $ingresofonoan)
        ->setCellValue('G'.$i, $rpastingresofono)
        ->setCellValue('H'.$i, $empresaac)
        ->setCellValue('I'.$i, $empresaan)
        ->setCellValue('J'.$i, $rpastempresa)
        ->setCellValue('K'.$i, $puntosac)
        ->setCellValue('L'.$i, $puntosan)
        ->setCellValue('M'.$i, $rpastpuntos)
        ->setCellValue('N'.$i, $totalventaac)
        ->setCellValue('O'.$i, $totalventaan)
        ->setCellValue('P'.$i, $rpasttotalventas);

    if(($rpastingresositio*100) > 0)
        $excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($colorbueno);
    else
        $excel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($colormalo);

    if(($rpastingresofono*100) > 0)
        $excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($colorbueno);
    else
        $excel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($colormalo);

    if(($rpastempresa*100) > 0)
        $excel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($colorbueno);
    else
        $excel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($colormalo);

    if(($rpastpuntos*100) > 0)
        $excel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($colorbueno);
    else
        $excel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($colormalo);

    if(($rpasttotalventas*100) > 0)
        $excel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($colorbueno);
    else
        $excel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($colormalo);

    $i++;
}

$estiloInformacion = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);

$color1 = array(
    'font' => array(
        'name'  => 'Calibri',
        'size' => '20',
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'dddddd'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => 'dddddd'
            )
        )
    )
);

$color2 = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold' => 'true',
        'size' => '10',
        'color' => array(
            'rgb' => 'ffffff'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => '35388E'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => 'BCBCBC'
            )
        )
    )
);

$color3 = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold' => 'true',
        'size' => '10',
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'ffffff'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => 'dddddd'
            )
        )
    )
);

$color4 = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold' => 'true',
        'size' => '10',
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'E0E1EE'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => 'dddddd'
            )
        )
    )
);

$color5 = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold' => 'true',
        'color' => array(
            'rgb' => '000000'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => 'C3CEFF'
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array(
                'rgb' => 'dddddd'
            )
        )
    )
);

$excel->getActiveSheet()->getStyle('A1:R1')->applyFromArray($color1);
$excel->getActiveSheet()->getStyle('B2:P2')->applyFromArray($color2);
$excel->getActiveSheet()->getStyle('A2:A4')->applyFromArray($color3);
$excel->getActiveSheet()->getStyle('B3:P4')->applyFromArray($color4);
$excel->getActiveSheet()->getStyle('A5:P'.$i)->applyFromArray($estiloInformacion);
$excel->getActiveSheet()->getStyle('A5:P5')->applyFromArray($color5);

$excel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('P')->setWidth('10');

$excel->getActiveSheet()->getStyle('B5:C' . ($i-1))->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('E5:F' . ($i-1))->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('H5:I' . ($i-1))->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('K5:L' . ($i-1))->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('N5:O' . ($i-1))->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('D5:D' . ($i-1))->getNumberFormat()->setFormatCode('#,##0 %');
$excel->getActiveSheet()->getStyle('G5:G' . ($i-1))->getNumberFormat()->setFormatCode('#,##0 %');
$excel->getActiveSheet()->getStyle('J5:J' . ($i-1))->getNumberFormat()->setFormatCode('#,##0 %');
$excel->getActiveSheet()->getStyle('M5:M' . ($i-1))->getNumberFormat()->setFormatCode('#,##0 %');
$excel->getActiveSheet()->getStyle('P5:P' . ($i-1))->getNumberFormat()->setFormatCode('#,##0 %');

for($j=2; $j<=($i-1); $j++)
    $excel->getActiveSheet()->getRowDimension($j)->setRowHeight(25);

// Se asigna el nombre a la hoja
$excel->getActiveSheet()->setTitle('Panel Venta por Hora');

// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$excel->setActiveSheetIndex(0);

// Inmovilizar paneles
//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
$excel->getActiveSheet(0)->freezePaneByColumnAndRow(0,6);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="paneltipoventahora.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('php://output');
exit;

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