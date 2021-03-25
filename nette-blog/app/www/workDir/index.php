<!DOCTYPE html>

<html lang="cs-cz">
<head>
	<meta charset="utf-8" />
	<title>Galerie obrázků</title>
</head>

<body>
<h1>Galerie obrázků</h1>
<?php
require_once('triedy/Galeria.php');

$galeria = new Galeria('obrazky', "5");
$galeria->nacitaj();
$galeria->vypis();

?>
</body>
</html>
