<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Category;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TagsImport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TagController extends Controller
{
    private $tag;
    private $state;
    public function __construct(Tag $tag) {
        $this->tag = $tag;
        $this->state = ['Activate', 'Deactivate'];
    }

    public function index() {
        $data = $this->tag->all()->toArray();
        $filtered = $columns = [];
        $i=0;
        array_push($columns, 'Sl #');
        foreach($data as $num => $arr) {
            foreach($arr as $col => $value) {
                switch ($col) {
                    case 'id':
                        $filtered[$i][$col] = $value;
                        break;
                    case 'tag_status':
                        $filtered[$i]['status'] = $value == true ? 'Active' : 'Inactive';
                        if(!in_array('status', $columns)) array_push($columns, 'status');
                        break;
                    case 'tag_name':
                        $filtered[$i][$col] = $value;
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                    case 'created_at':
                        $filtered[$i][$col] = date('Y-m-d', strtotime($value));
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                    case 'category_id':
                        $filtered[$i]['category'] = strtoupper(Category::find($value)->category_name);
                        if(!in_array('category', $columns)) array_push($columns, 'category');
                        break;
                }
                
            }
            $i++;
        }
        array_push($columns, 'action');
        return view('tag.index', ['data' => $filtered, 'columns' => $columns]);
    }

   // public function tagged_news($tag_id, $type='news') {
      //  $tag = Tag::find($tag_id)->$type()->get();
 //   }

    public function state_change($tag_id, $current_state) {
        $tag = Tag::find($tag_id);
        $state = false;
        if($current_state == 'Inactive') $state = true;
        $tag->tag_status = $state;
        if($tag->save()) return redirect('/tag')->with('success_status', 'Tag State has been Changed Successfully');
        else return redirect('/tag')->with('fail_status', 'Tag State couldn\'t be Changed');
    }

    public function create() {
        $categories = Category::where('category_status', true)->pluck('category_name', 'id')->toArray();
        return view('tag.create', ['status' => $this->state, 'categories' => $categories]);
    }

    public function store(Request $request) {
        $data = $request->all();
        $filtered = [];
        foreach($data as $col => $val) {
            if($col == 'tag_status') $filtered[$col] = $val == 1 ? true : false;
            else $filtered[$col] = $val;
        }
        if(Tag::create($filtered) != null) return redirect('/tag')->with('success_status', 'Tag has been created successfully');
        else return redirect('/tag')->with('fail_status', 'Tag couldn\'t be created');
    }

    public function create_file() {
        return view('tag.file_upload');
    }

    public function upload_file(Request $request) {
        $file = '';
        if($request->hasFile('uploaded_file')) $file = $request->file('uploaded_file');
        else redirect('/tag/create_file')->with('fail_status', 'File not found');
        
        if($file->getClientOriginalExtension() == 'xlsx' && $file->getClientSize() <= 1000000) {
            Excel::import(new TagsImport, $file);
            if(Artisan::call('tag:attach')==0) return redirect('/tag')->with('success_status', 'Import is Complete');
        } else back()->with('fail_status', 'Please check file type and file size (max 1MB)');
       
    }

    public function edit($tag_id) {
        $tag = Tag::find($tag_id);
        return view('tag.edit', ['tag' => $tag->toArray()]);
    }

    public function update(Request $request) {
        $id = $request->input('tag_id');
        $tag = Tag::find($id);
        $tag_name = $request->input('tag_name');
        $tag->tag_name = $tag_name;
        if($tag->save()) return redirect('/tag')->with('success_status', 'Tag Number: '.$id.' has been updated successfully');
        else return back()->with('fail_status', 'Tag couldn\'t be updated');
    }

    public function destroy($tag_id) {
        $tag = Tag::find($tag_id);
        $prod_rem = $tag->products()->detach();
        $news_rem = $tag->news()->detach();
        $cat_rem = gettype($tag->category()->dissociate()) == 'object';
        if($cat_rem && $tag->delete()) return redirect('/tag')->with('success_status', 'Tag Number: '.$tag_id.' has been removed successfully');
        else return back()->with('fail_status', 'Tag couldn\'t be removed');
    }

    public function mass_delete(Request $request) {
        $ids = $request->input('tag_id');
        $flag = false;
        if(gettype($ids) == 'array' && !empty($ids)) {
            for ($i=0; $i < count($ids); $i++) { 
                $tag = Tag::find($ids[$i]);
                $tag->products()->detach();
                $tag->news()->detach();
                $cat_rem = gettype($tag->category()->dissociate()) == 'object';
                if($cat_rem && $tag->delete()) $flag = true;
                else $flag = false;
            }
        } else {
            return back()->with('fail_status', 'Please select tags');    
        }
        if($flag) return redirect('/tag')->with('success_status', 'Tags have been removed successfully');
        else return back()->with('fail_status', 'Tags couldn\'t be removed');
    }
}
