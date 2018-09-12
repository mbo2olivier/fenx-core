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
 * class UserProviderInterface.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
interface UserProviderInterface {

    public function findUser($username);

    public function createUser($user);

    public function newObject();
}
