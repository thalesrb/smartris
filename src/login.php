<?php

// aceita qualquer dado
if ( isset($_POST["cmp_login"]) ) {
    $_SESSION["usuario_logado"] = true;
    $_SESSION["usuario"] = $_POST["cmp_login"];
}

$origem = $_SERVER["HTTP_REFERER"];

header("Location: {$origem}");