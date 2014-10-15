<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs;

use Piwik\Settings\SystemSetting;

/**
 * Defines Settings for CustomTrackerJs.
 *
 * Usage like this:
 * $settings = new Settings('CustomTrackerJs');
 * $settings->code->getValue();
 */
class Settings extends \Piwik\Plugin\Settings
{
    /**
     * @var SystemSetting
     */
    public $code;

    protected function init()
    {
        $this->setIntroduction(
            'The CustomTrackerJs plugin let super users and plugins add custom content to the piwik.js tracker file.'
            . ' This plugin is brought to you by Piwik PRO, creators of the Piwik Cloud.'
        );

        $this->createJavascriptSetting();
    }

    private function createJavascriptSetting()
    {
        $this->code = new SystemSetting('code', 'Javascript code');
        $this->code->readableByCurrentUser = true;
        $this->code->uiControlType = static::CONTROL_TEXTAREA;
        $this->code->description = 'The Javascript code will be inserted at the end of the Piwik Javascript Tracker (piwik.js).';
        $this->code->defaultValue = '';

        $this->addSetting($this->code);
    }
}
