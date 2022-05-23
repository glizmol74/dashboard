<?php


date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Gualberto Molinar")
							 ->setLastModifiedBy("Gualberto Molinar")
							 ->setTitle("Reporte desde Dashboard")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Facturas");

$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()
                                ->setHorizontal(( PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

                             
$objPHPExcel->getActiveSheet(0)->getStyle('B2:B2')->applyFromArray(
            array('fill' 	=> array(
                                        'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                                        'color'		=> array('argb' => '9C9C9C9C')
                                    ),
                    'borders' => array(
                                        'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
                                        'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
                                        'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
                                        'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
                                    )
                    )
            );

$objPHPExcel->getActiveSheet(0)->getStyle("B2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet(0)->getStyle( "B2" )
                ->getAlignment( )
                    ->setHorizontal( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )
                    ->setVertical( PHPExcel_Style_Alignment::VERTICAL_CENTER );
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->duplicateStyle(
    $objPHPExcel->getActiveSheet()->getStyle('B2'), 'C2:H2'
);

$objPHPExcel->getActiveSheet(0)->duplicateConditionalStyle(
    $objPHPExcel->getActiveSheet(0)->getStyle('B2')->getConditionalStyles(),
    'C2:H2'
  );

  for ($col = 'A'; $col != 'H'; $col++) {
    $objPHPExcel->getActiveSheet(0)->getColumnDimension($col)->setAutoSize(false);
             }

$objPHPExcel->getActiveSheet()->getColumnDimension( 'D' )->setWidth( (int)( 50) ); 

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('h1', 'Hello')
            ->setCellValue('D2', 'Bienvenido Mundo Wold');

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'xxxxyyyy');

// Rename worksheet
$objPHPExcel->getActiveSheet(0)->setTitle('Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 2030 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
