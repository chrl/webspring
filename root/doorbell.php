<?php

    require('./autoload.php');
    
    $core = new WebCore();
    $core-> 
	    setConfig(new Config('web.php'))->
        setRouter(new UrlRouter())->
        setDebugLevel(true)->
	    attach('Counter')->
	    attach('Cache')->
        //attach('DatasourceManager')->
	    attach('TemplateOutput')->
        //attach('HeaderOutput')->
	    process()->
	    shutdown();