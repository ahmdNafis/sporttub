<?php

namespace App\Http\Controllers;

use App\Category;
use App\Trending as Trending;
use App\Type as Type;
use App\Providers\App\Events\CategoryExistence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use DateTime;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    private $category;
    private $state = [];
    private $columns;
    private $mongo;
    private $trend_attributes;
    private $interval;

    public function __construct(Category $cat) {
        $this->mongo = DB::connection('mongodb');
        $this->category = $cat;
        $this->state = ['Activate', 'Deactivate'];
        $this->columns = Schema::getColumnListing('categories');
        $this->trend_attributes = (new Trending())->getFillable();
        $this->interval = ['One Year', 'Two Years', 'Three Years', 'Four Years', 'Five Years']; //one year == every year
    }
    public function index()
    {
        $data = $this->category->all();
        $filtered = $columns = [];
        $i=0;
        foreach($data->toArray() as $num => $arr) {
            foreach($arr as $col => $value) {
                switch ($col) {
                    case 'id':
                        $filtered[$i][$col] = $value;
                        //if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                    case 'category_name':
                        $filtered[$i][$col] = $value;
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                    case 'category_status':
                        $filtered[$i][$col] = $value == 1 ? 'Active' : 'Inactive';
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                    case 'created_at':
                    case 'updated_at':
                        $filtered[$i][$col] = Date('d/m/y', strtotime($value));
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                }
                
            }
            $i++;
        }
        array_push($columns, 'Action');
        return view('category.index', ['data' => $filtered, 'columns' => $columns]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::where('type_status', true)->pluck('type_name', 'id');
        return view('category.create', ['status' => $this->state, 'types' => $types, 'intervals' => $this->interval]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $mng_data = [];
        $title = $request->input('category_name');
        $start_date = new DateTime(date($request->input('date_from')));
        $end_date = new DateTime(date($request->input('date_to')));
        $mng_data['duration'] = (date_diff($start_date, $end_date))->format('%a'); //in days
        $current_date = new DateTime(date('Y-m-d'));
        $date_diff = $start_date->diff($current_date);
        
        $weighting = 0;
        $total_weight = 4;
        if($date_diff->d == 0 && $date_diff->m == 0 && $date_diff->y == 0) return redirect('/category/create')->with('fail_status', 'Date Values Shouldn\'t be Equal');
        if(($date_diff->m <= 3 && $date_diff->m >= 0)) {
            //if greater than 2 days, than m+1
            if($date_diff->d > 2) $weighting = $total_weight-($date_diff->m+1);
            else $weighting = $total_weight-$date_diff->m;
        }
        $mng_data['weighting'] = $weighting;
        
        $i=$j=0;
        $newCategory = new Category();
        foreach($data as $key => $value) {
            if(in_array($key, $this->columns)) $newCategory->$key = $value;
        }

        foreach($data as $key => $value) {
            if(in_array($key, $this->trend_attributes)) $mng_data[$key] = $value;
        }

        if($newCategory->save()) {
            $mng_data['category_id'] = Category::where('category_name', $title)->first()->id;
            if(Trending::create($mng_data)) {
                event(new CategoryExistence($title)); 
                return redirect('/category')->with('success_status', 'Success! Category saved Successfully');
            } else return redirect('/category')->with('fail_status', 'Trend Collection Couldn\'t be saved');
        } else return redirect('/category')->with('fail_status', 'Category Couldn\'t be saved');
    }

    public function check_presence(String $title) {
        if($title != null) {
            $categoryInst = Category::where('category_name', $title);
            if($categoryInst->get()->toArray() != null) return 'Category :'.$title.' Already Exists';
            else return 'Activate';
        }
    }

    public function change_state($title, $current_state) {
        $state = true;
        if($current_state == 'Active') $state = false;
        if(Category::where('category_name', $title)->update(['category_status' => $state])) return redirect('/category')->with('success_status', 'Category Status Changed Successfully');
        else return redirect('/category')->with('fail_status', 'Category Status Couldn\'t be Changed Successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, String $category_name)
    {
        $data = $category->where('category_name', $category_name);
        if($data->count() == 0) return back()->with('fail_status', 'No Such Record exist');
        $properties = $data->get(array_chunk($this->columns, count($this->columns)-count(array_slice($this->columns,-2)))[0])->toArray()[0];
        return view('category.edit', ['properties' => $properties]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $updateData = $request->input();
        $keys = [];
        foreach($updateData as $col => $val ) {
            if(!empty($val) && preg_match('/^_/', $col)==0) $keys[$col] = $val;
        }
        if($category->where('id', $updateData['_id'])->update($keys)) 
            return redirect('/category')->with('success_status', 'Category No.: '.$updateData['_id'].' Updated Successfully');
        else return back()->with('fail_status', 'Category No.: '.$updateData['_id'].'Couldn\'t be Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(String $category_name)
    {
        $id = Category::where('category_name', $category_name)->pluck('id')->toArray()[0];
        $catInstance =  Category::find($id);
        $deleteState = null;
        if($catInstance->news()->get()->count() == 0) $deleteState = $catInstance->delete();
        else {
            $catInstance->news()->each(function($news) {
                $news->delete();
            });
            $deleteState = $catInstance->delete();
        }
        $this->mongo->table('news_list')->where('category_id', $id)->delete();
        $this->mongo->table('news_link')->where('category_id', $id)->delete();
        if($deleteState) return redirect('/category')->with('success_status', 'Category: '.$category_name.' Has Been Deleted Successfully');
        else return redirect('/category')->with('fail_status', 'Category: '.$category_name.' Could not be Deleted');
    }
}
