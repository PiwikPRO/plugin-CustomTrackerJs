<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs;

use Piwik\Piwik;
use Piwik\Settings\Setting;
use Piwik\Settings\FieldConfig;

/**
 * Defines Settings for CustomTrackerJs.
 *
 * Usage like this:
 * $settings = new SystemSettings();
 * $settings->metric->getValue();
 * $settings->description->getValue();
 */
class SystemSettings extends \Piwik\Settings\Plugin\SystemSettings
{
    /** @var Setting */
    public $customCode;

    protected function init()
    {
        $this->customCode = $this->createAtCustomCodeSetting();
    }

    protected function createAtCustomCodeSetting()
    {
        return $this->makeSetting(
            'code',
            '',
            FieldConfig::TYPE_STRING,
            function (FieldConfig $field) {
                $field->title = Piwik::translate(
                    'CustomTrackerJs_Setting_title_code'
                );
                $field->uiControl = FieldConfig::UI_CONTROL_TEXTAREA;
                $field->inlineHelp = Piwik::translate(
                    'CustomTrackerJs_Setting_desc_code'
                );
                $field->introduction = Piwik::translate(
                    'CustomTrackerJs_Setting_introduction_code'
                );
            }
        );
    }
}
