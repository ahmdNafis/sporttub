<?php 

namespace App\Policies;

use App\User;

class CheckControllerPermission {
    private $user;
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function authenticate(String $controller_name,String $permission_val) {
        $access = $this->user->accesses()->where('controller_name', $controller_name)->pluck('role_id')->toArray();
        $roles = $this->user->roles()->pluck('permission', 'role_id')->toArray();
        $flag = false;
        if(count($roles) != 0 && count($access) != 0) {
            $i=0;
            foreach($roles as $role_id => $perm) {
                if(in_array($role_id, $access)) {
                    if(in_array($permission_val, unserialize($perm))) $flag = true;
                }
            }
            return $flag;
        } else return false;
    }
}