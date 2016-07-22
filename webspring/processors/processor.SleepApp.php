<?php

/**
 * SleepAppProcessor
 *
 * Unified processor for pausing Application
 *
 * @access public
 */
class SleepAppProcessor extends BaseProcessor implements ProcessorInterface
{
    /**
     * @var int Default sleeping time if not specified
     */
    protected $defSleepSec = 5;
    /**
     * @var int Maximum seconds for Application sleep
     */
    protected $maxSleepSec = 60;

    public function run($data, CoreInterface $core)
    {
        $defSleepSec = (empty($data['sleepsec']['default']) || !is_numeric($data['sleepsec']['default'])) ?
                        $this->defSleepSec :
                        (int)$data['sleepsec']['default'];

        $maxSleepSec = (empty($data['sleepsec']['max']) || !is_numeric($data['sleepsec']['max'])) ?
                        $this->maxSleepSec :
                        (int)$data['sleepsec']['max'];

        if ($defSleepSec > $maxSleepSec) {
            $defSleepSec = $maxSleepSec;
        }

        if (!isset($data['sleepsec']['sec']) || !is_numeric($data['sleepsec']['sec'])) {
            $core->getLogger()->log('Going to sleep for '.$defSleepSec.' sec (by default)');
            sleep($defSleepSec);
        } else {

            $secForSleep = (int)$data['sleepsec']['sec'];

            switch (true) {
                case ($secForSleep <= 0):
                    $core->getLogger()->log('Sleeping time can not be negative or 0: '.$secForSleep.', so it will be '.$defSleepSec.' sec by default');
                    $secForSleep = $defSleepSec;
                    break;
                case ($secForSleep > $maxSleepSec):
                    $core->getLogger()->log('Sleeping time '.$secForSleep.' sec can not be more than '.$maxSleepSec.' sec, so it will be '.$maxSleepSec);
                    $secForSleep = $maxSleepSec;
                    break;
            }

            $core->getLogger()->log('Going to sleep for '.$secForSleep.' sec');
            sleep($secForSleep);
        }

        // After awakening
        return array('ok');
    }
}