<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Form;

use Gregwar\Formidable\Form;
use Gregwar\Cache\Cache;

/**
 * class Formidable.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class FormBuilder {

    protected $cache;
    protected $templateDir;
    protected $cacheDir;

    public function __construct($templateDir, $cacheDir, $cache ) {
        $this->cacheDir = $cacheDir;
        $this->templateDir = $templateDir;
        $this->cache = $cache;
    }

    public function template($view) {
        return $this->cache ? new Form($this->templateDir."/".$view,null,$this->getCache()) : new Form($this->templateDir."/".$view);
    }

    public function form($html) {
        return $this->cache ? new Form($html,null,$this->getCache()) : new Form($html);
    }

    protected function getCache() {
        $cache = new Cache;
        $cache->setCacheDirectory($this->cacheDir.'/form');
        return $cache;
    }
}