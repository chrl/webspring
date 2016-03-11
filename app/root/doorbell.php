<?php
    ini_set('display_errors', 'on');
    require('./autoload.php');
    
    $core = new WebCore();
    $core-> 
		setConfig(new Config('web.php'))->
		setRouter(new UrlRouter())->
		setDebugLevel(false)->
		attach('Cache')->
		attach('HeaderOutput')->
		attach('TemplateOutput')->
		attach('Counter')->        
		process()->
		shutdown();