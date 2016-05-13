<?php
namespace model;

class GuiasModel extends Model
{
    function __construct()
    {
        $tabela     = "guias";
        $primarykey = array("paciente", "exame_tipo", "exame_id", "lote");

        parent::__construct($tabela, $primarykey);

        $this->setFieldDB("paciente");
        $this->setFieldDB("exame_tipo");
        $this->setFieldDB("exame_id");
        $this->setFieldDB("lote");
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
        $this->setFieldDB("name");
        $this->setFieldDB("id_card_number");
        $this->setFieldDB("termo");

        $query  = "SELECT *, name, id_card_number, termo";
        $query .= " FROM {$this->table_name} as guias";
        $query .= " INNER JOIN pacientes as paciente ON (paciente.id = paciente)";
        $query .= " INNER JOIN tuss_exames as exame ON (exame.id_tipo = exame_tipo AND exame.id = exame_id)";
        $query .= " WHERE 1";

        // seta os filtros padrões
        $query .= $this->setQueryFiltros();

        // seta a paginacao
        $query .= $this->setLimit($query, $pagina, $limite);

        $res = $this->dao->query($query);
        return $res;
    }

}
