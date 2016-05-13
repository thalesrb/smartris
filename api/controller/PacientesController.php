<?php

namespace controller;

use model\PacientesModel;

class PacientesController extends Controller
{
    function __construct()
    {
        $this->model = new PacientesModel();
        $this->set_methods_permitted(array('get', 'post', 'put', 'delete'));
    }
}
