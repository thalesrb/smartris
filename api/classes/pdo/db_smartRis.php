<?php
namespace classes\pdo;

class db_smartRis extends PdoMysql {

	public function __construct() {
		
		$dbname = DB_DATABASE;
		
		$this->connect($dbname);
	}

}