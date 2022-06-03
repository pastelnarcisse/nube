<?php
namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Name:    Statistics
 *
 * Created:  10.11.2021
 *
 * Description:  Modified auth system based on redux_auth with extensive customization.
 *               This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP7.2 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Ben Edmunds <ben.edmunds@gmail.com>
 * @author     Phil Sturgeon
 * @author     Benoit VRIGNAUD <benoit.vrignaud@zaclys.net>
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */

/**
 * This class is the IonAuth library.
 */
class Reports
{
	/**
	 * Configuration
	 *
	 * @var \IonAuth\Config\IonAuth
	 */
	protected $config;

	/**
	 * IonAuth model
	 *
	 * @var \IonAuth\Models\IonAuthModel
	 */
	protected $reportsModel;


	/**
	 * __construct
	 *
	 * @author Ben
	 */
	public function __construct()
	{
		
		$this->config = config('NubeNarcisse');

		helper('cookie');

		$this->session = session();

		$this->reportsModel = new \App\Models\ReportsModel();

		$this->reportsModel->triggerEvents('library_constructor');
	}

	/**
	 * DAME LOS DEPARTAMENTOS 
	 * @param array status Estado de los departamentos
	 * @return array Consulta | error 
	 * 
	 * */
	public function getDepartment($status = [1]){

		$result = $this->reportsModel->getDepartment($status);


		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}

