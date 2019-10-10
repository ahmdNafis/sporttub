<?php

namespace App\Console\Commands;

use App\Tag;
use App\Type;
use App\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class AttachTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tag:attach {type=Sports}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve all the tags and attach them by analyzing the news content
                                {type : Write the name of the Type that should be matched}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $type_name = $this->argument('type');
        $news = Type::where([['type_name', $type_name], ['type_status', 1]])->first()->news()->pluck('category_id', 'object_id')->toArray();
        $tags = Tag::where('tag_status', true)->pluck('category_id', 'id')->toArray();
        foreach($news as $obj_id => $nw_cat_id) {
            foreach($tags as $tag_id => $tg_cat_id) {
                if($nw_cat_id == $tg_cat_id) {

                    $tag_match = Tag::find($tag_id);
                    $news_match = News::where('object_id', $obj_id)->first();

                    if(!in_array($tag_id, $news_match->tags()->pluck('id')->toArray())) {
                        $content_match = stristr($news_match->content, $tag_match->tag_name);
                        $title_match = stristr(DB::connection('mongodb')->table('news_list')->where('_id', (new \MongoDB\BSON\ObjectId($obj_id)))->first()['title'], $tag_match->tag_name);

                        if($content_match || $title_match) { 

                            if($news_match->tags()->get()->count() <= 30) {
                                $news_match->tags()->attach($tag_match);
                                $this->info('Tag No.: '.$tag_id.' has been attached to News No.: '.$news_match->id);
                            } else $this->error('News No.: '.$news_match->id.' has exceeded the limit for tags (not more than 30)');

                        } else $this->line('Tag No.: '.$tag_id.' had no associated News');

                    } else $this->line('Tag No.: '.$tag_id.' is already associated with News No.: '.$news_match->id);

                }
            }
        }
    }
}
