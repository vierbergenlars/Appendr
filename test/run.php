<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../vendor/vierbergenlars/simpletest/autorun.php';


class TwigHeadTitleTest extends UnitTestCase
{
    private $twig;
    function setUp() {
        $loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
        $this->twig = new Twig_Environment($loader);
        $this->twig->addExtension(new vierbergenlars\Twig\Extension\HeadTitle\Extension);
    }

    function testBasic()
    {
        $this->assertEqual($this->twig->render('no_att_order/extension.twig'), "Prepend @ Ext @ Append @ Base
Prepend @ Ext @ Append @ Base @ Block");
    }

    function testDefaultOrder()
    {
        $this->assertEqual($this->twig->render('default_att_order/extension.twig'), "Prepend - Ext - Append - Base
Block - Prepend - Ext - Append - Base");
    }
}