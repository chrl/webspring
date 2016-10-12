<?php

    class Validator extends Linkable implements LinkableInterface
    {
        protected $form = array();
        public function __construct($form)
        {
            $form = str_ireplace('form', '', $form);
            $this->form = require_once(getcwd().'/../forms/form.'.ucfirst($form).'.php');
        }
        
        public function check($data)
        {
            if ($this->form['onCheck']) {
                return $this->form['onCheck']($this->core, $data);
            }
            return true;
        }
        
        
        
        
        
    }