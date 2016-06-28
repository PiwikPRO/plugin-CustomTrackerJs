<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link    http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs\tests\Integration;

use Piwik\EventDispatcher;
use Piwik\Plugins\CustomTrackerJs\TrackerUpdater;
use Piwik\Plugins\CustomTrackerJs\TrackingCode\ExtensionCollection;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;
use Piwik\Plugins\CustomTrackerJs\TrackingCode\Extension;

/**
 * @group CustomTrackerJs
 * @group Plugins
 */
class TrackerUpdaterTest extends IntegrationTestCase
{
    private $file = '/Fixture/tmp.js';

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var TrackerUpdater
     */
    private $updater;

    public function setUp()
    {
        parent::setUp();

        $this->file = __DIR__ . '/Fixture/tmp.js';
        $this->eventDispatcher = EventDispatcher::getInstance();

        // Prepare a file that we can manipulate for the tests
        if (file_exists($this->file)) {
            unlink($this->file);
        }
        copy(__DIR__ . '/Fixture/test.js', $this->file);
    }

    public function tearDown()
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    public function testUpdateTracker()
    {
        $this->iAddedExtensionWithTopAndBottom();
        $this->trackingCodeWasUpdated();
        $this->theTrackingCodeShouldHasNewLines();
        $this->theTrackingCodeWasUpdatedAgain();
        $this->theTrackingCodeWasNoChanged();
    }

    public function testMultipleEventListeners()
    {
        $this->iAddedExtensionWithTopAndBottom();
        $this->iAddedAnotherExtensionWithTopAndBottom();
        $this->iRemovedTheLastExtension();
        $this->trackingCodeWasUpdated();
        $this->theTrackingCodeShouldHasNewLines();
    }

    /**
     * @expectedException Piwik\Plugins\CustomTrackerJs\Exception\AccessDeniedException
     * @expectedExceptionMessage You have no access to piwik.js file
     */
    public function testUnknownFile()
    {
        $this->iAmTryingToUpdateNonExistentFile();
    }

    private function iAddedExtensionWithTopAndBottom()
    {
        $this->addExtensionWithNameAndCode("test", "foo");
    }

    private function iAddedAnotherExtensionWithTopAndBottom()
    {
        $this->addExtensionWithNameAndCode("test2", "bar");
    }

    private function iRemovedTheLastExtension()
    {
        $this->eventDispatcher->addObserver(
            'CustomTrackerJs.getTrackerJsExtension',
            function (ExtensionCollection $extensionCollection) {
                $extensionCollection->removeByName('test2');
            }
        );
    }

    /**
     * @param string $name
     * @param string $code
     */
    private function addExtensionWithNameAndCode($name, $code)
    {
        $this->eventDispatcher->addObserver(
            'CustomTrackerJs.getTrackerJsExtension',
            function (ExtensionCollection $extensionCollection) use ($name, $code) {
                $code = sprintf('var %s;', $code);

                $extension = new Extension($name);
                $extension->setCode($code);

                $extensionCollection->add($extension);

                $extension = new Extension($name, Extension::POSITION_TOP);
                $extension->setCode($code);

                $extensionCollection->add($extension);
            }
        );
    }

    private function trackingCodeWasUpdated()
    {
        $this->updater = new TrackerUpdater($this->file);
        $this->updater->__invoke();
    }

    private function theTrackingCodeShouldHasNewLines()
    {
        $expected = <<<JS

/* GENERATED: test */
var foo;
/* END GENERATED: test */

// Hello world
/* GENERATED: test */
var foo;
/* END GENERATED: test */


JS;

        $this->assertEquals($expected, file_get_contents($this->file));
    }

    private function theTrackingCodeWasUpdatedAgain()
    {
        $this->updater->__invoke();
    }

    private function theTrackingCodeWasNoChanged()
    {
        $this->theTrackingCodeShouldHasNewLines();
    }

    private function iAmTryingToUpdateNonExistentFile()
    {
        $updater = new TrackerUpdater('foobar');
        $updater();
    }
}
