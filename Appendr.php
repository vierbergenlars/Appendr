<?php

namespace vierbergenlars;

class Appendr
{
    const APPEND = 'APPEND';
    const PREPEND = 'PREPEND';
    const SET = 'SET';

    private $sources = array();
    private $separator = null;
    private $pattern = null;
    private $default_attach_order = self::APPEND;

    public function __construct($separator = null, $pattern = null, $default_attach_order = self::APPEND)
    {
        $this->setSeparator($separator);
        $this->setPattern($pattern);
        $this->setDefaultAttachOrder($default_attach_order);
    }

    public function __invoke($source = null, $attach_order = null) {
        if($attach_order === null) {
            $attach_order = $this->getDefaultAttachOrder();
        }
        if($source != '') {
            switch($attach_order) {
                case self::APPEND;
                    $this->append($source);
                    break;
                case self::PREPEND:
                    $this->prepend($source);
                    break;
                case self::SET:
                    $this->set($source);
                    break;
                default:
                    throw new \DomainException('$attach_order should be one of APPEND, PREPEND or SET. (got '.$attach_order.')');
            }
        }
        return $this;
    }

    public function append($source)
    {
        $this->sources[] = $source;
        return $this;
    }

    public function prepend($source)
    {
        array_unshift($this->sources, $source);
        return $this;
    }

    public function set($source)
    {
        $this->sources = array($source);
        return $this;
    }

    public function __toString() {
        $pattern = $this->pattern;
        if($pattern !== null) {
            $patternized = array_map(function($source) use($pattern){
                return sprintf($pattern, $source);
            }, $this->sources);
        } else {
            $patternized = $this->sources;
        }

        return implode($this->separator, $patternized);
    }

    public function setDefaultAttachOrder($attach_order)
    {
        if(!in_array($attach_order,
            array(
                self::APPEND,
                self::PREPEND,
                self::SET
            )
        )) {
            throw new \DomainException('$attach_order should be one of APPEND, PREPEND or SET. (got '.$attach_order.')');
        }
        $this->default_attach_order = $attach_order;
        return $this;
    }

    public function getDefaultAttachOrder()
    {
        return $this->default_attach_order;
    }

    public function setSeparator($separator)
    {
        $this->separator = $separator;
        return $this;
    }

    public function getSeparator()
    {
        return $this->separator;
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function getPattern()
    {
        return $this->pattern;
    }
}