		return $result;
	}

	/**
	 * DAME LOS CATEGORÍAS 
	 * @param array status Estado de las categorías
	 * @return array Consulta | error 
	 * 
	 * */
	public function getCategory($status = [1]){

		$result = $this->reportsModel->getCategory($status);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}

		foreach ($result['rows'] as $row) {
			$result['dep_id_group'][$row['dep_id']]['cat_id_group'][$row['cat_id']] = $row;
			$result['dep_id_group'][$row['dep_id']]['dep_id'] = $row['dep_id'];
			$result['dep_id_group'][$row['dep_id']]['nombre'] = $row['depNombre'];
			$result['cat_ids'][] = intval($row['cat_id']);
		}

		return $result;
	}


	/**
	 * DAME LAS VENTAS GENERALES
	 * @param object $input Entrada de valor para consultar
	 * @return array Consulta | error 
	 * 
	 * */
	public function getSaleGeneral($input){

		$this->reportsModel->triggerEvents('getSaleGeneral');
		
		$result = $this->reportsModel->getSalesGeneral($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}

		return $result;
	}

	/**
	 * CREA EL OBJETO DE HOJA DE CALCULO
	 * @param array $input Entrada de valor para consultar
	 * @return object spredsheet 
	 * 
	 * */
	public function spredsheetSaleGeneral($input){

		$spreadsheet = new Spreadsheet();
   
        $sheet = $spreadsheet->getActiveSheet();	

        // CREA LOS NOMBRES LAS COLUMNAS EN EL EXCEL
        $c = 'A';
        $rowName = $r = 7;
        $rowStart = $rowName+1;
        $column_ticket = false;
        $column_total = false;
        $column_cash = false;
        $column_card = false;
        $column_transfer = false;
        $column_movement = false;
        foreach ($input['columns'] as $column) {
        	$sheet->setCellValue($c.$r,$column);
        	$column_ticket = $column == 'TICKET' ? $c : $column_ticket;
        	$column_total = $column == 'TOTAL' ? $c : $column_total;
			$column_cash = $column == 'EFECTIVO' ? $c : $column_cash;
			$column_card = $column == 'TARJETA' ? $c : $column_card;
			$column_transfer = $column == 'TRANSFERENCIA' ? $c : $column_transfer;
			$column_movement = $column == 'MOVIMIENTO' ? $c : $column_movement;
        	$c++;
        }   

        // CREA LAS FILAS DE DATOS
        $c = 'A';
        $r++;
        $sheet->fromArray($input['rows'], NULL, $c.$r);

        // FILTRO TABLA
		$lastRow = $sheet->getHighestRowAndColumn();
		$c = 'A';
		$sheet->setAutoFilter($c.$rowName.':'.$lastRow['column'].$lastRow['row']);

		// ESTILO DE LA CABECERA DE LA TABLA
		$row_range_names = 'A'.$rowName.':'.$lastRow['column'].$rowName;
		$sheet->getStyle($row_range_names)->getFont()->setBold(true);

		// FORMATO Y FORMULA DE LAS COLUMNAS DE LAS QUE SI EXISTE
		$format = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING_USD;

		if ($column_ticket) {
			$sheet->getStyle($column_ticket)->getAlignment()->setHorizontal('center');
		}

		if ($column_movement) {
			$sheet->getStyle($column_movement)->getAlignment()->setHorizontal('center');
		}

		if ($column_total) {
			$column_range_total = $column_total.$rowStart.':'.$column_total.$lastRow['row'];
			$sheet->setCellValue('A2','TOTAL');
			$sheet->setCellValue('B2','=SUM('.$column_range_total.')');
			$sheet->getStyle('B2')->getNumberFormat()->setFormatCode($format);
			$sheet->getStyle('A2:B2')->getFont()->setBold(true);
			$sheet->getStyle($column_range_total)->getNumberFormat()->setFormatCode($format);
			$sheet->getStyle($column_total)->getAlignment()->setHorizontal('right');
		}

		if ($column_cash) {
			$column_range_cash = $column_cash.$rowStart.':'.$column_cash.$lastRow['row'];
			$sheet->setCellValue('A3','EFECTIVO');
			$sheet->setCellValue('B3','=SUM('.$column_range_cash.')');
			$sheet->getStyle('B3')->getNumberFormat()->setFormatCode($format);
			$sheet->getStyle($column_range_cash)->getNumberFormat()->setFormatCode($format);
			$sheet->getStyle($column_cash)->getAlignment()->setHorizontal('right');
		}

		if ($column_card) {
			$column_range_card = $column_card.$rowStart.':'.$column_card.$lastRow['row'];
			$sheet->setCellValue('A4','TARJETA');
			$sheet->setCellValue('B4','=SUM('.$column_card.$rowStart.':'.$column_card.$lastRow['row'].')');
			$sheet->getStyle('B4')->getNumberFormat()->setFormatCode($format);
			$sheet->getStyle($column_range_card)->getNumberFormat()->setFormatCode($format);
			$sheet->getStyle($column_card)->getAlignment()->setHorizontal('right');
		}

		if ($column_transfer) {
			$column_range_transfer = $column_transfer.$rowStart.':'.$column_transfer.$lastRow['row'];
			$sheet->setCellValue('A5','TRANSFERENCIA');
			$sheet->setCellValue('B5','=SUM('.$column_transfer.$rowStart.':'.$column_transfer.$lastRow['row'].')');
			$sheet->getStyle('B5')->getNumberFormat()->setFormatCode($format);
			$sheet->getStyle($column_range_transfer)->getNumberFormat()->setFormatCode($format);
			$sheet->getStyle($column_transfer)->getAlignment()->setHorizontal('right');
		}

        // AUTOAJUSTAR
        foreach ($sheet->getColumnIterator() as $column) {
			$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		 // TITULO
        $sheet->setCellValue('A1','Reporte de Ventas por tipo de pago');
		$sheet->getStyle('A1:A1')->getFont()->setBold(true)->setSize(16);
		$sheet->getStyle("A1:".$lastRow['column'].'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('007E86');
		$sheet->getStyle("A1:".$lastRow['column'].'1')->getFont()->getColor()->setRGB('FFFFFF');
		$sheet->getStyle("B2:".$lastRow['column'].'5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('00FEFA');	
		$sheet->mergeCells("A1:E1");
		$sheet->setTitle('todo');

        return $spreadsheet;

	}

	/**
	 * CREA CONJUNTO DE DATOS EN BASE 64 EXCEL
	 *
	 * @param object $input Arreglo de datos para la consulta
	 * @return array
	 * @author Chununo
	 */
	public function createExcelSaleGeneral($input){

		$this->reportsModel->triggerEvents('creatExcelSaleGeneral');
		
		$result = $this->getSaleGeneral($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}

		ob_start();

		$spreadsheet = $this->spredsheetSaleGeneral($result);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="file.xlsx"');
        $writer->save("php://output");
        $xlsData = ob_get_contents();

        ob_end_clean();

        $result['codigo'] = 200;
        $result['file'] = "data:application/vnd.ms-Excel;base64,".base64_encode($xlsData);
		return $result;
		
	}


	/**
	 * CREA CONJUNTO DE DATOS EN BASE 64 PDF
	 *
	 * @param object $input Arreglo de datos para la consulta
	 * @return array
	 * @author Chununo
	 */
	public function createPDFSaleGeneral(object $input){

		$this->reportsModel->triggerEvents('creatExcelSaleGeneral');
		
		$result = $this->getSaleGeneral($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}
		ob_start();

		$spreadsheet = $this->spredsheetSaleGeneral($result);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="file.pdf"');
        $writer->save("php://output");
        $pdfData = ob_get_contents();
        ob_end_clean();

        $result['codigo'] = 200;  
        $result['file'] = "data:application/pdf;base64,".base64_encode($pdfData);
		return $result;
		
	}

	/**
	 * DAME LOS ARTÍCULOS VENDIDOS
	 * @param object $input Entrada de valor para consultar
	 * @return array Consulta | error 
	 * 
	 * */
	public function getSalesProducts($input){

		$this->reportsModel->triggerEvents('getSalesProducts');
		
		$result = $this->reportsModel->getSalesProducts($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}

		return $result;
	}

	/**
	 * CREA EL OBJETO DE HOJA DE CALCULO
	 * @param array $input Entrada de valor para consultar
	 * @return object spredsheet 
	 * 
	 * */
	public function spredsheetSaleProduct($input){

		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $c = 'A';
        $r = 1;
        $struct = array('categoria' => array());

        foreach ($input['rows'] as $row) {

        	$catuni = $row['CATEGORIA'].$row['UNIDAD'];

        	if (!isset($struct['categoria'][$catuni])) {
        		$r++;
        		$struct['categoria'][$catuni] = array(
        			'CANTIDAD' => floatval($row['CANTIDAD']),
        			'POSICION_NOMBRE' => 'A'.$r,
        			'POSICION_CANTIDAD' => 'B'.$r,
        			'NOMBRE' => $row['CATEGORIA']
        		);
        		$sheet->setCellValue('A'.$r,$row['CATEGORIA']);
        		$sheet->setCellValue('B'.$r,$row['CANTIDAD']);
        		$sheet->setCellValue('C'.$r,$row['UNIDAD']);

        	}else{
				$cantidad = $struct['categoria'][$catuni]['CANTIDAD'] += floatval($row['CANTIDAD']);
				$posicion_cantidad = $struct['categoria'][$row['CATEGORIA'].$row['UNIDAD']]['POSICION_CANTIDAD'];
				$sheet->setCellValue($posicion_cantidad, $cantidad );
        	}
        	 
        }



        // CREA LOS NOMBRES LAS COLUMNAS EN EL EXCEL
        
        $rowName = $r++;
        $rowStart = $rowName++;
        $column_ticket = false;
        $column_cantidad = false;
        $column_categoria = false;
        foreach ($input['columns'] as $column) {
        	$sheet->setCellValue($c.$r,$column);
        	$column_ticket = $column == 'TICKET' ? $c : $column_ticket;
        	$column_cantidad = $column == 'CANTIDAD' ? $c : $column_cantidad;
        	$column_categoria = $column == 'CATEGORIA' ? $c : $column_categoria;
        	$c++;
        }

        // CREA LAS FILAS DE DATOS
        $c = 'A';
        $r++;
        $sheet->fromArray($input['rows'], NULL, $c.$r);

        // FILTRO TABLA
		$lastRow = $sheet->getHighestRowAndColumn();
		$c = 'A';
		$sheet->setAutoFilter($c.$rowName.':'.$lastRow['column'].$lastRow['row']);

		// ESTILO DE LA CABECERA DE LA TABLA
		$row_range_names = 'A'.$rowName.':'.$lastRow['column'].$rowName;
		$sheet->getStyle($row_range_names)->getFont()->setBold(true);

		// FORMATO Y FORMULA DE LAS COLUMNAS DE LAS QUE SI EXISTE
		$format = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING_USD;

		if ($column_ticket) {
			$sheet->getStyle($column_ticket)->getAlignment()->setHorizontal('center');
		}

		if ($column_cantidad) {
			$sheet->getStyle($column_ticket)->getAlignment()->setHorizontal('right');			
		}

        // AUTOAJUSTAR
        foreach ($sheet->getColumnIterator() as $column) {
			$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		// DATOS GENERALES
		$sheet->getStyle('A2:B'.($rowName-1))->getAlignment()->setHorizontal('right');
		$sheet->getStyle('A2:B'.($rowName-1))->getFont()->setBold(true)->setSize(11);

		 // TITULO
        $sheet->setCellValue('A1','Reporte de Ventas por artículos');
		$sheet->getStyle('A1:A1')->getFont()->setBold(true)->setSize(16);
		$sheet->getStyle("A1:".$lastRow['column'].'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('007E86');
		$sheet->getStyle("A1:".$lastRow['column'].'1')->getFont()->getColor()->setRGB('FFFFFF');
		$sheet->getStyle("B2:".$lastRow['column'].($rowName-1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('00FEFA');	
		$sheet->mergeCells("A1:E1");
		$sheet->setTitle('todo');

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        return $spreadsheet;

	}
	
	/**
	 * CREA CONJUNTO DE DATOS EN BASE 64 EXCEL
	 *
	 * @param object $input Arreglo de datos para la consulta
	 * @return array
	 * @author Chununo
	 */
	public function createExcelSaleProduct($input){

		$this->reportsModel->triggerEvents('creatExcelSaleGeneral');
		
		$result = $this->getSalesProducts($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}

		ob_start();

		$spreadsheet = $this->spredsheetSaleProduct($result);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="file.xlsx"');
        $writer->save("php://output");
        $xlsData = ob_get_contents();

        ob_end_clean();

        $result['codigo'] = 200;
        $result['file'] = "data:application/vnd.ms-Excel;base64,".base64_encode($xlsData);
		return $result;
		
	}

	/**
	 * CREA CONJUNTO DE DATOS EN BASE 64 PDF
	 *
	 * @param object $input Arreglo de datos para la consulta
	 * @return array
	 * @author Chununo
	 */
	public function createPDFSaleProduct(object $input){

		$this->reportsModel->triggerEvents('creatExcelSaleGeneral');
		
		$result = $this->getSalesProducts($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}
		ob_start();

		$spreadsheet = $this->spredsheetSaleProduct($result);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="file.pdf"');
        $writer->save("php://output");
        $pdfData = ob_get_contents();
        ob_end_clean();

        $result['codigo'] = 200;  
        $result['file'] = "data:application/pdf;base64,".base64_encode($pdfData);
		return $result;
		
	}


	/**
	 * DAME LOS INVENTARIOS DE ARTICULOS
	 * @param object $input Entrada de valor para consultar
	 * @return array Consulta | error 
	 * 
	 * */
	public function getReportsStock($input){

		$this->reportsModel->triggerEvents('getReportsStock');
		
		$result = $this->reportsModel->getProductsStock($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}

		foreach ($result['rows'] as $arts) {

			if (!isset($result['categoria'][$arts['CATEGORIA']])) {
				$result['categoria'][$arts['CATEGORIA']]['existencia'] = floatval($arts['EXISTENCIA']);
				$result['categoria'][$arts['CATEGORIA']]['unidad'] = $arts['UNIDAD'];	 
			}else{
				$result['categoria'][$arts['CATEGORIA']]['existencia'] += $arts['EXISTENCIA'];	
			}
			
		}

		return $result;
	}


	/**
	 * CREA EL OBJETO DE HOJA DE CALCULO
	 * @param array $input Entrada de valor para consultar
	 * @return object spredsheet 
	 * 
	 * */
	public function spredsheetProductsStock($input){

		$spreadsheet = new Spreadsheet();
   
        $sheet = $spreadsheet->getActiveSheet();

        $c = 'A';
        $r = 1;

        // EXISTENCIA POR CATEGORIA
        $sheet->setCellValue('A'.$r,'CATEGORIA');
        $sheet->setCellValue('B'.$r,'EXISTENCIA');
        $r++;



        // AGREGAR LAS EXISTENCIA
        foreach ($input['categoria'] as $key => $value) {
        	$sheet->setCellValue('A'.$r,$key);
        	$sheet->setCellValue('B'.$r,$value['existencia']);
        	$sheet->setCellValue('C'.$r,$value['unidad']);
        	$sheet->getStyle('A'.$r.':C'.$r)->getFont()->setBold(true);
        	$r++;
        }

        // CREA LOS NOMBRES LAS COLUMNAS EN EL EXCEL
        $c = 'A';
        $r++;
        $rowName = $r;
        $rowStart = $rowName+1;


        foreach ($input['columns'] as $column) {
        	$sheet->setCellValue($c.$r,$column);
        	$c++;
        }   

        // CREA LAS FILAS DE DATOS
        $c = 'A';
        $r++;
        $sheet->fromArray($input['rows'], NULL, $c.$r);

        // FILTRO TABLA
		$lastRow = $sheet->getHighestRowAndColumn();
		$c = 'A';
		$sheet->setAutoFilter($c.$rowName.':'.$lastRow['column'].$lastRow['row']);

		// ESTILO DE LA CABECERA DE LA TABLA
		$row_range_names = 'A'.$rowName.':'.$lastRow['column'].$rowName;
		$sheet->getStyle($row_range_names)->getFont()->setBold(true);

		// FORMATO Y FORMULA DE LAS COLUMNAS DE LAS QUE SI EXISTE
		$format = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_ACCOUNTING_USD;
		

        // AUTOAJUSTAR
        foreach ($sheet->getColumnIterator() as $column) {
			$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		 // TITULO
        $sheet->setCellValue('A1','Reporte de inventarios '.date('Y-m-d H:i'));
		$sheet->getStyle('A1:A1')->getFont()->setBold(true)->setSize(18);
		$sheet->getStyle("A1:".$lastRow['column'].'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('007E86');
		$sheet->getStyle("A1:".$lastRow['column'].'1')->getFont()->getColor()->setRGB('FFFFFF');
		$sheet->getStyle("B2:".$lastRow['column'].($rowName-1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('00FEFA');	
		$sheet->mergeCells("A1:E1");
		$sheet->setTitle('todo');

        return $spreadsheet;

	}

	/**
	 * CREA CONJUNTO DE DATOS EN BASE 64 EXCEL
	 *
	 * @param object $input Arreglo de datos para la consulta
	 * @return array
	 * @author Chununo
	 */
	public function createExcelProductsStock($input){

		$this->reportsModel->triggerEvents('createExcelProductsStock');
		
		$result = $this->getReportsStock($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}

		ob_start();

		$spreadsheet = $this->spredsheetProductsStock($result);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="file.xlsx"');
        $writer->save("php://output");
        $xlsData = ob_get_contents();

        ob_end_clean();

        $result['codigo'] = 200;
        $result['file'] = "data:application/vnd.ms-Excel;base64,".base64_encode($xlsData);
		return $result;
		
	}

	/**
	 * CREA CONJUNTO DE DATOS EN BASE 64 PDF
	 *
	 * @param object $input Arreglo de datos para la consulta
	 * @return array
	 * @author Chununo
	 */
	public function createPDFProductsStock(object $input){

		$this->reportsModel->triggerEvents('createPDFProductsStock');
		
		$result = $this->getReportsStock($input);

		if (empty($result['columns'])) {
			$result['codigo'] = 500;
			$result['error'] .= '| sin datos |';
			return $result;
		}
		ob_start();

		$spreadsheet = $this->spredsheetProductsStock($result);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="file.pdf"');
        $writer->save("php://output");
        $pdfData = ob_get_contents();
        ob_end_clean();

        $result['codigo'] = 200;  
        $result['file'] = "data:application/pdf;base64,".base64_encode($pdfData);
		return $result;
		
	}
	

}
