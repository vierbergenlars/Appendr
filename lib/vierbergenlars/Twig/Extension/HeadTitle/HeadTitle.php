<?php

namespace vierbergenlars\Twig\Extension\HeadTitle;

class HeadTitle
{
    const APPEND = 'APPEND';
    const PREPEND = 'PREPEND';
    const SET = 'SET';

    private $title = array();
    private $separator = '-';
    private $default_attach_order = self::APPEND;

    public function __invoke($title = null, $attach_order = null) {
        if($attach_order === null) {
            $attach_order = $this->getDefaultAttachOrder();
        }
        if($title != '') {
            switch($attach_order) {
                case self::APPEND;
                    $this->append($title);
                    break;
                case self::PREPEND:
                    $this->prepend($title);
                    break;
                case self::SET:
                    $this->set($title);
                    break;
                default:
                    throw new \DomainException('$attach_order should be one of APPEND, PREPEND or SET. (got '.$attach_order.')');
            }
        }
        return $this;
    }

    public function append($title)
    {
        $this->title[] = $title;
        return $this;
    }

    public function prepend($title)
    {
        array_unshift($this->title, $title);
        return $this;
    }

    public function set($title)
    {
        $this->title = array($title);
        return $this;
    }

    public function __toString() {
        return implode(' '.$this->separator.' ', $this->title);
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
}