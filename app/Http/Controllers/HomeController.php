<?php

namespace App\Http\Controllers;

use App\Trending;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*$weight = Trending::pluck('weighting')->toArray();
        $min_weight = min($weight) < 1 ? min($weight)+1 : $weight;
        $max_weight = max($weight);
        $categories = Trending::whereBetween('weighting', [$min_weight, $max_weight])->pluck('category_id')->toArray();
        $content_list = [];
        $news = DB::connection('mongodb')->table('news_list')->get()->toArray();
        foreach($news as $num => $arr) {
            foreach ($arr as $col => $value) {
                if(in_array((int)$news[$num]['category_id'], $categories)) $content_list[$num][$col] = $value;
            }
        }*/
        return view('home');
    }
}
