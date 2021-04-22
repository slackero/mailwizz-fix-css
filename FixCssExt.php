<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * Fix CSS Extension
 *
 * @package MailWizz EMA
 * @subpackage FixCssExt
 * @author Oliver Georgi <slackero@gmail.com>
 * @link http://github.com/slackero/
 * @copyright 2020 MailWizz EMA (http://www.mailwizz.com)
 * @license http://www.mailwizz.com/license/
 */

class FixCssExt extends ExtensionInit
{
    // name of the extension as shown in the backend panel
    public $name = 'Fix MailWizz CSS';

    // description of the extension as shown in backend panel
    public $description = 'Fixes CSS problems, use system font stack';

    // current version of this extension
    public $version = '1.2';

    // minimum app version
    public $minAppVersion = '1.3.6.2';

    // the author name
    public $author = 'Oliver Georgi';

    // author website
    public $website = 'http://github.com/slackero/';

    // contact email address
    public $email = 'slackero@gmail.com';

    /**
     * in which apps this extension is allowed to run
     * '*' means all apps
     * available apps: customer, backend, frontend, api, console
     * so you can use any variation,
     * like: array('backend', 'customer'); or array('frontend');
     */
    public $allowedApps = array('backend', 'customer');

    /**
     * The run method is the entry point of the extension.
     * This method is called by mailwizz at the right time to run the extension.
     */
    public function run()
    {
        Yii::app()->hooks->addFilter('register_styles', function ($styles) {
            $styles->add(array('src' => Yii::app()->apps->getBaseUrl('/frontend/assets/fixcss/css/fix.css')));
            return $styles;
        });
    }

    public function installCss() {
        // Copy extension assets
        $filesPathTo = Yii::getPathOfAlias('root.frontend.assets.fixcss');
        $filesPathFrom = Yii::getPathOfAlias('root.apps.extensions.fix-css.assets');
        $this->recurseCopy($filesPathFrom, $filesPathTo);
    }

    /**
     * Code to run before enabling the extension.
     * Make sure to call the parent implementation
     *
     * Please note that if you return false here
     * the extension will not be enabled.
     */
    public function beforeEnable()
    {
        $this->installCss();

        // call parent
        return parent::beforeEnable();
    }

    /**
     * Recursive copy
     *
     * @param $src
     * @param $dst
     */
    private function recurseCopy($src, $dst)
    {
        $dir = opendir($src);
        if (!file_exists($dst)) {
            @mkdir($dst, 0777, true);
        }
        while(false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * This is called when the extension is actually updated
     * So update logic goes here.
     */
    public function update()
    {
        $this->installCss();

        return parent::update();
    }
}
