<?php

namespace CrispySystem\Controllers;

use CrispySystem\View\SmartyView;

abstract class AController
{
    protected $view;

    public function __construct(SmartyView $view)
    {
        $this->view = $view;
    }
}