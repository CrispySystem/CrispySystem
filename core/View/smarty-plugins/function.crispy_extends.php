<?php

function smarty_function_crispy_extends(array $params, Smarty_Internal_Template $template)
{
    if (!isset($params['module'])) {
        return false;
    } else {
        $module = $params['module'];
    }

    if (!isset($params['file'])) {
        return false;
    } else {
        $file = $params['file'];
    }

    // First check for a custom template, then for the default
    $customDir = 'resources/modules/' . $module . '/' . (BACKEND ? 'backend' : 'frontend') . '/tpl/';
    $defaultDir = 'modules/' . $module . '/resources/' . (BACKEND ? 'backend' : 'frontend') . '/tpl/';

    if (file_exists(ROOT_DIR . $customDir . $file)) {
        $path = $customDir . $file;
    } elseif (file_exists(ROOT_DIR . $defaultDir . $file)) {
        $path = $defaultDir . $file;
    } else {
        showPlainError('Template: ' . $file . ' does not exist in the following directories:<br><br>' . $customDir . '<br>' . $defaultDir);
    }

    $template->assign('crispy_extends', $path);
}