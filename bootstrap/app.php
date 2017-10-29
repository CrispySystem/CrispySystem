<?php

use CrispySystem\Application\Application;

/**
 * Start a session
 */
session_start();

/**
 * Create a new application instance
 */
$app = new Application();

return $app;
