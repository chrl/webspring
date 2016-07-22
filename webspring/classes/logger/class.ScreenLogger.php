<?php

/**
 * ScreenLogger
 *
 * @package
 * @author
 * @copyright admin
 * @version 2011
 * @access public
 */
class ScreenLogger extends Logger implements LoggerInterface
{

    private $textLevels  = array(
        Logger::CRITICAL=>'[CRIT]',
        Logger::WARNING=>'[WARN]',
        Logger::NOTICE=>'[INFO]'
    );

    /**
     * ScreenLogger::log()
     *
     * @param mixed $message
     * @param string $calledClass
     * @return
     */
    public function log($message,$calledClass = 'default',$level = 1)
    {

        if (!$this->log) return $this;

        $bt = debug_backtrace();
        preg_match('/([^\.^\/]+)\.php$/',$bt[0]['file'],$matches);
        $calledClass = $matches[1];

        if ($calledClass == 'ScreenLogger') $calledClass = $matches[2];

        if ($calledClass == 'Logger') {
            preg_match('/([^\.^\/]+)\.php$/',$bt[1]['file'],$matches);
            $calledClass = $matches[1];
        }

        // particular log levels

        $levels = $this->core->getConfig()->get('settings.loglevels');

        if (isset($levels[$calledClass])) {

            if ($level > $levels[$calledClass]) return $this;
        }

        // transpone name to DI name

        $called = $this->core->getInjector()->getStandingFor($calledClass);
        if ($called) $calledClass = $called;

        // publish message


        if ($level > $this->outputLevel) return $this;

        $message = $this->textLevels[$level].'-['.date('d.m.Y H:i:s.').substr(microtime(),2,8).'] -> '.$calledClass.' -> '.$message."\n";

        echo $message;
    }
}