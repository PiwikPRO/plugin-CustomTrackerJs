<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\CustomTrackerJs\Commands;

use Piwik\Plugin\ConsoleCommand;
use Piwik\Plugins\CustomTrackerJs\TrackerUpdater;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTracker extends ConsoleCommand
{
    protected function configure()
    {
        $this->setName('customtrackerjs:update');
        $this->setDescription('Update the Javascript Tracker with plugin additions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updater = new TrackerUpdater();
        $updater();

        $output->writeln('<info>The Javascript Tracker has been updated</info>');
    }
}
