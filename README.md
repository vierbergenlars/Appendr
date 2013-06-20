# Twig extension: `headTitle()`

[![Build Status](https://travis-ci.org/vierbergenlars/twig-ext-headtitle.png?branch=master)](https://travis-ci.org/vierbergenlars/twig-ext-headtitle)

## Installation

`$ composer require vierbergenlars/twig-ext-headtitle:*`

Register the extension with your Twig environment
```php
<?php

// ...
$twig = new Twig_Environment($loader);
$twig->addExtension(new \vierbergenlars\Twig\Extension\HeadTitle\Extension);
```

## Example

Use `headTitle()` in your twig templates.

```twig
<!DOCTYPE html>
<html>
    <head>
        <title>{{ headTitle('Website Name') }}</title>
        {% do headTitle().setSeparator('~') %à
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

 * `headTitle().append('Title')` (Same as `headTitle('Title', 'APPEND')` )
 * `headTitle().prepend('Title')` (Same as `headTitle('Title', 'PREPEND')` )
 * `headTitle().set('New Title')` (Same as `headTitle('Title', 'SET')` )
 * `headTitle().getSeparator()`
 * `headTitle().setSeparator('~')`
 * `headTitle().getDefaultAttachOrder()`
 * `headTitle().setDefaultAttachOrder('APPEND')` (Also `'PREPEND'` and `'SET'`)

