<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type as Type;
use App\Trending as Trending;

class TypeController extends Controller
{
    private $state;
    public function __construct() {
        $this->state = ['Active', 'Inactive'];
    }

    public function index() {
        $types = Type::get()->toArray();
        $filtered = $columns = [];
        foreach($types as $key => $arr) {
            foreach($arr as $col => $val)
            switch($col) {
                case 'created_at':
                $filtered[$key][$col] = date('Y/m/d', strtotime($val));
                if(!in_array($col, $columns)) array_push($columns, $col);
                break;
                case 'type_status':
                $filtered[$key]['status'] = $val == 1 ? 'Active': 'Inactive';
                if(!in_array('status', $columns)) array_push($columns, 'status');
                break;
                case 'id':
                case 'type_name':
                $filtered[$key][$col] = $val;
                if(!in_array($col, $columns) && $col != 'id') array_push($columns, $col);
                break;
            }
        }
        array_push($columns, 'action');
        return view('type.index', ['data' => $filtered, 'columns' => $columns]);
    }

    
    public function state_change($type_id, $current_state) {
        $type = Type::find($type_id);
        $state = false;
        if($current_state == 'Inactive') $state = true;
        $type->type_status = $state;
        if($type->save()) return redirect('/type')->with('success_status', 'Status has been changed successfully');
        else return redirect('/type')->with('fail_status', 'Status couldn\'t be changed');
    }

    public function create() {
        return view('type.create', ['status' => $this->state]);
    }

    public function store(Request $request) {
        $data = $request->all();
        if(Type::create($data) != null) return redirect('/type')->with('success_status', 'Type has been Created Successfully.');
        else return redirect('/type/create')->with('fail_status', 'Type couldn\'t be created');
    }

    public function edit($type_id) {
        $type = Type::find($type_id)->toArray();
        return view('type.edit', ['properties' => $type]);
    }

    public function update(Request $request) {
        $id = $request->input('type_id');
        $type = Type::find($id);
        $update_data = $request->all();
        $ignore = ['type_id', '_token', '_method'];
        foreach($update_data as $col => $val) {
            if(!in_array($col, $ignore) && !empty($val)) $type->$col = $val;
        }
        if($type->save()) return redirect('/type')->with('success_status', 'Type has been Updated Successfully.');
        else return redirect('/type/edit')->with('fail_status', 'Type couldn\'t be updated');
    }

    public function destroy($type_id) {
        //destroy in categories table
        $type = Type::find($type_id);
        if($type->categories()->get()->count() > 0) {
            $type->categories()->each(function($category) {
                $category->delete();
            });
        } //else return redirect('/type')->with('fail_status', 'Related Categories couldn\'t be deleted');
        $trends = Trending::where('type_id', $type_id);
        if($trends->get()->count() > 0) {
            $trends->each(function($trend) {
                $trend->delete();
            });
        } //else return redirect('/type')->with('fail_status', 'Related Trend Collections couldn\'t be deleted');
        //destroy in trending collection
        if($type->delete()) return redirect('/type')->with('success_status', 'Type has been Deleted Successfully.');
        else return redirect('/type')->with('fail_status', 'Type couldn\'t be deleted');
    }
}
