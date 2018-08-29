<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Annotation;

/**
 * class Inject.
 * @Annotation
 * @Target({"METHOD","CLASS"})
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class Inject
{
    /**
     * @var string
     */
    public $service;
    /**
     * @string
     */
    public $var;
}