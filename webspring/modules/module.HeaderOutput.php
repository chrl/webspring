<?php

    /**
     * HeaderOutputModule
     * 
     * @package WebSpring
     * @author charlie@chrl.ru
     * @copyright admin
     * @version 2011
     * @access public
     */
    class HeaderOutputModule extends BaseModule implements ModuleInterface
    {
	
		/**
		 * HeaderOutputModule::intro()
		 *
		 * Output one or more headers from "result" headers variable
		 *
		 * @param mixed $core
		 * @return void
		 */
		public function intro(CoreInterface $core) {

			if ($headers = $core->getConfig()->get('settings.headers')) {

    				foreach($headers as $key=>$value)
					{
						header($key.': '.$value);
					}
			}
		}

		/**
		 * @param CoreInterface $core
		 * @return void
         */
		public function outro(CoreInterface $core) {

            $headers =  $core->getRequest()->get('put_header');
            if ($headers) {
                if (in_array('500',$headers)) {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    unset($headers[array_search('500',$headers)]);
                }

                foreach($headers as $key=>$value)
                {
                    header($key.': '.$value);
                }

            }

            if ($redirect = $core->getRequest()->get('redirect')) {
				$core->getLogger()->log('Redirecting to: '.$redirect);
				header('Location: '.$redirect);
			}

		}

    }