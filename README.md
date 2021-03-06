WebSpring – easy configurable service action framework

[![Build Status](https://travis-ci.org/chrl/webspring.svg?branch=master)](https://travis-ci.org/chrl/webspring)

Usage cases: 
  * SMS-services: 
    *	Weather
    *	Horoscope
    *	Subscribe/unsubscribe service
  *	Api-calls:
    *	Example service api
    *	Translator api
  *	AJAX/RPC calls

Advantages:
  *	Service trees written once, and can be used with any output template/view
  *	Caching technology
  *	Advanced Logging
  *	Extra-easy configurable. 

Typical service development steps:
  *	Define paths for your service (“usage cases”) ~ 10min
  *	Decompose path task into small handlers ~ 20min
  *	Create necessary Processors, or use existing ones ~ 1hour
  *	Configure caching and logging ~5min
  *	Deploy ~1min

Resulting time for setting up average generic service: 1 hour 36 minutes ;)
