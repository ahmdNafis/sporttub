<?php

namespace App\Http\Controllers;

use App\Comment;
use App\News;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $comment;
    private $news;
    private $user;
    public function __construct(Comment $com, News $news, User $user) {
        $this->comment = $com;
        $this->news = $news;
        $this->user = $user;
    }

    public function index() {
        $comments = $this->comment->get(['id', 'flag', 'user_id', 'news_id'])->toArray();
        $filtered = $columns = [];
        foreach($comments as $num => $arr) {
            foreach($arr as $col => $val) {
                switch($col) {
                    case 'id':
                    $filtered[$num]['id'] = $val;
                    break;
                    case 'user_id':
                    $filtered[$num]['user'] = $this->user->find($val)->first_name.' '.$this->user->find($val)->last_name;
                    $filtered[$num]['user_id'] = $val;
                    if(!in_array('users', $columns)) array_push($columns, 'users');
                    if(!in_array($col, $columns)) array_push($columns, $col);
                    break;
                    case 'news_id':
                    $object_id =  $this->news->find($val)->object_id;
                    $filtered[$num]['news'] = str_limit(DB::connection('mongodb')->table('news_list')->find($object_id)['title'], 45);
                    if(!in_array('news', $columns)) array_push($columns, 'news');
                    break;
                    default:
                    $filtered[$num][$col] = $val;
                    if(!in_array($col, $columns)) array_push($columns, $col);
                    break;
                }
            }
        }
        return view('comment.index', ['data' => $filtered, 'columns' => $columns]);
    }

    public function list($user_id) {
        $comments = $this->user->find($user_id)->comments()->get(['id', 'user_id', 'content', 'flag', 'news_id'])->toArray();
        $filtered = $columns = [];
        foreach($comments as $in => $arr) {
            foreach($arr as $col => $val) {
                switch ($col) {
                    case 'news_id':
                    $object_id =  $this->news->find($val)->object_id;
                    $filtered[$in]['news'] = str_limit(DB::connection('mongodb')->table('news_list')->find($object_id)['title'], 45);
                    if(!in_array('news', $columns)) array_push($columns, 'news');
                    break;
                    
                    default:
                    $filtered[$in][$col] = $val;
                    if(!in_array($col, $columns)) array_push($columns, $col);
                    break;
                }
            }
        }
        return view('comment.index', ['data' => $filtered, 'columns' => $columns]);
    }

    public function change_flag($comment_id, $selected_flag) {
        $comment = $this->comment->find($comment_id);
        if($selected_flag!=null) $comment->flag = $selected_flag;
        if($comment->save()) return back()->with('success_status', 'Comment No.:'.$comment_id.' has been '.$selected_flag);
        else back()->with('fail_status', 'Comment No.:'.$comment_id.' couldn\'t be '.$selected_flag);
    }

    public function login_authenticate(Request $request) {
        $data = $request->only('email', 'password');
        if(Auth::attempt($data)) return back()->with('success_status', 'Login Successful. You can now start commenting');
        else return back()->with('fail_status', 'Couldn\'t login. Please try again with correct credentials');
    }

    public function store(Request $request) {
        $user_id = $this->user->find($request->input('user_id'))->id;
        $news_id = $this->news->where('object_id', $request->input('object_id'))->first()->id;
        $new_comment = $this->comment->create(['user_id' => $user_id, 'news_id' => $news_id, 'content' => $request->input('content')]);
        if($new_comment != null) return back()->with('success_status', 'Your comment has forwarded for review');
        else return back()->with('fail_status', 'Comment couldn\'t be verified');
    }

    public function edit($comment_id) {
        $comment = $this->comment->find($comment_id)->toArray();
        return view('comment.edit', ['properties' => $comment]);
    }

    public function update(Request $request) {
        $data = $request->input('content');
        $id = $request->input('_id');
        $comment = $this->comment->find($id);
        $comment->content = $data;
        if($comment->save()) return redirect('/comment')->with('success_status', 'Comment Number: '.$id.' has been updated successfully');
        else back()->with('fail_status', 'Comment Number: '.$id.' couldn\'t be updated');
    }

    public function destroy($comment_id) {
        $comment = $this->comment->find($comment_id);
        if(gettype($comment->user()->dissociate()) == 'object' && $comment->delete()) return back()->with('success_status', 'Comment Number: '.$comment_id.' has been removed successfully');
        else back()->with('fail_status', 'Comment Number: '.$comment_id.' couldn\'t be removed');
    }
}
