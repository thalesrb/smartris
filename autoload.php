<?php
/**
 * Faz o autoload das classes
 */

spl_autoload_register(function ($class_name) {

	$arquivo = "{$class_name}.php";

	if ( !file_exists($arquivo) ) {
		return false;
	}

	include $arquivo;
});