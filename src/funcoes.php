<?php
/**
 * Arquivo com funções diversas
 */

/**
 * Se houver retorna o caminho do arquivo
 */
function retorna_arquivo()
{
    $url_partes = explode('/', $_SERVER['REQUEST_URI']);
    $tela = $url_partes[2];

    $pagina = ( !empty($tela) ) ? addslashes($tela) : "listagem";

    $arquivo = "public/{$pagina}.php";

    if ( !file_exists($arquivo) ) {
        $arquivo = "public/404.php";
    }

    // exige o login
    if ( !isset($_SESSION["usuario_logado"]) && $arquivo != "login") {
        $arquivo = "public/form_login.php";
    }

    if ( $pagina == "login") {
        $arquivo = "src/login.php";
    }

    return $arquivo;
}






