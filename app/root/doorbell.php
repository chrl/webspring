<?php
    
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