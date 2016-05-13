<?php
namespace model;

class TussTiposModel extends Model
{
    function __construct()
    {
        $tabela     = "tuss_tipos";
        $primarykey = "id";

        parent::__construct($tabela, $primarykey);

        $this->setFieldDB("id");
        $this->setFieldDB("tipo");
    }
}