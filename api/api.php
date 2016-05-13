<?php
include __DIR__.'/../config.php';
include __DIR__.'/../autoload.php';

$autoloaderAPI = require_once __DIR__ . '/vendor/autoload.php';

$autoloaderAPI->add('controller\\', __DIR__);
$autoloaderAPI->add('model\\', __DIR__);

$matches = array();
preg_match('/(\d)\.(\d)\.(\d)/', PHP_VERSION, $matches);

if (version_compare($matches['0'], '5.3.3', '<')) {
    exit('Necessario PHP 5.3.3 ou maior');
}

session_start();

$app = new Silex\Application();
$app['debug'] = false;


$app->get('/', function () use ($app) {
    $text = 'Rota de exemplo, vc pode usar /hello/{param} para testar<br />';
    $text .= 'As rotas de EMKT são: /emkt/acao/{codigo}, /emkt/mensagem/{codigo} e /emkt/lista/{codigo}<br />';
    $text .= 'O esquema é executar pelo POSTMAN<br />';

    return $text;
});


// Registra o módulo, que tem as rotas
$app->mount('/', require_once __DIR__ . '/router/smartRis.php');

// Executa
$app->run();