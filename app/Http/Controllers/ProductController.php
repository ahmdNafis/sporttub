<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $product;
    private $state;
    private $category;
    private $tag;

    public function __construct(Product $prod, Category $cat, Tag $tag) {
        $this->product = $prod;
        $this->category = $cat;
        $this->tag = $tag;
        $this->state = ['Activate', 'Deactivate'];
    }

    public function index() {
        $data = $this->product->all();
        $filtered = $columns = [];
        $i=0;
        foreach($data->toArray() as $num => $arr) {
            foreach($arr as $col => $value) {
                switch ($col) {
                    case 'image_link':
                        $filtered[$i]['image_link'] = $value != null ? $value : 'None';
                        if(!in_array('image', $columns)) array_push($columns, 'image');
                        break;
                    case 'category_id':
                        $filtered[$i]['category'] = strtoupper(Category::find($value)->category_name);
                        if(!in_array('categories', $columns)) array_push($columns, 'categories');
                        break;
                    case 'id':
                        $filtered[$i][$col] = $value;
                        break;
                    case 'product_status':
                        $filtered[$i]['status'] = $value == 1 ? 'Active' : 'Inactive';
                        if(!in_array('status', $columns)) array_push($columns, 'status');
                        break;
                    case 'product_name':
                        $filtered[$i][$col] = $value;
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                }
                
            }
            $filtered[$i]['tags'] = count($data[$num]->tags()->get()->toArray());
            if(!in_array('tags', $columns)) array_push($columns, 'tags');
            $i++;
        }
        array_push($columns, 'Action');
        return view('product.index', ['data' => $filtered, 'columns' => $columns]);
    }

    public function state_change($product_id, $current_state) {
        $product = $this->product->find($product_id);
        $state = false;
        if($current_state == 'Inactive') $state = true;
        $product->product_status = $state;
        if($product->save()) return redirect('/product')->with('success_status', 'State has been changed successfully');
        else return redirect('/product')->with('fail_status', 'State couldn\'t be changed');
    }

    public function create() {
        $category = $this->category->where('category_status', 1)->pluck('category_name', 'id')->toArray();        
        return view('product.create', ['status' => $this->state, 'categories' => $category]);
    }

    public function store(Request $request) {
        $data = $request->all();
        $filtered = [];
        $slug = $request->input('slug');
        foreach($data as $key => $val) {
            if($key == 'product_image') {
                if($request->hasFile($key)) {
                    $file = $request->file($key);
                    $ext = $file->getClientOriginalExtension();
                    if($file->getSize() > 500000) return back()->with('fail_status', 'File is too big (must be less than 500kb)');
                    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') $filtered['image_link'] = Storage::disk('public')->putFileAs('product_images', $file, $slug.'.'.$ext);
                    else return back()->with('fail_status', ($ext=='jpg'?'true':'false').' Please choose a JPEG/JPG or PNG File');
                }
            } else {
                $filtered[$key] = $val;
                if($key == 'slug') $filtered[$key] = $val;
            }
        }
        
        if(Product::create($filtered) != null) return redirect('/product')->with('success_status', 'Product has been saved successfully');
        else return redirect('/product')->with('fail_status', 'Product couldn\'t be saved');
    }

    public function attach_tag($product_id) {
        $tags = $this->category->find($this->product->find($product_id)->category()->pluck('id')->toArray()[0])->tags()->where('tag_status', 1)->pluck('tag_name', 'id');
        return view('product.attach_tag', ['product_id' => $product_id,  'tags' => $tags]);
    }

    public function tag_store(Request $request) {
        $product = $this->product->find($request->input('product_id'));
        $tags = $request->input('tag_id');
        for ($i=0; $i < count($tags); $i++) { 
            if($product->tags()->attach($this->tag->find($tags[$i])) != null) return back()->with('fail_status', 'Tag id: '.$tags[$i].' couldn\'t be attached');
        }
        return redirect('/product')->with('success_status', count($tags).' tags have been attached successfully');
    }

    public function tag_remove($product_id) {
        $tags = $this->product->find($product_id)->tags()->pluck('tag_name', 'id');
        return view('product.detach_tag', ['tags' => $tags, 'product_id' => $product_id]);
    }

    public function tag_destroy(Request $request) {
        $id = $request->input('product_id');
        $product = $this->product->find($id);
        $tag_ids = $request->input('tag_id');
        if($product->tags()->detach($tag_ids)) return redirect('/product')->with('success_status', count($tag_ids).' tags have been removed from Product Number: '.$id);
        else return back()->with('fails_status', 'Tags for Product Number: '.$id.' couldn\'t be removed');
    }

    public function edit($product_id) {
        $product = $this->product->find($product_id)->toArray();
        $category = $this->category->where('category_status', 1)->pluck('category_name', 'id')->toArray();
        return view('product.edit', ['product' => $product, 'categories' => $category]);
    }

    public function update(Request $request) {
        $id = $request->input('_id');
        $product = $this->product->find($id);
        $data = $request->all();
        foreach($data as $col => $val) {
            if(($col != '_id' && $col != '_method' && $col != '_token') && !empty($val)) $product->$col = $val;
        }
        if($product->save()) return redirect('/product')->with('success_status', 'Product Number: '.$id.' has been updated');
        else return back()->with('fail_status', 'Please Check again. Product Number: '.$id.' Couldn\'t be updated');
    }

    public function destroy($product_id) {
        $product = $this->product->find($product_id);
        if($product->tags()->detach() && gettype($product->category()->dissociate()) == 'object' && $product->delete()) return back()->with('success_status', 'Product Number: '.$product_id.' has been removed successfully');
        else back()->with('fail_status', 'Product Number: '.$product_id.' couldn\'t be removed');
    }
}
