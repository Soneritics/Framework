# Soneritics Framework - Version 1.0 #

[![Build Status](https://api.travis-ci.org/Soneritics/Framework.svg?branch=master)](https://travis-ci.org/Soneritics/Framework)

by
* [@Soneritics](https://github.com/Soneritics) - Jordi Jolink


## Introduction ##
A brief summarization of what this framework does:

> The Soneritics framework is a framework that is object oriented and provides some useful functionalities for
> creating websites and web applications very easy.

## Minimum Requirements ##

- PHP 5.5+
- PDO driver for your respective database (atm only MySQL is supported)

## Supported Databases ##

- MySQL

# Features ##

- MVC structure
- Routing
- Database finder
- And more

### Installation ##

Setup is very easy and straight-forward. You can choose on of the following options:

1. Upload the framework to your include path.
2. Upload the framework to a private directory, on level higher than your website/web app's folder.


Then start the framework by including the bootstrap:

```php
require_once('Soneritics/Framework/Bootstrap.php');
new Framework\Bootstrap();
```

