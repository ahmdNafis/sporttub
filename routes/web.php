<?php

use Illuminate\Support\Facades\DB;
use App\Trending;
use App\Category;
use App\News;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function() {
        $connection = DB::connection('mongodb')->table('news_list');
        $news = $connection->orderBy('published_date', 'desc')->get(['title', 'description', 'category_id', 'published_date', '_id', 'thumbnail_url'])->toArray();
        $news_last_week = $connection->whereBetween('published_date', [date('Y-m-d H:i:s', strtotime('-14 days')), date('Y-m-d H:i:s', strtotime('-7 days'))])->limit(5)->pluck('title', '_id');
        $weight = Trending::pluck('weighting')->toArray();
        $min_weight = min($weight) < 1 ? min($weight)+1 : $weight;
        $max_weight = max($weight);
        $trend_categories = $exclude_categories = Trending::whereBetween('weighting', [$min_weight, $max_weight])->pluck('category_id')->toArray();
        $filtered = $category_names = $archive = [];
        foreach($news as $key => $arr) {
            foreach($arr as $col => $val) {
                $cat_name = Category::find($arr['category_id'])->category_name;
                $pub_date = new \Datetime($arr['published_date']);
                $curr_date = new \Datetime(date('Y-m-d'));
                $current_diff = $pub_date->diff($curr_date);
                if((int)$current_diff->format('%a') <= 21) {
                    switch ($col) {
                        case 'category_id':
                            $filtered[$key]['category'] = $cat_name;
                            array_push($exclude_categories, $arr['category_id']);
                            break;
                        case 'published_date':
                            $filtered[$key]['date'] = Date('d-M-Y', strtotime($arr['published_date']));
                            break;
                        case 'description':
                            $filtered[$key][$col] = str_limit($val, 100);
                            break;
                        
                        default:
                            if($col == '_id') $filtered[$key]['imagelink'] = News::where('object_id', (string)$val)->pluck('imagelink')->toArray()[0];
                            $filtered[$key][$col] = $val;
                            break;
                    }
                } else {
                    if(array_key_exists($arr['category_id'], $archive)) array_push($archive, $arr['category_id']);
                    else {
                        //if(count($archive[$cat_name]) <= 4 || count($archive[$cat_name]) == 0) 
                            $archive[$cat_name][(string)$arr['_id']] = $arr['title'];
                    }
                }
            }
        }
        $categories_list = Category::where('category_status', 1)->whereNotIn('id', $exclude_categories)->get(['id', 'category_name'])->toArray();
        foreach($trend_categories as $id) $category_names[$id] = Category::find($id)->category_name;

        return view('home', ['data' => $filtered, 'trend_categories' => $category_names, 'categories_list' => $categories_list, 'last_week' => $news_last_week]); //$content_list
})->name('home');

Auth::routes();

Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider')->name('facebook_login');

Route::get('/login/facebook/callback', 'Auth\LoginController@handleProviderFacebookCallback')->name('facebook_callback');

Route::get('/login/google', 'Auth\LoginController@redirectToGoogleProvider')->name('google_login');

Route::get('/login/google/callback', 'Auth\LoginController@handleProviderGoogleCallback')->name('google_callback');

Route::get('/login/twitter', 'Auth\LoginController@redirectToTwitterProvider')->name('twitter_login');

Route::get('/login/twitter/callback', 'Auth\LoginController@handleProviderTwitterCallback')->name('twitter_callback');

Route::get('/user', 'UserController@index')->name('user_index')->middleware('can:view,App\User');

Route::get('/user/edit/{user_id}', 'UserController@edit')->name('user_edit')->middleware('can:update,App\User');

Route::put('/user/update', 'UserController@update')->name('user_update');

Route::get('/user/state/{user_id}/{current_state}', 'UserController@state_change')->name('user_state');

Route::get('/user/remove/{$user_id}', 'UserController@destroy')->name('user_remove')->middleware('can:delete,App\User');;

Route::post('/user/role', 'UserController@role_change')->name('user_role');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

//News routes
Route::get('/news/list', 'NewsController@news_list')->name('news_all')->middleware('can:view,App\News');

Route::get('/news/state/{oid}/{current_state}', 'NewsController@state_change')->name('news_state')->middleware('can:update,App\News');

Route::get('/news/create', 'NewsController@create')->name('news_create')->middleware('can:create,App\News');

Route::post('/news/store', 'NewsController@store')->name('news_store');

Route::get('/news/{category_id}', 'NewsController@index')->name('news_home')->middleware('can:view,App\News');

