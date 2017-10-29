<?php

function smarty_function_url(array $params, Smarty_Internal_Template $template)
{
    if (isset($params['name'])) { // It's a route name
        $route = \CrispySystem\Router\Router::getRouteByName($params['name']);
        $path = $route->getPath();

        if (isset($params['parameters'])) {
            foreach ($params['parameters'] as $key => $value) {
                $path = str_ireplace('{' . $key . '}', $value, $path);
            }
        }

        return $path; // TODO-PR3: Add translations
    }
}