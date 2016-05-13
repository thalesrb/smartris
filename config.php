<?php
/**
 * Configurações do sistema
 */

// defines do mysql
define("DB_HOST",     "localhost");
define("DB_DATABASE", "smartris");
define("DB_USER",     "root");
define("DB_PASS",     "");

// maximo de registro retornados em uma query
define("MAX_ITENS", 10);


// defines para as URLS
define("URL_SITE", "http://" . $_SERVER["HTTP_HOST"] . "/smart_ris/");
define("URL_API",  URL_SITE . "api/");


