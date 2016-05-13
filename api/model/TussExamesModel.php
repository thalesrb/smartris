<?php
namespace model;

class TussExamesModel extends Model
{
    function __construct()
    {
        $tabela     = "tuss_exames";
        $primarykey = array("id_tipo", "id");

        parent::__construct($tabela, $primarykey);

        $this->setFieldDB("id_tipo");
        $this->setFieldDB("id");
        $this->setFieldDB("termo");
    }
}