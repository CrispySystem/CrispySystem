<?php

function smarty_function_css(array $params, Smarty_Internal_Template $template)
{
    if (!isset($params['href'])) {
        return false;
    }

    return '<link type="text/css" rel="stylesheet" href="/resource.php?type=css&path=' . $params['href'] . '"/>';
}