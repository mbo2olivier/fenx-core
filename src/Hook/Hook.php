<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Hook;

/**
 * class Hook.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Hook implements \Iterator {
    /**
     * @var array
     */
    protected $callbacks;
    /**
     * @var string 
     */
    protected $tag;
    /**
     * @var integer
     */
    protected $index;
    /**
     * @var integer
     */
    protected $iterations;

    public function __construct($tag) {
        $this->tag = $tag;
        $this->callbacks = [];
        //$this->rewind();
    }

    public function attach($callback, $priority = 0) {
        if(!preg_match("/^(.*)::(.*)$/",$callback)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid service callback',$callback));
        }
        $priority_existed = isset($this->callbacks[ $priority ]);

        $this->callbacks[$priority][] = $callback;

        if (! $priority_existed && count( $this->callbacks ) > 1 ) {
			krsort($this->callbacks, SORT_NUMERIC);
		}
    }

    public function detach($callback, $priority = 0) {
        $exists = isset($this->callbacks[$priority][$callback]);

        if($exists) {
            unset($this->callbacks[$priority][$callback]);
            if(!$this->callbacks[$priority]) {
                unset($this->callbacks[$priority]);
            }
        }

        return $exists;
    }

    public function hasAttachments() {
        return count($this->callbacks) > 0;
    }

    public function rewind() {
        $this->iterations = 0;
        $this->index = 0;
    }

    public function current () {
        $i = $this->callbacks[$this->iterations][$this->index];
        $i = preg_split("/::/",$i);
        return $i[1];
    }

    public function next () {
        if((count($this->callbacks[$this->iterations]) - 1) <= $this->index) {
            $this->index = 0;
            $this->iterations++;
        }else{
            $this->index++;
        }
    }

    public function key () {
        $i = $this->callbacks[$this->iterations][$this->index];
        $i = preg_split("/::/",$i);
        return $i[0];
    }

    public function valid () {
        return isset($this->callbacks[$this->iterations][$this->index]);
    }
}