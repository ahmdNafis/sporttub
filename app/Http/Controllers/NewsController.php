<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\News as News;
use App\Category as Category;
use App\Tag;
use App\Product;
use App\Comment;
use App\User;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $db_mongo;
    // author field missing
    public function __construct() {
        $this->db_mongo = DB::connection('mongodb');
        //https://www.espn.com/espn/rss/nfl/news
        //https://rss.cbssports.com/rss/headlines/nfl/
        //https://api.foxsports.com/v1/rss?partnerKey=zBaFxRyGKCfxBagJG9b8pqLyndmvo7UU&tag=nfl
        //https://sports.yahoo.com/nfl/rss/
    }

    public function index($category_id) {
        $data = null;
        //$data = News::get()->toArray();
        $news_list = Category::find($category_id)->news()->get();
        if($news_list->count()>0) $data = $news_list->toArray();
        else return redirect('/category')->with('fail_status', 'Articles for this Category doesn\'t exist');
        $filtered = [];
        $columns = [];
        foreach($data as $key => $arr) {
            foreach($arr as $col => $val) {
                switch($col) {
                    case 'object_id':
                    $filtered[$key]['oid'] = $val;
                    $filtered[$key]['title'] = $this->db_mongo->table('news_list')->where('_id', (new \MongoDB\BSON\ObjectId($val)))->get()->toArray()[0]['title'];
                    if(!in_array('title', $columns)) array_push($columns, 'title');
                    break;
                    case 'published_status':
                    $filtered[$key]['state'] = $val == 1 ? 'Published' : 'Unpublished';
                    if(!in_array('state', $columns)) array_push($columns, 'state');
                    break;
                    case 'videolink':
                    $filtered[$key][$col] = $val == null ? 'Not Present' : 'Present';
                    if(!in_array($col, $columns)) array_push($columns, $col);
                    break;
                    case 'published_date':
                    $filtered[$key][$col] = date('d/m/y', strtotime($val));
                    if(!in_array($col, $columns)) array_push($columns, $col);
                    break;

                }
            }
        }
        array($columns, 'action');
        return view('news.index', ['data' => $filtered, 'columns' => $columns]);
    }

    public function news_list() {
        $news = News::get(['object_id', 'id', 'category_id', 'published_status', 'published_date'])->toArray();
        $filtered = [];
        $columns = [];
        array_push($columns, 'Sl #');
        foreach($news as $key => $val) {
            foreach($val as $col => $content) {
                switch($col) {
                    case 'object_id':
                    $filtered[$key][$col] = $content;
                    $filtered[$key]['title'] = str_limit($this->db_mongo->table('news_list')->where('_id', (new \MongoDB\BSON\ObjectId($content)))->pluck('title')[0], 50);
                    if(!in_array('title', $columns)) array_push($columns, 'title');
                    break;
                    case 'id':
                    $news_data = News::find($content);
                    $filtered[$key]['tag'] = $news_data->tags()->get()->count() > 0 ? $news_data->tags()->get()->count() : 'None';
                    $filtered[$key]['comments'] = $news_data->comments()->where('flag', 'approved')->get()->count() > 0 ? $news_data->comments()->where('flag', 'approved')->get()->count() : 'None' ;
                    if(!in_array('tags', $columns)) array_push($columns, 'tags');
                    if(!in_array('comments', $columns)) array_push($columns, 'comments');
                    break;
                    case 'category_id':
                    $filtered[$key]['category'] = Category::find((int)$content)->category_name;
                    if(!in_array('category', $columns)) array_push($columns, 'category');
                    break;
                    case 'published_status':
                    $filtered[$key][$col] = $content == 1 ? 'Published' : 'Unpublished';
                    if(!in_array($col, $columns)) array_push($columns, $col);
                    break;
                    case 'published_date':
                    $filtered[$key][$col] = date('d/m/y', strtotime($content));
                    if(!in_array($col, $columns)) array_push($columns, $col);
                    break;
                    default:
                    $filtered[$key][$col] = $content;
                    if(!in_array($col, $columns)) array_push($columns, $col);
                    break;
                }
            }
        }
        array_push($columns, 'action');
        return view('news.list', ['data' => $filtered, 'columns' => $columns]);
    }

    public function news_specific($category_name) {
        $category = Category::where('category_name', $category_name)->first();
        $id = $category->id;
        $metadata = $this->db_mongo->table('news_list')->where('category_id', $id)->orderBy('published_date','desc')->get();
        $filtered = [];
        foreach($metadata->toArray() as $num => $arr) {
            foreach($arr as $col => $val) {
                $oid = '';
                if($col == '_id') $oid = (string)$val;
                //$pub_state = News::where('object_id', $oid)->first()->published_status;
                //if($pub_state != null && $pub_state == 1) {
                    switch ($col) {
                        case '_id':
                            $filtered[$num]['oid'] = $oid;
                            break;
                        case 'published_date':
                            $filtered[$num]['date'] = date('d/m/Y', strtotime($val));
                            break;
                        case 'title':
                        case 'thumbnail_url':
                        case 'description':
                            $filtered[$num][$col] = $val;
                            break;
                    }
                //}
            }
        }
        return view('news.news_specific', ['data' => $filtered]);
    }

    public function link_list() {
        $list = $this->db_mongo->table('news_links')->get()->toArray();
        $filtered=$columns=[];
        foreach($list as $num => $arr) {
            foreach($arr as $col => $val) {
                switch ($col) {
                    case '_token':
                        break;
                    case '_id':
                        $filtered[$num]['oid'] = (string)$val;
                        break;
                    case 'category_id':
                        $filtered[$num]['category'] = Category::find($val)->category_name;
                        if(!in_array('category', $columns)) array_push($columns, 'category');
                        break;
                    default:
                        $filtered[$num][$col] = $val;
                        if(!in_array($col, $columns)) array_push($columns, $col);
                        break;
                }
            }
        }
        return view('news.link_index', ['data' => $filtered, 'columns' => $columns]);
    }

    public function create_link() {
        $category_list = Category::where('category_status', 1)->pluck('category_name', 'id')->toArray();
        return view('news.link', ['categories' => $category_list]);
    }   

    public function store_link(Request $request) {
        $input_data = $request->all();
        $filtered = [];
        foreach($input_data as $col => $data) {
            if(preg_match("/(link)+/", $col)) {
                try {
                    if(simplexml_load_file($data, 'SimpleXMLElement', LIBXML_NOBLANKS)) {
                        // push the link value
                        $filtered[$col] = urlencode($data);             
                        // extract the provider name like cbssports or foxsports and push it into array
                        $name_arr = explode('.', parse_url($data)['host']);
                        $filtered['host_name'] = $name_arr[(int)count($name_arr)/2];
                        //if(!empty($this->db_mongo->table('news_links')->where('host_name', $filtered['host_name'])->get()->toArray()))
                        if(!empty($this->db_mongo->table('news_links')->where('rss_link', $filtered['rss_link'])->get()->toArray()))
                            return redirect('/news/link/create')->with('fail_status', 'Link Already Exists!');
                    }
                } catch (\Exception $e) {
                    return redirect('/news/link/create')->with('fail_status', 'Not a Valid Link! '.$e->getMessage());
                }
            } else $filtered[$col] = $data;
        }
        if($this->db_mongo->table('news_links')->insert($filtered)) return redirect('/news/link/list')->with('success_status', 'Link Added Successfully');
    } 

    public function link_edit($oid) {
        $link = $this->db_mongo->table('news_links')->where('_id', (new \MongoDB\BSON\ObjectId($oid)))->first();
        $category = Category::pluck('category_name', 'id')->toArray();
        $filtered = [];
        foreach($link as $col => $val) {
            switch ($col) {
                case '_id':
                    $filtered['oid'] = (string)$val;
                    break;
                default:
                    $filtered[$col] = $val;
                    break;
            }
        }
        return view('news.link_edit', ['data' => $filtered, 'category' => $category]);

    }

    public function link_update(Request $request) {
        $oid = $request->input('oid');
        $link = $this->db_mongo->table('news_links')->where('_id', (new \MongoDB\BSON\ObjectId($oid)));
        $data = $request->all();
        $ignore = ['_token', '_id', '_method'];
        $filtered = [];
        foreach ($data as $key => $value) {
            if(!in_array($key, $ignore) && !empty($value)) $filtered[$key] = $value;
        }
        $update = !empty($filtered) ? $link->update(['$set' => $filtered]) : null;
        if($update) return redirect('/news/link/list')->with('success_status', 'Link has been updated successfully');
        else return back()->with('fail_status', 'Link couldn\'t be updated');
    }

    public function link_destroy($oid) {
        $link = $this->db_mongo->table('news_links')->where('_id', (new \MongoDB\BSON\ObjectId($oid)));
        if($link->delete()) return redirect('/news/link/list')->with('success_status', 'Link has been removed successfully');
        else return back()->with('fail_status', 'Link couldn\'t be removed');
    }

    public function state_change($oid, $current_state) {
        if($oid != null && !empty($current_state)) {
            $state = true;
            if($current_state == 'Published') $state = false;
            if(News::where('object_id', $oid)->update(['published_status' => $state])) return redirect('/news/list')->with('success_status', 'News Status Changed Successfully');
            else return redirect('/news/list')->with('fail_status', 'News Status Couldn\'t be Changed Successfully');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('category_name', 'id')->toArray();
        $state = ['Unpublish', 'Publish'];
        return view('news.create', ['categories' => $categories, 'status' => $state]);
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
        $db_data = $sql_insert = [];
        foreach($data as $col => $val) {
            if($col != 'videolink' && $col != 'content' && $col != 'published_status') $db_data[$col] = $val;
            else $sql_insert[$col] = $val;
        }
        $db_data['published_date'] = date('Y-m-d H:i:s', time());
        if($this->db_mongo->table('news_list')->insert($db_data)) {
            $db_data['object_id'] = (string)$this->db_mongo->table('news_list')->where('title', $db_data['title'])->first()['_id'];
            $db_insert = array_merge($db_data, $sql_insert);
            if(News::create($db_insert) != null) return redirect('/news/'.$db_data['category_id'])->with('success_status', 'News has been Created Successfully');
            else return redirect('/news/list')->with('fail_status', 'News Couldn\'t be Created');
        } else return redirect('/news/list')->with('fail_status', 'News Couldn\'t be Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show($oid, $title = null)
    {
        $comments = News::where('object_id', $oid)->first()->comments()->where('flag', 'approved')->pluck('content', 'user_id')->toArray();
        $news = News::where('object_id', $oid)->get(['content', 'videolink'])->toArray()[0];
        $news_meta = $this->db_mongo->table('news_list')->where('_id', (new \MongoDB\BSON\ObjectId($oid)))->get(['title', 'link', 'category_id', 'published_date'])->toArray()[0];
        $category = Category::find($news_meta['category_id'])->category_name;
        $tags = News::where('object_id', $oid)->first()->tags()->get()->count() > 0 ? News::where('object_id', $oid)->first()->tags()->pluck('tag_name', 'id') : null;
        $products = [];
        $j=0;
        if($tags && count($tags->toArray())>=3) {
            foreach($tags as $id => $name) {
            $tagged_products = Tag::find($id)->products()->pluck('id')->toArray();
            
                for ($i=0; $i < count($tagged_products); $i++) { 
                    $product = Product::find($tagged_products[$i]);
                    if($product->tags()->get()->count()>=3 && !in_array($product, $products)) $products[$j++] = $product;
                }
            
            }
        }
        $user_comments = [];
        if(count($comments)!=0) {
            foreach($comments as $user_id => $content) {
                $name = User::find($user_id)->first_name.' '.User::find($user_id)->last_name;
                $user_comments[$name] = $content;
            }
        }
        return view('news.show', ['news' => $news, 'metadata' => $news_meta, 'category' => $category, 'tags' => $tags, 'products' => $products, 'comments' => $user_comments, 'object_id' => $oid]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit($oid)
    {
        $news = News::where('object_id', $oid)->first();
        $metadata = $this->db_mongo->table('news_list')->where('_id', (new \MongoDB\BSON\ObjectId($oid)))->first();
        $categories = Category::where('category_status', 1)->pluck('category_name', 'id')->toArray();
        $state = ['Unpublish', 'Publish'];
        return view('news.edit', ['categories' => $categories, 'status' => $state, 'news' => $news->toArray(), 'metadata' => $metadata, 'oid' =>$oid]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->input('news_id');
        $oid = $request->input('oid');
        $data = $request->all();

        $ignore = ['news_id', 'oid', '_method', '_token', 'title', 'description'];
        $news = News::find($id);
        $metadata = $this->db_mongo->table('news_list')->where('_id', (new \MongoDB\BSON\ObjectId($oid)));
        
        $filtered = [];
        foreach($data as $col => $val) {
            if(!in_array($col, $ignore) && !empty($val)) $news->$col = $val;
            if(($col == 'title' || $col == 'description') && !empty($val)) $filtered[$col] = $val;
        }

        $meta_update = !empty($filtered) ? $metadata->update(['$set' => $filtered]) : null;
        if($news->save() && ($meta_update || $meta_update == null)) return redirect('/news/list')->with('success_status', 'News Number: '.$id.' has been updated');
        else return back()->with('fail_status', 'Please Check again. News Number: '.$id.' couldn\'t be updated');
    }

    public function mass_delete(Request $request) {
        $ids = $request->input('news_oid');
        $flag = false;
        if(gettype($ids) == 'array' && !empty($ids)) {
            for ($i=0; $i < count($ids); $i++) { 
                $news = News::where('object_id', $ids[$i])->first();
                $news->tags()->detach();
                $news->users()->detach();
                $cat_rem = gettype($news->category()->dissociate()) == 'object';
                
                $comments = $news->comments();
                if($comments->get()->count() != 0) {
                    $comments->each(function($comment) {
                        $comment->delete();
                    });
                }
                $metadata_rem = $this->db_mongo->table('news_list')->where('_id', (new \MongoDB\BSON\ObjectId($ids[$i])));
                if($cat_rem && $news->delete() && $metadata_rem->delete()) $flag = true;
                else $flag = false;
            }
        } else {
            return back()->with('fail_status', 'Please select news');    
        }
        if($flag) return redirect('/news/list')->with('success_status', 'News have been removed successfully');
        else return back()->with('fail_status', 'News couldn\'t be removed');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy($oid)
    {
        $news = News::where('object_id', $oid)->first();
        $metadata_rem = $this->db_mongo->table('news_list')->where('_id', (new \MongoDB\BSON\ObjectId($oid)));
        $title = $metadata_rem->first()['title'];
        $tag_rem = $news->tags()->get()->count() > 0 ? $news->tags()->detach() : false;
        $comm_rem = true;
        if($news->comments()->get()->count() > 0) {
            try {
                $news->comments()->each(function($comment) {
                    $individual_comm = $comment->user()->dissociate();
                    if(gettype($individual_comm) == 'object') $comment->delete();
                });
            } catch (\Throwable $th) {
                $comm_rem = false;
            }
        }
        
        $user_rem = $news->users()->get()->count() > 0 ? $news->users()->detach() : false;
        $cat_rem = gettype($news->category()->dissociate()) == 'object';

        $associations = '';
        if($user_rem || $cat_rem)  {
            if($comm_rem || $tag_rem) $associations = 'Associations Removed';
        }
        if($news->delete() && $metadata_rem->delete()) return back()->with('success_status', '"'.$title.'" has been removed successfully. '.$associations);
        else return back()->with('fail_status', '"'.$title.'" couldn\'t be removed');
    }
}
