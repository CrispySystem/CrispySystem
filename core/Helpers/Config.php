<?php

namespace CrispySystem\Helpers;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Config
{
    /**
     * @var array Configuration
     */
    private static $config = [];

    /**
     * Creates a cache of the configuration files
     * @param string $dtap The current environment
     * @return void
     */
    public static function cache(string $dtap): void
    {
        $cache = [];

        /**
         * Gather all `DTAP`.php files in the config dir
         */
        $finder = (new Finder())
            ->files()
            ->name($dtap . '.php')
            ->in(ROOT_DIR . 'config/*');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $group = $file->getPathInfo()->getFilename();
            $config = require $file->getRealPath();

            $cache[$group] = $config;
        }

        if (!file_exists(ROOT_DIR . 'storage/crispysystem')) {
            showPlainError('No directory `storage/crispysystem` in project root');
        }

        $file = ROOT_DIR . 'storage/crispysystem/config.php';

        file_put_contents($file, serialize($cache));
    }

    /**
     * Loads the cached config into a static variable so it only has to be read from file once
     */
    public static function init()
    {
        $file = ROOT_DIR . 'storage/crispysystem/config.php';

        $config = unserialize(file_get_contents($file));

        static::$config = $config;
    }

    /**
     * Get a configuration value by key
     * @param null $key The key to search for, can be formatted like this: key.subkey.subsubkey
     * @return array|mixed|null Return the value of the configuration key, or null if it doesn't exist
     */
    public static function get($key = null)
    {
        if (is_null($key)) {
            return static::$config;
        }
        // Key is formatted like database.default.driver
        $config = static::$config;
        $key = explode('.', $key);
        foreach ($key as $k) {
            if (!isset($config[$k])) {
                return null;
            }
            $config = $config[$k];
        }
        return $config;
    }
}