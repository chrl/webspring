<?php
    
    require('../../webspring/autoload.php');
    
    $core = new WebCore();
    $core-> 
		setConfig(new Config('cms.php'))->
		setRouter(new UrlRouter())->
		setDebugLevel(true)->
		attach('Cache')->
		attach('TemplateOutput')->
		attach('Counter')->        
		process()->
		shutdown();