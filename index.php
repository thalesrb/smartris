<?php
session_start();

include "config.php";
include "autoload.php";
require "src/funcoes.php";

use src\Buscas;

$objBuscas = new Buscas();

// força o download do arquivo
if ( strpos($_SERVER['REQUEST_URI'], "lote_gerar") ) {
    include 'public/lote_gerar.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Thales Bessa">

<title>SmartRIS - Teste Prático</title>

<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

<!-- Custom styles for this template -->
<link rel="stylesheet" href="/smart_ris/public/layout.css" >

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/smart_ris/">SmartRIS</a>
            </div>
            <div id="navbar">
                <div class="nav  navbar-left">
                    <ul class="nav navbar-nav">
                        <li><a href="/smart_ris/guia/">Montar guia</a></li>
                        <li><a href="/smart_ris/lote/">Montar lote</a></li>
                    </ul>
                </div>
                <?php if ( !empty($_SESSION["usuario"]) ) :?>
                <div class="nav navbar-right" style="display:none;">
                    <ul class="nav navbar-nav">
                        <li><?php print $_SESSION["usuario"];?></li>
                        <li>
                            <button data-toggle="popover" class="">
                              <img src="" >
                            </button>
                          </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>

    <div class="container content">

		<?php
        $arquivo = retorna_arquivo();
        include $arquivo;
        ?>

	</div><!-- /.container -->

    <footer class="footer">
        <div class="container">
            <p class="text-muted">SmartRIS - Teste Prático - Thales Bessa</p>
        </div>
    </footer>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

</body>
</html>
