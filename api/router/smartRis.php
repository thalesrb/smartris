<?php


// define o caminho das classes de controler
use controller\PacientesController;
use controller\TussTiposController;
use controller\TussExamesController;
use controller\GuiasController;

$ris = $app['controllers_factory'];

// Default emkt
$ris->get('/', function () use ($app) {
    $text = 'Default RIS';

    return $text;
});


// Rota para os pacientes
$ris->match('/pacientes/{id}', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request, $id) {
    $objPacientes = new PacientesController();
    $run  = $objPacientes->run($request, $app, $id);

    return $run;
})
->value('id', 0)
;


// Rota para os tipos TUSS
$ris->match('/tuss_tipos/{id}', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request, $id) {
    $objTussTipos = new TussTiposController();
    $run = $objTussTipos->run($request, $app, $id);

    return $run;
})
->value('id', 0)
;


// Rota para os termos TUSS
$ris->match('/tuss_exames/{id}', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request, $id) {
    $objTussExames = new TussExamesController();
    $run = $objTussExames->run($request, $app, $id);

    return $run;
})
->value('id', 0)
;


// Rota para os termos TUSS
$ris->match('/guias/{id}', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request, $id) {
    $objGuia = new GuiasController();
    $run     = $objGuia->run($request, $app, $id);

    return $run;
})
->value('id', 0)
;


return $ris;
