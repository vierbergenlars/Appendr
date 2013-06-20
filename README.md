# Twig extension: headTitle()

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

## Usage

Use `headTitle()` in your twig templates.

```
<!DOCTYPE html>
<html>
    <head>
        <title>{{ headTitle('Website Name') }}</title>
        {% do headTitle().setSeparator('~') %à
        {% do headTitle().setDefaultAttachOrder('APPEND') %}
    </head>
    <body>
        ...
    </body>
</html>
```

```
{% extends 'base.twig' %}
{% do headTitle('Page name', 'PREPEND') %}
{# Or set a complete new title #}
{% do headTitle('Page name', 'SET') %}
```