Route::get('/news/category/{category_name}', 'NewsController@news_specific')->name('news_specific');

Route::get('/news/link/list', 'NewsController@link_list')->name('link_list')->middleware('can:view,App\News');

Route::get('/news/link/edit/{oid}', 'NewsController@link_edit')->name('link_edit')->middleware('can:update,App\News');

Route::put('/news/link/update', 'NewsController@link_update')->name('link_update');

Route::get('/news/link/destroy/{oid}', 'NewsController@link_destroy')->name('link_destroy')->middleware('can:delete,App\News');

Route::get('/news/link/create', 'NewsController@create_link')->name('news_link')->middleware('can:create,App\News');

Route::post('/news/link/store', 'NewsController@store_link')->name('create_link');

Route::get('/news/show/{oid}/{title?}', 'NewsController@show')->name('news_details');

Route::get('/news/edit/{oid}', 'NewsController@edit')->name('news_edit')->middleware('can:update,App\News');

Route::put('/news/update', 'NewsController@update')->name('news_update');

Route::get('/news/destroy/{oid}', 'NewsController@destroy')->name('news_remove')->middleware('can:delete,App\News');

Route::put('/news/mass_delete', 'NewsController@mass_delete')->name('news_remove_multiple')->middleware('can:delete,App\News');

//Category Methods
Route::get('/category', 'CategoryController@index')->name('category_index')->middleware('can:view,App\Category');

Route::get('/category/new', 'CategoryController@create')->name('category_new')->middleware('can:create,App\Category');

Route::post('/category/store', 'CategoryController@store');

Route::get('/category/check_presence/{title}', 'CategoryController@check_presence');

Route::get('/category/edit/{category_name}', 'CategoryController@edit')->name('category_edit')->middleware('can:update,App\Category');

Route::put('/category/update','CategoryController@update')->name('category_update');

Route::get('/category/show/{category_name}', 'CategoryController@show')->name('category_show')->middleware('can:view,App\Category');

Route::get('/category/destroy/{category_name}', 'CategoryController@destroy')->name('category_remove')->middleware('can:delete,App\Category');

Route::get('/category/state/{title}/{current_state}', 'CategoryController@change_state')->name('state_update');

//Type Controller
Route::get('/type', 'TypeController@index')->name('type_index')->middleware('can:view,App\Type');

Route::get('/type/state/{type_id}/{current_state}', 'TypeController@state_change')->name('type_state');

Route::get('/type/create', 'TypeController@create')->name('type_create')->middleware('can:create,App\Type');

Route::post('/type/store', 'TypeController@store');

Route::get('/type/edit/{type_id}', 'TypeController@edit')->name('type_edit')->middleware('can:update,App\Type');

Route::put('/type/update', 'TypeController@update')->name('type_update');

Route::get('/type/destroy/{type_id}', 'TypeController@destroy')->name('type_remove')->middleware('can:delete,App\Type');

//Role Controller
Route::get('/role', 'RoleController@index')->name('role_index')->middleware('can:view,App\Role');

Route::get('/role/create', 'RoleController@create')->name('role_create')->middleware('can:create,App\Role');

Route::post('/role/store', 'RoleController@store')->name('role_store');

Route::get('/role/edit/{role_id}', 'RoleController@edit')->name('role_edit')->middleware('can:update,App\Role');

Route::put('/role/update', 'RoleController@update')->name('role_update');

Route::get('/role/permission/edit/{role_id}', 'RoleController@permission_edit')->name('role_permission_edit')->middleware('can:update,App\Role');

Route::put('/role/permission/update', 'RoleController@permission_update')->name('role_permission_update');

Route::get('/role/destroy/{role_id}', 'RoleController@destroy')->name('role_remove')->middleware('can:delete,App\Role');

Route::get('/role/state/{role_id}/{current_state}', 'RoleController@state_change')->name('role_state');

//Tag Controller
Route::get('/tag', 'TagController@index')->name('tag_index')->middleware('can:view,App\Tag');

Route::get('/tag/create', 'TagController@create')->name('tag_create')->middleware('can:create,App\Tag');

Route::post('/tag/store', 'TagController@store')->name('tag_store');

Route::get('/tag/edit/{tag_id}', 'TagController@edit')->name('tag_edit')->middleware('can:update,App\Tag');

Route::put('/tag/update', 'TagController@update')->name('tag_update');

Route::get('/tag/delete/{tag_id}', 'TagController@destroy')->name('tag_remove')->middleware('can:delete,App\Tag');

Route::put('/tag/mass_delete', 'TagController@mass_delete')->name('role_mass_remove')->middleware('can:delete,App\Tag');

