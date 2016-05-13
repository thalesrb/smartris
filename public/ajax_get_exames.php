<?php
header('Content-Type: application/json');

include "../config.php";
require "../src/Buscas.php";

use src\Buscas;

$objBuscas = new \src\Buscas();

$exames = $objBuscas->buscar_exames($_GET["busca"]);

$result = json_encode($exames);

print $result;