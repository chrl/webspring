<?php

/**
 * Description of ConfigInjector
 *
 * @author kholodilin
 */
class ConfigInjector extends Linkable implements InjectorInterface {
	//put your code here
	protected $classes = array();
	
	public function __construct() {

	}
	
	public function getStandingFor($name)
	{
		foreach($this->classes as $key=>$class) {
			if(false!==strpos(get_class($class),$name)) {
				return $key;
			}
		}
		return false;
	}

	public function getClass($name)
	{
		if (isset($this->classes[$name])) {
			return $this->classes[$name];
		}
		
		$roles = $this->core->getConfig()->get('roles');
		if (isset($roles[$name])) {
			
			
			$this->classes[$name] = new $roles[$name]();
			
			if ($this->classes[$name] instanceof LinkableInterface) {
				$this->classes[$name]->linkCore($this->core);
			}
            if (method_exists($this->classes[$name], 'setup')) {
                $this->classes[$name]->setup();
            }
			
			return $this->classes[$name];
		} else {
			
			
			if (class_exists($name)) {
				
				$this->classes[$name] = new $name();

				if ($this->classes[$name] instanceof LinkableInterface) {
					$this->classes[$name]->linkCore($this->core);
				}
				if (method_exists($this->classes[$name], 'setup')) {
                    $this->classes[$name]->setup();
                }

				return $this->classes[$name];
				
			}
		}
		
		throw new Exception('Role for class '.$name.' not found');
	}
}
