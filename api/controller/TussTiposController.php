<?php

namespace controller;

use model\TussTiposModel;

class TussTiposController extends Controller
{
    function __construct()
    {
        $this->model = new TussTiposModel();
        $this->set_methods_permitted(array('get'));
    }
}
