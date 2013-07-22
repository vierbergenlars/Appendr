# Appendr Twig extension

[![Build Status](https://travis-ci.org/vierbergenlars/twig-ext-appendr.png?branch=master)](https://travis-ci.org/vierbergenlars/twig-ext-appendr)

## Installation

`$ composer require vierbergenlars/twig-ext-appendr:*`

Register the extension with your Twig environment
```php
<?php

// ...
$twig = new Twig_Environment($loader);
$twig->addFunction(new \Twig_SimpleFunction('headTitle', new \vierbergenlars\Twig\Extension\Appendr));
// headTitle is just an example name, you can name it anything you want.
```

## Example

```twig
<!DOCTYPE html>
<html>
    <head>
        <title>{{ headTitle('Website Name') }}</title>
        {% do headTitle().setSeparator('~') %}
        {% do headTitle().setDefaultAttachOrder('APPEND') %}
    </head>
    <body>
        {# ... #}
    </body>
</html>
```

```twig
{% extends 'base.twig' %}
{% do headTitle('Page name', 'PREPEND') %}
```
## API

All methods are chainable (except the getters).

Assuming `headTitle` is an instance of appendr.

 * `headTitle().append('Title')` (Same as `headTitle('Title', 'APPEND')` )
 * `headTitle().prepend('Title')` (Same as `headTitle('Title', 'PREPEND')` )
 * `headTitle().set('New Title')` (Same as `headTitle('New Title', 'SET')` )
 * `headTitle().getSeparator()`
 * `headTitle().setSeparator('~')`
 * `headTitle().getDefaultAttachOrder()`
 * `headTitle().setDefaultAttachOrder('APPEND')` (Also `'PREPEND'` and `'SET'`)
 * `headTitle().getPattern()`
 * `headTitle().setPattern('_/°° %s °°\_')` (printf syntax, the pattern is used for each part that is added)

Default options can be passed when registering the Twig function.

```php
<?php

use vierbergenlars\Twig\Extension\Appendr;

// ...

$twig = new Twig_Environment($loader);

// Set default options in the constructor. (null skips an option)
$appendr = new Appendr('~', '_/°° %s °°\_', Appendr::APPEND);

// And register the Twig function
$twig->addFunction(new \Twig_SimpleFunction('fancyTitle', $appendr));

// Or set them on the instance later
$appendr->setSeparator('~');
$appendr->setPattern('_/°° %s °°\_');
$appendr->setDefaultAttachOrder(Appendr::APPEND);
```
