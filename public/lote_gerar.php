<?php
header('Content-disposition: attachment; filename="lote.xml"');
header('Content-type: "text/xml"; charset="utf8"');

use src\MontaXML;

$objMontaXML = new MontaXML();

$guias_pacientes = $_POST["guias"];

$dados_guias = $objBuscas->busca_guias_lote($guias_pacientes);

$xml = $objMontaXML->retorna_xml_tiss($guias_pacientes, $dados_guias);

print $xml;
