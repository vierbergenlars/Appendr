<?php

namespace vierbergenlars\Twig\Extension\HeadTitle;

class Extension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('headTitle', new HeadTitle())
        );
    }

    public function getName() {
        return 'vierbergenlars/twig-ext-head-title';
    }
}