Route::get('/tag/state/{tag_id}/{current_state}', 'TagController@state_change')->name('tag_state');

Route::get('/tag/create_file', 'TagController@create_file')->name('tag_file_create')->middleware('can:create,App\Tag');

Route::post('/tag/upload_file', 'TagController@upload_file')->name('tag_upload');

//Product Routes
Route::get('/product', 'ProductController@index')->name('product_index')->middleware('can:view,App\Product');

Route::get('/product/create', 'ProductController@create')->name('product_create')->middleware('can:create,App\Product');

Route::post('/product/store', 'ProductController@store')->name('product_store');

Route::get('/product/edit/{product_id}', 'ProductController@edit')->name('product_edit')->middleware('can:update,App\Product');

Route::put('/product/update', 'ProductController@update')->name('product_update');

Route::get('/product/delete/{product_id}', 'ProductController@destroy')->name('product_remove')->middleware('can:delete,App\Product');

Route::get('/product/state/{product_id}/{current_state}', 'ProductController@state_change')->name('product_state');

Route::get('/product/attach/tag/form/{product_id}', 'ProductController@attach_tag')->name('product_attach_form');

Route::post('/product/attach', 'ProductController@tag_store')->name('product_tag_store');

Route::get('/product/tag/remove/{product_id}', 'ProductController@tag_remove')->name('product_tag_remove')->middleware('can:delete,App\Product');

Route::post('product/tag/detach', 'ProductController@tag_destroy')->name('product_tag_detach');

//Comments Controller
Route::get('/comment', 'CommentController@index')->name('comment_index')->middleware('can:view,App\Comment');

Route::get('/comment/list/{user_id}', 'CommentController@list')->name('comment_list')->middleware('auth');

Route::get('/comment/edit/{comment_id}', 'CommentController@edit')->name('comment_edit');

Route::put('/comment/update', 'CommentController@update')->name('comment_update');

Route::get('/comment/change/flag/{comment_id}/{selected_flag}', 'CommentController@change_flag')->name('comment_flag');

Route::get('/comment/destroy/{comment_id}', 'CommentController@destroy')->name('comment_remove')->middleware('can:delete,App\Comment');

Route::post('/comment/store', 'CommentController@store')->name('comment_store');

Route::post('/comment/login', 'CommentController@login_authenticate')->name('comment_login');

//Route::get('/comment/remove/{comment_id}', 'CommentController@destroy')->name('comment_remove')->middleware('can:delete,App\Comment');

//dashboard controller
Route::get('/dashboard', 'DasboardController@index')->name('dashboard');

//profile controller
Route::get('/profile/{user_id}', 'ProfileController@show')->name('profile_show');

Route::get('/profile/edit/{user_id}', 'ProfileController@edit')->name('profile_edit');

Route::put('/profile/update', 'ProfileController@update')->name('profile_update');

//privacy controller
Route::get('/privacy', 'PrivacyPolicyController@index')->name('view_policy');

Route::get('/privacy/edit/{id}', 'PrivacyPolicyController@edit')->name('policy_edit')->middleware('can:update,App\Privacy');

Route::put('/privacy/update', 'PrivacyPolicyController@update')->name('policy_update');

Route::get('/privacy/new', 'PrivacyPolicyController@create')->name('policy_new')->middleware('can:create,App\Privacy');

Route::post('/privacy/store', 'PrivacyPolicyController@store')->name('policy_store');

//Route::get('/privacy/delete/{id}', 'PrivacyPolicyController@destroy')->name('policy_destroy');

//service terms controller
Route::get('/service', 'ServiceTermsController@index')->name('view_service');

Route::get('/service/edit/{id}', 'ServiceTermsController@edit')->name('service_edit')->middleware('can:update,App\Service');

Route::put('/service/update', 'ServiceTermsController@update')->name('service_update');

Route::get('/service/new', 'ServiceTermsController@create')->name('service_new')->middleware('can:create,App\Service');

Route::post('/service/store', 'ServiceTermsController@store')->name('service_store');

//contact controller
Route::get('/contact', 'ContactController@index')->name('contact_index')->middleware('can:view,App\Contact');

Route::get('/contact/new', 'ContactController@create')->name('contact_new');

Route::post('/contact/post', 'ContactController@store')->name('contact_store');

Route::get('/contact/show/{id}', 'ContactController@show')->name('contact_show')->middleware('can:view,App\Contact');

Route::get('/contact/delete/{id}', 'ContactController@destroy')->name('contact_destroy')->middleware('can:delete,App\Contact');