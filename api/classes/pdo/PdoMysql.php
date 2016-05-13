<?php
namespace classes\pdo;

use PDO;

class PdoMysql extends \PDO 
{
	private $host     = DB_HOST;
	private $user     = DB_USER;
	private $pass     = DB_PASS;
	public  $dao;

    /**
     * Baseado nos dados passados conecta no banco, usando o PDO
     *
     * @param array $arr_db_dados
     * @return boolean
     */
    public function connect($dbname) {

        try {
            // data source name
            $dsn = "mysql:host={$this->host};dbname={$dbname};charset=utf8mb4";
            
            $this->db = new PDO($dsn, $this->user, $this->pass);

            return true;
        }
        catch(PDOException $e) {
            $this->grava_erro($e->getMessage(), "0", "Banco: {$dbname}");
            return false;
        }

    }

    public function query($query)
    {
        if ( !$this->db ) {
            return false;
        }

        $res = $this->db->query($query);

        if ( $res === false ) {
        	$this->num_rows = 0;
            $erro = $this->db->errorInfo();
            $erro_num = $erro[1];
            $msg_erro = $erro[2];

            $this->grava_erro($msg_erro, $erro_num, $query);
            return false;
        }

        $this->num_rows = $res->rowCount();
        
        return $res;
    }

    public function fetch($res)
    {
        if ( !$res ) {
            return false;
        }
        
        return $this->fetch_assoc($res);
    }

    public function fetch_assoc(\PDOStatement $res)
    {
        while ($row = $res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
            return $row;
        }
    }

    public function num_rows()
    {
        return $this->num_rows;
    }

    private function grava_erro($msg_erro, $erro_num, $query)
    {
        echo("Mysql erro: {$msg_erro}\n");
    }


}