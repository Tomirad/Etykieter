<?php

include './vendor/autoload.php';
error_reporting(E_ALL);
$stickers = new \Treto\PrintStickers;

if(isset($_POST['show'])) {
	$stickers -> setData($_POST['excelData']);
} elseif(isset($_POST['get'])) {
	$stickers -> setData($_POST['excelData']);
	$stickers -> preparePdf();
	die();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>ETYKIETER ANEXBABY</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/pure-min.css">
	<link rel="stylesheet" href="/grids-min.css">
	<link rel="stylesheet" href="/grids-responsive-min.css">
                
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		.sticker {
			margin: 15px 10px;
			border: 1px solid #333;
			width: 60mm;
			height: 20mm;
			text-align: center;
		}
		div.sticker::nth-child-1, div.sticker::nth-child-3 {
		}
		div.sticker::nth-child-2 {
			position: relative;
			top: 0;
			width: 50mm;
			height: 15mm;
		}
		textarea {
			width: 700px;
			height: 150px;
			font-size: 0.9em;
		}
		form div {
			padding: 5px 30px;
		}
	</style>
</head>
<body>
	<form action='index.php' method='post'>
		<div>
			<textarea name='excelData'><?php echo $_POST['excelData'] ?? '' ?></textarea>
		</div>
		<div><button type='submit' name='show'>Wyświetl etykiety</button> <button type='submit' name='get'>Pobierz etykiety jako PDF</button></div>
</form>
<div class='pure-g'>
	<?php echo $stickers -> getListStickers() ?>
</div>
</body>
</html>