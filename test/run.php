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

    function testSeparatorCallable()
    {
        $appendr = new Appendr;
        $appendr->setSeparator(function($sources) {
            $result = '';
            for($i=0; $i<count($sources); $i++) {
                if($i%2 == 0)
                    $result.=$sources[$i];
            }
            return $result;
        });

        $appendr->append('a');
        $appendr->append('b');
        $appendr->append('c');
        $this->assertEqual($appendr->__toString(), 'ac');
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

    function testSeparatorAndPatternCallable()
    {
        $appendr = new Appendr();
        $appendr->setPattern(function($source) {
            if(isset($source['href'])) {
                return array(
                    'data'=>'<a href="'.$source['href'].'">'.$source['text'].'</a>',
                    'visible'=>$source['visible']
                );
            }
            return array(
                'data'=>$source['text'],
                'visible'=>true
            );
        });
        $appendr->setSeparator(function($sources) {
            $ret = '<ul class="breadcrumb">';
            for($i=0;$i<count($sources)-1;$i++)
            {
                if(!$sources[$i]['visible'])
                    continue;
                $ret.='<li>'.$sources[$i]['data'].' <span class="divider">/</span></li>';
            }
            $ret.='<li class="active">'.$sources[$i]['data'].'</li>';
            $ret.='</ul>';
            return $ret;
        });

        $appendr->append(array('href'=>'/', 'text'=>'Home', 'visible'=>true));
        $appendr->append(array('href'=>'/contact', 'text'=>'Contact', 'visible'=>false));
        $appendr->append(array('href'=>'/lib', 'text'=>'Library', 'visible'=>true));
        $appendr->append(array('text'=>'Data', 'visible'=>true));
        $this->assertEqual($appendr->__toString(), '<ul class="breadcrumb"><li><a href="/">Home</a> <span class="divider">/</span></li><li><a href="/lib">Library</a> <span class="divider">/</span></li><li class="active">Data</li></ul>');

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