<?php
    session_start();
    require('../../webspring/autoload.php');
    
    $core = new WebCore();
    $core-> 
		setConfig(new Config($core->isAjax?'ajax.php':'cms.php'))->
		setRouter($core->isAjax?new RequestRouter():new UrlRouter())->
		setDebugLevel(false)->
		attach('Cache')->
		attach($core->isAjax?'JsonOutput':'TemplateOutput')->
		process()->
		shutdown();