<?php

use Symfony\Component\Finder\Finder;
use CrispySystem\Application\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Require all the autoload :-)
 */
require __DIR__ . '/../bootstrap/autoload.php';

/**
 * Require all the routes from all modules
 */
$finder = (new Finder())
    ->files()
    ->name('/backend\.php|frontend\.php/')
    ->in(ROOT_DIR . 'modules');

/** @var SplFileInfo $file */
foreach ($finder as $file) {
    require $file->getPathname();
}

/**
 * Create application
 * @var Application $app
 */
$app = require __DIR__ . '/../bootstrap/app.php';

/** @var Response $response */
$response = $app->handle(Request::createFromGlobals());

$response->send();