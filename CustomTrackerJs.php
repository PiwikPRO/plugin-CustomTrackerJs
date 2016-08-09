<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs;

use Piwik\Db;
use Piwik\Log;
use Piwik\Plugin;
use Piwik\Plugins\CustomTrackerJs\TrackingCode\Extension;
use Piwik\Plugins\CustomTrackerJs\TrackingCode\ExtensionCollection;

class CustomTrackerJs extends Plugin
{
    public function getListHooksRegistered()
    {
        return array(
            'CustomTrackerJs.getTrackerJsExtension'    => 'getTrackerJsAdditions',
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
     * @param ExtensionCollection $extensionCollection
     */
    public function getTrackerJsAdditions(ExtensionCollection $extensionCollection)
    {
        $addition = $this->loadAdditionFromSettings();

        if ($addition) {
            $extension = new Extension('customTrackerJs');
            $extension->setCode($addition);

            $extensionCollection->add($extension);
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

    /**
     * @return string
     */
    private function loadAdditionFromSettings()
    {
        if (Db::hasDatabaseObject()) {
            return (new Settings('CustomTrackerJs'))->code->getValue();
        }

        return '';
    }
}
