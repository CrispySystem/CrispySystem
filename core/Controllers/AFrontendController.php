<?php

namespace CrispySystem\Controllers;

use CrispySystem\View\SmartyView;

abstract class AFrontendController extends AController
{
    public function __construct(SmartyView $view)
    {
        parent::__construct($view);

        define('BACKEND', false);
    }
}