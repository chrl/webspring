<?php

/**
 * WebRequest
 *
 * @access public
 */
class WebRequest extends AbstractStorage implements RequestInterface, StorageInterface
{
	/**
	 * WebRequest::__construct()
	 *
	 * @return
	 */
	public function __construct()
	{
		$arguments = (isset($_SERVER['argv'])) ? ($_SERVER['argv']):array();

		$this->batchSet($_REQUEST);
		$this->batchSet($_GET);
		$this->batchSet($_POST);


		foreach ($arguments as $k=>$arg) {
			$this->set($arg,true);
			$this->set('arg_'.$k,$arg);
		}

        // read stdin

        $entityBody = file_get_contents('php://input');

        if ($entityBody = json_decode($entityBody,true)) {
            $this->batchSet($entityBody);
        }


		return $this;
	}
}