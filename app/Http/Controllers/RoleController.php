<?php

namespace App\Http\Controllers;

use App\Role as Role;
use App\Accessibility;
use Illuminate\Http\Request;
//use Illuminate\Routing\RouteCollection\Route;
use Illuminate\Support\Facades\Route as Route;

class RoleController extends Controller
{
    private $role;
    private $permissions;
    private $state;
    private $controllers = [];

    public function __construct(Role $role) {
        $this->role = $role;
        $this->permissions = ['Read', 'Write', 'Update', 'Delete'];
        $this->state = ['Activate', 'Deactivate'];
        $i = 0;
        $ignore_arr = ['Login', 'ResetPassword', 'ForgotPassword', 'Register', 'Broadcast', 'Dasboard'];
        foreach(Route::getRoutes()->getRoutes() as $route) {
            $actions = $route->getAction();
            if(array_key_exists('controller', $actions)) {
                $cont_arr = explode('\\', $actions['controller']);
                $cont_name = explode('Controller', substr($cont_arr[count($cont_arr)-1], 0, strpos($cont_arr[count($cont_arr)-1], '@')))[0];
                if(!in_array($cont_name, $this->controllers) && !in_array($cont_name, $ignore_arr)) $this->controllers[$i++] = $cont_name;
            }
        }
    }

    public function index() {
        $roles = Role::all()->toArray();
        $filtered = $columns = [];
        $i=0;
        foreach($roles as $num => $arr) {
            foreach($arr as $col => $value) {
                switch ($col) {
                    case 'id':
                        $filtered[$i][$col] = $value;
                        $filtered[$i]['accesses'] = implode(' | ', Accessibility::where('role_id', $value)->pluck('controller_name')->toArray());
                        if(!in_array('accesses', $columns)) array_push($columns, 'accesses');
                        break;
                    case 'role_name':
                        $filtered[$i][$col] = $value;
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                    case 'role_status':
                        $filtered[$i][$col] = $value == 1 ? 'Active' : 'Inactive';
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                    case 'permission':
                        $filtered[$i][$col] = implode(' | ', unserialize($value));
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                    case 'created_at':
                        $filtered[$i][$col] = Date('H:i:s', strtotime($value));
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                }
                
            }
            $i++;
        }
        array_push($columns, 'Action');
        return view('role.index', ['data' => $filtered, 'columns' => $columns]);
    }

    public function state_change($role_id, $current_state) {
        $role = Role::find($role_id);
        $state = false;
        if($current_state == 'Inactive') $state = true;
        $role->role_status = $state;
        if($role->save()) return redirect('/role')->with('success_status', 'Role State has been Changed Successfully');
        else return redirect('/role')->with('fail_status', 'Role State couldn\'t be Changed');
    }

    public function create() {
        return view('role.create', ['permissions' => $this->permissions, 'status' => $this->state, 'controllers' => $this->controllers]);
    }

    public function store(Request $request) {
        $data = $request->all();
        $controller_name = '';
        $filtered = [];
        foreach($data as $key => $val) {
            if($key != 'accessibility') { 
                if(gettype($val) != 'array') $filtered[$key] = $val;
                else $filtered[$key] = serialize($val);
            } else $controller_name = $val;
        }
        $role_new = Role::create($filtered);
        if($role_new != null) {
           
            $new_access = new Accessibility();
            $new_access->controller_name = $controller_name;  //$controllers[$i];
            $new_access->role_id = $role_new->id;
            if($new_access->save()==false) return redirect('/role')->with('fail_status', 'Controller: '.$controller_name.' Couln\'t be Saved');
           
            return redirect('/role')->with('success_status', 'Role has been created successfully');
        } else return redirect('/role')->with('fail_status', 'Role Couln\'t be Created');
    }

    public function edit($role_id) {
        $role = Role::find($role_id);
        $access = $role->access()->first();
        $existing_permission = unserialize($role['permission']);
        $alter_perm = '';
        for ($i=0; $i < count($existing_permission); $i++) { 
            $alter_perm .= $existing_permission[$i].' ';
        }
        return view('role.edit', ['access' => $access, 'role' => $role->toArray(), 'permissions' => $this->permissions, 'controllers' => $this->controllers, 'existing_perm' => $existing_permission, 'perm_str' => $alter_perm]);
    }

    public function update(Request $request) {
        $id = $request->input('role_id');
        $role = Role::find($id);
        $access = $role->access()->first();
        $data = $request->all();
        $ignore_arr = ['role_id', '_method', '_token'];
        foreach($data as $col => $val) {
            if(!in_array($col, $ignore_arr) && !empty($val)) {
                if($col=='accessibility') $access->controller_name = $val;
                elseif(gettype($val) == 'array') {
                    $permissions = unserialize($role['permission']);
                    for ($i=0; $i < count($val); $i++) { 
                        if(!in_array($val[$i], $permissions)) array_push($permissions, $val[$i]);
                    }
                    $role->$col = serialize($permissions);
                }
                else $role->$col = $val;
            }
        }

        if($role->save() && $access->save()) return redirect('/role')->with('success_status', 'Role Number: '.$id.' has been updated successfully');
        else return back()->with('fail_status', 'Role coudln\'t be updated');
    }

    public function permission_edit($role_id) {
        $role = Role::find($role_id);
        $existing_permission = unserialize($role['permission']);
        return view('role.permission_edit', ['permissions' => $existing_permission, 'role_id' => $role_id]);
    }

    public function permission_update(Request $request) {
        $id = $request->input('role_id');
        $role = Role::find($id);
        $existing_permission = unserialize($role['permission']);
        $permissions = $request->input('permission');
        if(gettype($permissions) == 'array' && !empty($permissions)) {
            for ($i=0; $i < count($permissions); $i++) { 
                $ind = array_search($permissions[$i], $existing_permission);
                array_splice($existing_permission, $ind, 1);
            }
        }
        $role->permission = serialize($existing_permission);
        if($role->save()) return redirect('/role')->with('success_status', 'Permissions removed successfully');
        else return back()>with('fail_status', 'Permissions couldn\'t be removed');
    }

    public function destroy($role_id) {
        $role = Role::find($role_id);
        if($role->users()->detach() && $role->access()->delete() && $role->delete()) return redirect('/role')->with('success_status', 'Role Number: '.$role_id.' has been deleted successfully');
        else return back()->with('fail_status', 'Selected role coudln\'t be deleted');
    }
}
