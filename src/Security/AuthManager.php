<?php
/**
 * This file is part of the fenxweb/fex
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Security;

use Fenxweb\Fenx\Application;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * class AuthManager.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class AuthManager {

    const LOGIN_BAD_CREDENTIALS = 0;
    const LOGIN_USER_NOT_FOUND = 1; 
    const LOGIN_SUCCESS = 2; 
    const SESSION_USER = "_user_connected";

    /**
     * @var Session
     */
    private $session;
    
    /** @var string */
    private $redirectTo;

    /** @var string */
    private $loginRoute;

    /** @var string|null */
    private $algo;

    /** @var UserProviderInterface */
    private $provider;

    /** @var RoleManager  */
    private $rm;

    public function __construct(Session $session, UserProviderInterface $provider, RoleManager $rm, array $config) {
        $this->session = $session;
        $this->provider = $provider;
        $this->rm = $rm;
        $this->redirectTo = (isset($config['redirect_to'])) ? $config['redirect_to'] : '/';
        $this->loginRoute = (isset($config['login_route'])) ? $config['login_route'] : 'login';
    }

    public function login($username, $pwd) {
        $user = $this->provider->findUser($username);
        if($user) {
            if(password_verify($pwd, $user->getPassword())) {
                $this->session->set(self::SESSION_USER, [
                    'id' => $user->getId(),
                    'username' => $username,
                    'role' => $user->getRole()
                ]);
                return self::LOGIN_SUCCESS;
            }else {
                return self::LOGIN_BAD_CREDENTIALS;
            }
        }else {
            return self::LOGIN_USER_NOT_FOUND;
        }
    }

    public function logout() {
        $this->session->remove(self::SESSION_USER);
    }

    public function check($role = null) {
        if(is_null($role)) {
            return $this->session->has(self::SESSION_USER);
        }else {
            if($user = $this->session->get(self::SESSION_USER)) {
                return $user ? $this->rm->check($user['role'], $role) : false;
            }
            return false;
        }
    }

    /**
     * Get the value of redirecTo
     */ 
    public function getRedirectTo()
    {
        return $this->redirectTo;
    }

    /**
     * Get the value of loginRoute
     */ 
    public function getLoginRoute()
    {
        return $this->loginRoute;
    }
} 