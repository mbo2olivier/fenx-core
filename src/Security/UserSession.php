<?php
/**
 * This file is part of the fenxweb/fenx
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Security;

use Symfony\Component\HttpFoundation\Session\Session;
/**
 * class UserSession.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class UserSession {

    /**
     * @var Session
     */
    private $session;
    /**
     * @var array
     */
    private $userData;

    public function __construct(Session $session) {
        $this->session = $session;
        $this->userData = null;
    }

    public function get($key) {
        if(!$this->userData)  {
            $this->userData = $this->session->get(AuthManager::SESSION_USER, []);
        }
        return isset($this->userData[$key])? $this->userData[$key] : '';
    }
}