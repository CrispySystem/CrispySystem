<?php

use CrispySystem\Helpers\Config;

/**
 * Define the site root directory
 */
define('ROOT_DIR', str_ireplace('bootstrap', '', __DIR__)); // /web/html/sitename/somedirectory

/**
 * Require helpers
 */
require ROOT_DIR . 'core/helpers.php';

/**
 * Define the DTAP (Development, Testing, Acceptance, Production)  environment
 */
if (getenv('DTAP') === false) {
    showPlainError('Environment variable <b>"DTAP"</b> is not set');
}

$phases = [ // List of allowed DTAP phases
    'development',
    'testing',
    'acceptance',
    'production'
];

if (!in_array(getenv('DTAP'), $phases, true)) {
    showPlainError(
        'Environment variable <b>"DTAP"</b> has invalid value <i>"' . getenv('DTAP') . '"</i>
        <br><br>
        Allowed values are: <ul><li><i>' . implode('</i></li><li><i>', $phases) . '</ul>'
    );
}

define('DTAP', getenv('DTAP'));

/**
 * Set error display
 */
if (DTAP === 'development') { // Only show errors when on development server
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}

/**
 * Load Composer autoloader
 */
if (!file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    showPlainError(
        'Composer <i>autoload.php</i> not found
        <br><br>
        Did you forget to run <i>"composer install"</i>?'
    );
}

require ROOT_DIR . 'vendor/autoload.php';

/**
 * Initialize the config
 */
if (DTAP === 'development' || !file_exists(ROOT_DIR . 'storage/crispysystem/config.php')) { // Only cache config if on development environment, or if the cached config does not yet exist
    Config::cache(DTAP);
}

Config::init();