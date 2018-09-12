<?php
/**
 * This file is part of the fenxweb/fenx
 * (c) 2018 Fenxweb
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fenxweb\Fenx\Security;

use Fenxweb\Fenx\Hook\Hooker;
/**
 * class RoleManager.
 * 
 * @author Olivier M. Mukadi <olivier.m@fenxweb.com>
 */
class RoleManager {
    /** @var array */
    protected $roles;

    /** @var Hooker */
    protected $hook;

    public function __construct(Hooker $hook, array $roles) {
        $this->roles = $roles;
        $this->hook = $hook;
    }

    public function check($urole, $role) {
        $grant = false;
        if( $urole === $role) {
            $grant = true;
        }else {
            $caps = $this->getCapabilities($urole);
            foreach($caps as $r) {
                if($r === $role) {
                    $grant = true;
                    break;
                }
            }
        }
        return $this->hook->dispatch('security.user_role',[$grant, $urole, $role]);
    }

    /**
     * @return array
     */
    public function getCapabilities($role) {
        return isset($this->roles[$role]) ? $this->roles[$role] : [];
    }

    public function getRoles() {
        return $this->roles;
    }
}