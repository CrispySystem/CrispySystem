<?php

/**
 * @param array $params
 * @param Smarty_Internal_Template $template
 * @return bool|string String like /resource.php?type=TYPE&file=FILE&options=OPTIONS
 */
function smarty_function_image(array $params, Smarty_Internal_Template $template)
{
    if (!isset($params['file'])) {
        return false;
    }

    $query = 'type=img';

    if (isset($params['module'])) {
        $customDir = 'resources/modules/' . $params['module'] . '/' . (BACKEND ? 'backend' : 'frontend') . '/img/';
        $defaultDir = 'modules/' . $params['module'] . '/resources/' . (BACKEND ? 'backend' : 'frontend') . '/img/';
    } else {
        $customDir = 'resources/img/';
        $defaultDir = $customDir;
    }

    if (file_exists(ROOT_DIR . $customDir . $params['file'])) {
        $query .= '&file=' . $customDir . $params['file'];
    } elseif (file_exists(ROOT_DIR . $defaultDir . $params['file'])) {
        $query .= '&file=' . $defaultDir . $params['file'];
    } else {
        return false;
    }

    return '/resource.php?q=' . encrypt($query);
}