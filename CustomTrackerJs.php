<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs;

use Piwik\Log;
use Piwik\Plugin;

class CustomTrackerJs extends Plugin
{
    public function getListHooksRegistered()
    {
        return array(
            'CustomTrackerJs.getTrackerJsAdditions'    => 'getTrackerJsAdditions',
            // Update the tracker when one of these events is raised
            'Settings.CustomTrackerJs.settingsUpdated' => 'updateTracker',
            'CoreUpdater.update.end'                   => 'updateTracker',
            'PluginManager.pluginDeactivated'          => 'updateTracker',
            'PluginManager.pluginActivated'            => 'updateTracker',
        );
    }

    /**
     * Add the custom Javascript that is configured in the admin panel to the JS Tracker.
     *
     * @param string &$code
     */
    public function getTrackerJsAdditions(&$code)
    {
        $settings = new Settings('CustomTrackerJs');

        $addition = $settings->code->getValue();

        if ($addition) {
            $code .= PHP_EOL . $addition;
        }
    }

    public function updateTracker()
    {
        try {
            $trackerUpdater = new TrackerUpdater();
            $trackerUpdater();
        } catch (\Exception $e) {
            Log::error('There was an error while updating the javascript tracker: ' . $e->getMessage());
        }
    }
}
