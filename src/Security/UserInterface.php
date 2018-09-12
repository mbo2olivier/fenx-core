<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Security;

/**
 * class UserInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
interface UserInterface {

    public function getId();

    public function getUsername();

    public function getSalt();

    public function getPassword();

    public function isActive();

    public function getRole();

    public function getCreateAt();

    public function getPlainPassword();
}
