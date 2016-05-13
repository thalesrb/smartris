<?php
namespace model;

class PacientesModel extends Model
{
    /**
     * Define o nome da tabela, e os campos que seram retornados nas consultas
     */
    function __construct()
    {
        $tabela     = "pacientes";
        $primarykey = "id";

        // seta esses valores no "$this"
        parent::__construct($tabela, $primarykey);

        $this->autoincrement = "id";

        $this->setFieldDB("id", "int");
        $this->setFieldDB("name", "text");
        $this->setFieldDB("id_card_number");
        $this->setFieldDB("sex");
        $this->setFieldDB("birthdate");
        $this->setFieldDB("address");
        // incluir outros campos da tabela
    }

    /**
     * Busca um lote de registros na tabela
     * não usa o metodo da classe Model, pois é adicionado um filtro manualmente
     *
     * {@inheritDoc}
     * @see \model\Model::getList()
     */
    public function getList($limite = 15, $pagina = 1)
    {
        $query  = "SELECT *";
        $query .= " FROM {$this->table_name}";
        $query .= " WHERE 1";

        // só retorna pacientes que tenha carteirinha
        $query .= " AND id_card_number != ''";

        // seta os filtros padrões
        $query .= $this->setQueryFiltros();

        $query .= " ORDER BY id";

        // seta a paginacao
        $query .= $this->setLimit($query, $pagina, $limite);

        $res = $this->dao->query($query);
        return $res;
    }

}