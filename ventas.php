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

$titulos1 = array('Día Actual - ' . diasem($buscaract->format("D")) . ", " . $buscaract->format("d-m-Y"),
                  'Día Anterior - ' . diasem($buscarant->format("D")) . ", " . $buscarant->format("d-m-Y"), '% R/Past', '% Peso Venta');

$titulos2 = array('Hora', 'Ingreso Bruto', 'Click & Collect', 'Pendiente Validación', 'Anulaciones', 'Novios', 'Ingreso Neto (Sin IVA)');

$titulos3 = array('Monto $', '#', 'Monto por Hora $', 'Monto Acumulado $');


$excel->setActiveSheetIndex(0)
    ->mergeCells('A1:R1')

    ->mergeCells('A2:N2')
    ->mergeCells('O2:P2')
    ->mergeCells('Q2:Q4')
    ->mergeCells('R2:R4')

    ->mergeCells('A3:A4')
    ->mergeCells('B3:C3')
    ->mergeCells('D3:E3')
    ->mergeCells('F3:G3')
    ->mergeCells('H3:I3')
    ->mergeCells('J3:K3')
    ->mergeCells('L3:N3')
    ->mergeCells('O3:P3');

$excel->setActiveSheetIndex(0)
    ->setCellValue('A1', $titulo)

    ->setCellValue('A2', $titulos1[0])
    ->setCellValue('O2', $titulos1[1])
    ->setCellValue('Q2', $titulos1[2])
    ->setCellValue('R2', $titulos1[3])

    ->setCellValue('A3', $titulos2[0])
    ->setCellValue('B3', $titulos2[1])
    ->setCellValue('D3', $titulos2[2])
    ->setCellValue('F3', $titulos2[3])
    ->setCellValue('H3', $titulos2[4])
    ->setCellValue('J3', $titulos2[5])
    ->setCellValue('L3', $titulos2[6])
    ->setCellValue('O3', $titulos2[6])

    ->setCellValue('B4', $titulos3[0])
    ->setCellValue('C4', $titulos3[1])
    ->setCellValue('D4', $titulos3[0])
    ->setCellValue('E4', $titulos3[1])
    ->setCellValue('F4', $titulos3[0])
    ->setCellValue('G4', $titulos3[1])
    ->setCellValue('H4', $titulos3[0])
    ->setCellValue('I4', $titulos3[1])
    ->setCellValue('J4', $titulos3[0])
    ->setCellValue('K4', $titulos3[1])
    ->setCellValue('L4', $titulos3[3])
    ->setCellValue('M4', $titulos3[3])
    ->setCellValue('N4', $titulos3[1])
    ->setCellValue('O4', $titulos3[3])
    ->setCellValue('P4', $titulos3[1]);

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

$total = "select mingresobrutoacum, ordingresobrutoacum, mclickacum, ordclickacum, mpendacum, ordpendacum,
                                     manulacum, ordanulacum, mnoviosacum, ordnoviosacum, mingresonetoacum, ordingresonetoacum

                              from resultadosp1

                              where diaactual = $actual and mingresonetoacum <> 0 order by inicio desc limit 1";

$res = $con->query($total);

$mingresobrutoacum   = 0;
$ordingresobrutoacum = 0;
$mclickacum          = 0;
$ordclickacum        = 0;
$mpendacum           = 0;
$ordpendacum         = 0;
$manulacum           = 0;
$ordanulacum         = 0;
$mnoviosacum         = 0;
$ordnoviosacum       = 0;
$mingresonetoacum    = 0;
$ordingresonetoacum  = 0;

