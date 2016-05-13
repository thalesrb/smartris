<?php
namespace model;

use classes\pdo\db_smartRis;

// extends ModelFuncoes
abstract class Model
{
	public $dao;
	public $table_name;
	public $primary_key;
	public $autoincrement;

	/**
	 * Define a DAO a ser usada, e recebe a tabela e primary_key(s)
	 *
	 * @param unknown $tabela
	 * @param unknown $primarykey
	 */
    public function __construct($tabela, $primarykey)
    {
        $this->dao          = new db_smartRis();
        $this->table_name   = $tabela;
        $this->primary_key  = $primarykey;
    }

    /**
     * Define os campos da tabela
     *
     * @param unknown $nome
     * @param string $tipo
     */
    public function setFieldDB($nome, $tipo = "text")
    {
        $prefix_nome = $nome;

        $this->_campos[$nome] = $prefix_nome;
    }

    /**
     * Faz o select de 1 registro usando a primary_key
     *
     * @param unknown $codigo
     * @return boolean|unknown
     */
    public function getData($codigo)
    {
        $query  = "SELECT *";
        $query .= " FROM {$this->table_name}";
        $query .= " WHERE {$this->primary_key} = '{$codigo}'";

        $res = $this->dao->query($query);
        return $res;
    }

    /**
     * Faz o SELECT de 'n' registros, aceita filtros e paginação
     *
     * @param number $limite
     * @param number $pagina
     * @return boolean|unknown
     */
    public function getList($limite = 15, $pagina = 1)
    {
        $query  = "SELECT *";
        $query .= " FROM {$this->table_name}";
        $query .= " WHERE 1";

        // seta os filtros padrões
        $query .= $this->setQueryFiltros();

        // seta a paginacao
        $query .= $this->setLimit($query, $pagina, $limite);

        $res = $this->dao->query($query);
        return $res;
    }

    /**
     * Faz o insert na tabela
     *
     * @param unknown $post
     * @return boolean
     */
    public function insert($post)
    {
        $fields = $values = array();

        if ( empty($post) ) {
        	return false;
        }

        foreach ($post as $campo => $value) {

            if ( !isset($this->_campos[$campo]) ) {
                continue;
            }

            $campo_tabela = $this->_campos[$campo];

            if ( $campo == $this->autoincrement ) {
                continue;
            }

            $fields[] = $campo_tabela;
            $values[] = $value;
        }

        $query = "INSERT IGNORE INTO {$this->table_name}";
        $query .= " (" . implode(", ", $fields) . ")";
        $query .= " VALUES ";
        $query .= " ('".implode("', '", $values)."')";

        $this->dao->query($query);

        $result = ( $this->dao->num_rows > 0 );

        return $result;
    }

    /**
     * Faz o update na talela
     *
     * @param unknown $put
     * @param unknown $codigo
     * @return boolean|boolean|unknown
     */
    public function update($put, $codigo)
    {
        $res = $this->getData($codigo);
        $total = $this->dao->num_rows($res);

        if ( $total < 1 ) {
            return false;
        }

        $fields = array();
        $values = array();
        foreach ($put as $campo => $value) {

            if ( !isset($this->_campos[$campo]) ) {
                continue;
            }

            $campo_tabela = $this->_campos[$campo];

            if ( $campo == $this->autoincrement ) {
                continue;
            }

            $fields[] = "{$campo_tabela} = '{$value}'";

        }

        if ( empty($fields) ) {
            return false;
        }

        $query  = "UPDATE {$this->table_name} SET ";
        $query .= implode(", ", $fields);
        $query .= " WHERE {$this->primary_key} = '{$codigo}'";

        return $this->dao->query($query);
    }

    /**
     * Faz o delete da tabela
     * @param unknown $codigo
     * @return boolean|unknown
     */
    public function delete($codigo)
    {
        $query  = "DELETE FROM {$this->table_name}";
        $query .= " WHERE {$this->primary_key} = '{$codigo}'";
        return $this->dao->query($query);
    }


    /**
     * Retorna para a query os filtros passados
     */
    public function setQueryFiltros()
    {
        $query = "";

        if ( empty($this->filtros) ) {
            return false;
        }

        foreach ($this->filtros as $sub_querys) {
            $query .= " AND {$sub_querys}";
        }

        return $query;
    }

    /**
     * Retorna para a query a paginação
     *
     * @param unknown $query
     * @param unknown $pagina
     * @param unknown $limite
     * @return string
     */
    public function setLimit($query, $pagina, $limite)
    {
        $res = $this->dao->query($query);
        $this->dao->total_itens = $this->dao->num_rows();

        // complementa a query com o limite
        $offset = ( $pagina - 1 ) * $limite;
        $query_limite = " LIMIT {$limite} OFFSET {$offset}";

        return $query_limite;
    }

}
