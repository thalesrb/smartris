<?php

namespace controller;

use model\TussExamesModel;

class TussExamesController extends Controller
{
    function __construct()
    {
        $this->model = new TussExamesModel();
        $this->set_methods_permitted(array('get'));
    }
}