while($row = mysqli_fetch_assoc($res)) {
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

                              where diaactual = $anterior and mingresonetoacum <> 0 order by inicio desc limit 1";

$res = $con->query($total);

$mingresonetopacum = 0;
$ordingresonetopacum = 0;

while($row = mysqli_fetch_assoc($res)){
    $mingresonetopacum = $row['mingresonetoacum'];
    $ordingresonetopacum = $row['ordingresonetoacum'];
}

$rpast            = 0;
if($mingresonetopacum != 0)
    $rpast        = ($mingresonetoacum/$mingresonetopacum)-1;



$excel->setActiveSheetIndex(0)
    ->setCellValue('A5', "Total a las " . date("H:i"))
    ->setCellValue('B5', $mingresobrutoacum)
    ->setCellValue('C5', $ordingresobrutoacum)
    ->setCellValue('D5', $mclickacum)
    ->setCellValue('E5', $ordclickacum)
    ->setCellValue('F5', $mpendacum)
    ->setCellValue('G5', $ordpendacum)
    ->setCellValue('H5', $manulacum)
    ->setCellValue('I5', $ordanulacum)
    ->setCellValue('J5', $mnoviosacum)
    ->setCellValue('K5', $ordnoviosacum)
    ->setCellValue('L5', "-")
    ->setCellValue('M5', $mingresonetoacum)
    ->setCellValue('N5', $ordingresonetoacum)
    ->setCellValue('O5', $mingresonetopacum)
    ->setCellValue('P5', $ordingresonetopacum)
    ->setCellValue('Q5', $rpast)
    ->setCellValue('R5', 1);

if(($rpast*100) > 0)
    $excel->getActiveSheet()->getStyle('Q5')->applyFromArray($colorbueno);
else
    $excel->getActiveSheet()->getStyle('Q5')->applyFromArray($colormalo);

$query = "select act.hora as fin, act.inicio as inicio, act.mingresobrutoacum as brutoactual, act.ordingresobrutoacum as ordbrutoactual,
                 act.mclickacum as clickactual, act.ordclickacum as ordclickactual, act.mpendacum as pendactual, act.ordpendacum as ordpendactual,
                 act.manulacum as anulactual, act.ordanulacum as ordanulactual, act.mnoviosacum as noviosactual, act.ordnoviosacum as ordnoviosactual,
                 act.mingresonetohora as netohoraactual, act.mingresonetoacum as netoactual, act.ordingresonetoacum as ordnetoactual,
                 act.rpastacum as peso, ant.mingresonetoacum as netoanterior, ant.ordingresonetoacum ordnetoanterior

                 from resultadosp1 ant, resultadosp1 act

                 where ant.diaactual = $anterior and act.diaactual = $actual and act.hora = ant.hora and act.inicio = ant.inicio order by act.inicio asc";

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
        $rpast        = ($mingresonetoacum/$mingresonetop)-1;

    $excel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $inicio->format("H:i:s") . " - " . $hora->format("H:i:s"))
        ->setCellValue('B'.$i, $mingresobruto)
        ->setCellValue('C'.$i, $ordingresobruto)
        ->setCellValue('D'.$i, $mclick)
        ->setCellValue('E'.$i, $ordclick)
        ->setCellValue('F'.$i, $mpend)
        ->setCellValue('G'.$i, $ordpend)
        ->setCellValue('H'.$i, $manul)
        ->setCellValue('I'.$i, $ordanul)
        ->setCellValue('J'.$i, $mnovios)
        ->setCellValue('K'.$i, $ordnovios)
        ->setCellValue('L'.$i, $mingresonetohora)
        ->setCellValue('M'.$i, $mingresonetoacum)
        ->setCellValue('N'.$i, $ordingresoneto)
        ->setCellValue('O'.$i, $mingresonetop)
        ->setCellValue('P'.$i, $ordingresonetop)
        ->setCellValue('Q'.$i, $rpast)
        ->setCellValue('R'.$i, ($pesoventa/100));

    if(($rpast*100) > 0)
        $excel->getActiveSheet()->getStyle('Q'. $i)->applyFromArray($colorbueno);
    else
        $excel->getActiveSheet()->getStyle('Q'. $i)->applyFromArray($colormalo);

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
            'rgb' => '337ab7'
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
            'rgb' => 'ffffff'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => '4E85FC'
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
            'rgb' => 'ffffff'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => '7E9FE7'
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
        'size' => '10',
        'color' => array(
            'rgb' => 'ffffff'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => '5A82D7'
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

$color6 = array(
    'font' => array(
        'name'  => 'Calibri',
        'bold' => 'true',
        'color' => array(
            'rgb' => 'ffffff'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'rgb' => '7E7D7D'
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

$color7 = array(
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
$excel->getActiveSheet()->getStyle('A2:N2')->applyFromArray($color2);
$excel->getActiveSheet()->getStyle('A3:A4')->applyFromArray($color2);
$excel->getActiveSheet()->getStyle('B3:E4')->applyFromArray($color3);
$excel->getActiveSheet()->getStyle('F3:K4')->applyFromArray($color4);
$excel->getActiveSheet()->getStyle('L3:N4')->applyFromArray($color2);
$excel->getActiveSheet()->getStyle('O2:R4')->applyFromArray($color5);
$excel->getActiveSheet()->getStyle('R5:R'.($i-1))->applyFromArray($color6);
$excel->getActiveSheet()->getStyle('A5:R'.$i)->applyFromArray($estiloInformacion);
$excel->getActiveSheet()->getStyle('A5:P5')->applyFromArray($color7);

$excel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth('20');
$excel->setActiveSheetIndex(0)->getColumnDimension('P')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('Q')->setWidth('10');
$excel->setActiveSheetIndex(0)->getColumnDimension('R')->setWidth('10');

$excel->getActiveSheet()->getStyle('B5:P' . ($i-1))->getNumberFormat()->setFormatCode('#,##0');
$excel->getActiveSheet()->getStyle('Q5:Q' . ($i-1))->getNumberFormat()->setFormatCode('#,##0 %');
$excel->getActiveSheet()->getStyle('R5:R' . ($i-1))->getNumberFormat()->setFormatCode('#,##0.0 %');

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
header('Content-Disposition: attachment;filename="panelventahora.xlsx"');
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