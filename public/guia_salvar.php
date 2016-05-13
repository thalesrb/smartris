<?php
use src\Salvar;

$objSalvar = new Salvar();

$objSalvar->cadastra_guia($_POST);

$redireciona = URL_SITE . "guia/?mensagem=cadastrado";

header("Location: {$redireciona}");
