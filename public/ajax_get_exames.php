<?php
header('Content-Type: application/json');

include "../config.php";
require "../src/Buscas.php";

use src\Buscas;

$objBuscas = new \src\Buscas();

$pagina = ( isset($_GET["pagina"]) ) ? $_GET["pagina"] : 1;

$exames = $objBuscas->buscar_exames($_GET["busca"], $pagina);

$result = json_encode($exames);

print $result;