<?php

namespace CrispySystem\View;

use CrispySystem\Application\Application;
use CrispySystem\Helpers\Config;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class SmartyView implements IView
{
    protected $smarty;

    protected $template;

    // TODO-PR1: Rewrite smarty-extends to smarty-cripsy-extends, to allow extend with a module name instead of path

    public function __construct()
    {
        $this->smarty = new \Smarty();

        /**
         * Set Smarty template options
         */
        $this->smarty->setTemplateDir(ROOT_DIR);

        /**
         * Set Smarty compile options
         */
        $this->smarty->setCompileDir(ROOT_DIR . 'storage/crispysystem/smarty/compile');
        if (DTAP === 'development') {
            $this->smarty->setForceCompile(true); // Always re-compile on development environments
        }

        /**
         * Set Smarty caching options
         */
        $this->smarty->setCacheDir(ROOT_DIR . 'storage/crispysystem/smarty/cache');
        if (DTAP === 'development') {
            $this->smarty->setCaching(false);
        } else {
            $this->smarty->setCaching(true);
        }

        /**
         * Smarty load plugins
         */
        $finder = (new Finder())
            ->files()
            ->name('/.+\..+\.php/')
            ->in(__DIR__ . '/smarty-plugins');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $parts = explode('.', $file->getFilename());
            require_once $file->getFileInfo();
            $this->smarty->registerPlugin($parts[0], $parts[1], 'smarty_' . $parts[0] . '_' . $parts[1]);
        }

        /**
         * Smarty assign config
         */
        $this->smarty->assign('config', Config::get());
        $this->smarty->assign('system', [
            'version' => Application::VERSION,
        ]);
    }

    public function template(string $file, string $module): SmartyView
    {
        // First check for a custom template, then for the default
        $customDir = 'resources/modules/' . $module . '/' . (BACKEND ? 'backend' : 'frontend') . '/tpl/';
        $defaultDir = 'modules/' . $module . '/resources/' . (BACKEND ? 'backend' : 'frontend') . '/tpl/';

        if (file_exists(ROOT_DIR . $customDir . $file)) {
            $this->template = $customDir . $file;
        } elseif (file_exists(ROOT_DIR . $defaultDir . $file)) {
            $this->template = $defaultDir . $file;
        } else {
            showPlainError('Template: ' . $file . ' does not exist in the following directories:<br><br>' . $customDir . '<br>' . $defaultDir);
        }

        return $this;
    }

    public function with(array $data)
    {
        foreach ($data as $k => $v) {
            $this->smarty->assign($k, $v);
        }

        return $this;
    }

    public function display()
    {
        return  $this->smarty->fetch($this->template);
    }
}