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

    /**
     * Creates a new Appendr instance
     * @param string|callable|null $separator Separator between parts. If it's a callable, the function gets called with the whole array as only parameter.
     * @param string|callable|null $pattern Pattern to apply to each part individually. (If it's a string, {@link sprintf()} is used. If it's callable, the function will be passed to {@link array_map()})
     * @param string $default_attach_order The default attach order {@link Appendr::APPEND}, {@link Appendr::PREPEND} or {@link Appendr::SET}
     */
    public function __construct($separator = null, $pattern = null, $default_attach_order = self::APPEND)
    {
        $this->setSeparator($separator);
        $this->setPattern($pattern);
        $this->setDefaultAttachOrder($default_attach_order);
    }

    /**
     * An instance of appendr can be used as a function.
     * @param mixed $source Anything the pattern callback can handle (or a string if a pattern string is used). If this variable is empty, nothing will happen
     * @param string $attach_order The attach order {@link Appendr::APPEND}, {@link Appendr::PREPEND} or {@link Appendr::SET}
     * @return \vierbergenlars\Appendr
     * @throws \DomainException When $attach_order is not one of the acceptable ones
     */
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

    /**
     * Appends a source
     * @param mixed $source Anything the pattern callback can handle (or a string if a pattern string is used). If this variable is empty, nothing will happen
     * @return \vierbergenlars\Appendr
     */
    public function append($source)
    {
        $this->sources[] = $source;
        return $this;
    }

    /**
     * Prepends a source
     * @param mixed $source Anything the pattern callback can handle (or a string if a pattern string is used). If this variable is empty, nothing will happen
     * @return \vierbergenlars\Appendr
     */
    public function prepend($source)
    {
        array_unshift($this->sources, $source);
        return $this;
    }

    /**
     * Sets a source
     * @param mixed $source Anything the pattern callback can handle (or a string if a pattern string is used). If this variable is empty, nothing will happen
     * @return \vierbergenlars\Appendr
     */
    public function set($source)
    {
        $this->sources = array($source);
        return $this;
    }

    /**
     * Creates the string representation of the sources.
     * @return string
     */
    public function __toString() {
        $pattern = $this->pattern;
        if($pattern !== null) {
            if(is_callable($pattern)) {
                $patternized = array_map($pattern, $this->sources);
            } else {
                $patternized = array_map(function($source) use($pattern){
                    return sprintf($pattern, $source);
                }, $this->sources);
            }
        } else {
            $patternized = $this->sources;
        }
        return is_callable($this->separator)?call_user_func($this->separator, $patternized):implode($this->separator, $patternized);
    }

    /**
     * Sets the default attach order
     * @param string $attach_order The default attach order {@link Appendr::APPEND}, {@link Appendr::PREPEND} or {@link Appendr::SET}
     * @return \vierbergenlars\Appendr
     * @throws \DomainException
     */
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

    /**
     * Gets the default attach order
     * @return string
     */
    public function getDefaultAttachOrder()
    {
        return $this->default_attach_order;
    }

    /**
     * Sets the separator character(s), or callable
     * @param string|callable $separator If it's a callable, the function gets called with the whole array as only parameter.
     * @return \vierbergenlars\Appendr
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * Gets the separator character(s), or callable
     * @return string|callable|null
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Sets the pattern
     * @param string|callable|null $pattern Pattern to apply to each part individually. (If it's a string, {@link sprintf()} is used. If it's callable, the function will be passed to {@link array_map()})
     * @return \vierbergenlars\Appendr
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * Gets the pattern
     * @return string|callable|null
     */
    public function getPattern()
    {
        return $this->pattern;
    }
}