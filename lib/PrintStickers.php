<?php
/*
Example: 
dataW	[0] => 201945
-		[1] => PI-SGJ080111-1
-		[2] => Sp13-Q
-		[3] => 3in1
Number	[4] => 0001
EAN		[5] => 5902280013778
Company	[6] => SNA
Color	[7] => 377
Type	[8] => ES
Start	[9] => 1
End		[10] => 20

*/
namespace Treto;

class PrintStickers {

	private $stickerList = [];

	public function __construct() {
		$this -> barcode = new \Picqer\Barcode\BarcodeGeneratorPNG();
	}

	public function setData($content = '', $typeData = 'flat') {
		$data = [];
		$getLine = str_getcsv(trim($content), "\n");
		foreach($getLine as $line => $row) {
			if (!empty($row)) {
				$data[$line] = str_getcsv($row, "\t");
			}
		}
		$this -> setListStickers($data);
	}


	public function createSticker($var = []) {
		return "<div class='sticker pure-u-1-8'>
			<div align='center'><strong>{$var['date']} {$var['number']}</strong></div>
			<div align='center'>{$this->getBarcode($var['ean'], true)}</div>
			<div align='center'><strong>{$var['company']}-{$var['color']}-{$var['code']}{$var['count']}</strong></div>
		</div>";
	}

	public function preparePdf() {
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => [60, 20],
			'orientation' => 'P',
			'margin_left' => 3,
			'margin_right' => 3,
			'margin_top' => 2,
			'margin_bottom' => 0,
			'margin_header' => 0,
			'margin_footer' => 0,
			'default_font_size' => 8,
			'default_font' => 'dejavusans',
			''
		]);
		$mpdf -> WriteHTML(implode("<pagebreak page-break-type='slice'/>", $this -> stickerList));
		$mpdf -> SetTitle('Etykiety');
		$mpdf -> SetAuthor('Tomasz Trela');
		$mpdf -> SetCreator('Brain & Fingers');
		$mpdf -> autoPageBreak = 1;
		//$mpdf -> Output('etykiety.pdf');
		//header("Location: /etykiety.pdf");
		return $mpdf -> Output();
	}

	public function getListStickers() {
		return implode("\n", $this -> stickerList);
	}

	private function setListStickers($stickerData = []) {

		foreach($stickerData as $line => $data) {
			for($count = $data[9]; $count <= $data[10]; $count ++) {
				$this -> stickerList[] = $this -> createSticker([
					'date' => $data[0],
					'number' => $data[4],
					'ean' => $data[5],
					'company' => $data[6],
					'color' => $data[7],
					'code' => $data[8],
					'count' => str_pad($count, 4, '0', STR_PAD_LEFT)
				]);
			}
		}
	}

	private function getBarcode($eanCode = 0, $img = false) {
		$barcode = $this -> barcode -> getBarcode($eanCode, $this -> barcode::TYPE_EAN_13);
		return $img ? "<img src='data:image/png;base64, ". base64_encode($barcode) ."'>" : $barcode;
	}

}
