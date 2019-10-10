<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Comment;
use App\User;
use App\News;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $news = News::pluck('id')->toArray();
    $users = User::pluck('id')->toArray();
    $flag = ['approved', 'declined', 'pending'];
    return [
        'content' => $faker->realText(rand(50, 150)),
        'user_id' => rand(min($users), max($users)),
        'news_id' => rand(max($news), min($news)),
        'flag' => $flag[rand(0, count($flag)-1)]
    ];
});
