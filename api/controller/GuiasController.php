<?php

namespace controller;

use model\GuiasModel;

class GuiasController extends Controller
{
    function __construct()
    {
        $this->model = new GuiasModel();
        $this->set_methods_permitted(array('get', 'post', 'put'));
    }
}
