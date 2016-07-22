<?php

/**
 * CheckLonelinessProcessor
 *
 * Processor for checking whether current PHP process is unique.
 * We can use it at the beginning of execution path to stop double starting or
 * to continue execution.
 */
class CheckLonelinessProcessor extends BaseProcessor implements ProcessorInterface
{
    public function run($data, CoreInterface $core)
    {
        /**
         * select all processes | find PHP processes | exclude grep processes | count lines
         * trouble: that "grep" identify itself by file name, but there can be another execution path
         * from that file started by cron. So search by first argument name (which identifies exec patch)
         */
        $c = trim(exec("ps auxww | grep '".$core->getRequest()->get('arg_1')."' | grep -v grep | wc -l"));
        //$proc = exec("ps auxww | grep '".$core->getRequest()->get('arg_1')."' | grep -v grep");
        //var_dump($core->getRequest()->get('arg_1'));
        if( $c > 2 ) {
            // there is anoother process with the same file name and argument
            // stop exec path
            //print $proc;
            return array('stop');
        } else {
            // i'm alone. The world is mine!
            //print $proc."\n";
            return array('continue');
        }
    }
}