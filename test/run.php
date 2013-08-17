<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../Appendr.php';
require __DIR__.'/../vendor/vierbergenlars/simpletest/autorun.php';

use vierbergenlars\Appendr;

class AppendrTest extends UnitTestCase
{
    function testSeparator()
    {
        $appendr = new Appendr();
        $appendr->setSeparator(' ** ');
        $appendr->append('a');
        $appendr->append('b');
        $this->assertEqual($appendr->__toString(), 'a ** b');
    }

    function testDefaultOrder()
    {
        $appendr = new Appendr();
        $appendr->setDefaultAttachOrder(Appendr::PREPEND);
        $appendr('2');
        $appendr('1');
        $appendr->setDefaultAttachOrder(Appendr::APPEND);
        $appendr('3');
        $appendr('4');
        $appendr->setSeparator(' < ');
        $this->assertEqual($appendr->__toString(), '1 < 2 < 3 < 4');
    }

    function testPattern()
    {
        $appendr = new Appendr();
        $appendr->setPattern('<script src="%s"></script>');
        $appendr->append('jquery.min.js');
        $this->assertEqual($appendr->__toString(), '<script src="jquery.min.js"></script>');
        $appendr->append('jquery.dropdown.js');
        $this->assertEqual($appendr->__toString(), '<script src="jquery.min.js"></script><script src="jquery.dropdown.js"></script>');
    }

    function testCallablePattern()
    {
        $appendr = new Appendr();
        $appendr->setPattern(function($source) {
            return '<script src="'.$source.'"></script>';
        });
        $appendr->append('jquery.min.js');
        $this->assertEqual($appendr->__toString(), '<script src="jquery.min.js"></script>');
        $appendr->append('jquery.dropdown.js');
        $this->assertEqual($appendr->__toString(), '<script src="jquery.min.js"></script><script src="jquery.dropdown.js"></script>');
    }

    function testCallablePatternArray()
    {
        $appendr = new Appendr();
        $appendr->setPattern(function($source) {
            return $source[0].'+'.$source[1];
        });
        $appendr->append(array('a','b'));
        $this->assertEqual($appendr->__toString(), 'a+b');
        $appendr->append(array('c','d'));
        $this->assertEqual($appendr->__toString(), 'a+bc+d');
    }

    function testConstructorArgs()
    {
        $appendr = new Appendr(' - ', '**%s**', Appendr::PREPEND);
        $this->assertEqual($appendr->getDefaultAttachOrder(), Appendr::PREPEND);
        $this->assertEqual($appendr->getPattern(), '**%s**');
        $this->assertEqual($appendr->getSeparator(), ' - ');
    }

    function testChainability()
    {
        $appendr = new Appendr;
        $appendr->append('1')
                ->prepend('2')
                ->set('a')
                ->setDefaultAttachOrder(Appendr::APPEND)
                ->setPattern('%s')
                ->setSeparator('-')
                ->append('8');
        $appendr('5')->setSeparator('-');
    }

    function testTwigIntegration()
    {
        $loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
        $twig = new Twig_Environment($loader);
        $twig->addFunction(new \Twig_SimpleFunction('headTitle', new Appendr(' - ')));
        $this->assertEqual($twig->render('no_att_order/extension.twig'), "Prepend @ Ext @ Append @ Base
Prepend @ Ext @ Append @ Base @ Block");

        $loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
        $twig = new Twig_Environment($loader);
        $twig->addFunction(new \Twig_SimpleFunction('headTitle', new Appendr(' - ')));
        $this->assertEqual($twig->render('default_att_order/extension.twig'), "Prepend - Ext - Append - Base
Block - Prepend - Ext - Append - Base");
    }

}