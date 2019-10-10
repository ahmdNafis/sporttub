<?php

namespace App\Http\Controllers;

use App\Accessibility;
use App\User;
use App\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function index() {
        $data = $this->user->all()->toArray();
        $cont_roles = Role::where('role_status', 1)->pluck('role_name', 'id')->toArray();
        $filtered = $columns = [];
        $i=0;
        foreach($data as $num => $arr) {
            foreach($arr as $col => $value) {
                switch ($col) {
                    case 'first_name':
                        $filtered[$i]['name'] = $value.' '.$data[$num]['last_name'];
                        if(!in_array('name', $columns)) array_push($columns, 'name');
                        break;
                    case 'id':
                        $filtered[$i][$col] = $value;
                        $filtered[$i]['roles'] = $this->user->find($value)->roles()->get()->count() == 0 ? 'None' : $this->user->find($value)->roles()->pluck('role_name', 'id')->toArray();
                        if(!in_array('roles', $columns)) array_push($columns, 'roles');
                        break;
                    case 'user_status':
                        $filtered[$i]['status'] = $value == 1 ? 'Active' : 'Inactive';
                        if(!in_array('status', $columns)) array_push($columns, 'status');
                        break;/*
                    case 'email':
                        $filtered[$i][$col] = $value;
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;*/
                }
                
            }
            $i++;
        }
        array_push($columns, 'Action');
        return view('user.index', ['data' => $filtered, 'columns' => $columns, 'cont_roles' => $cont_roles]);
    }

    public function state_change($user_id, $current_state) {
        $user = $this->user->find($user_id);
        $state = false;
        if($current_state == 'Inactive') $state = true;
        $user->user_status = $state;
        if($user->save()) return redirect('/user')->with('success_status', 'State has been changed successfully');
        else return redirect('/user')->with('fail_status', 'State couldn\'t be changed');
    }

    public function role_change(Request $request) {
        $role_id = $request->input('role_id');
        $activity = $request->input('role_activity');
        $user = $this->user->find($request->input('user_id'));
        $role = Role::find($role_id);
        $access = Accessibility::where('role_id', $role_id)->first();
        $user_activity = $activity == 'attach' ? $user->roles()->attach($role) : $user->roles()->detach($role);
        $access_activity = $activity == 'attach' ? $user->accesses()->attach($access) : $user->accesses()->detach($access);
        if(($user_activity == null && $access_activity == null) || ($user_activity && $access_activity)) return redirect('/user')->with('success_status', 'Role has been changed successfully');
        else return redirect('/user')->with('fail_status', 'Role couldn\'t be added');
    }

    public function edit($user_id) {
        $user = $this->user->find($user_id);
    }

    public function destroy($user_id) {
        $user = $this->user->find($user_id);
    }

}
