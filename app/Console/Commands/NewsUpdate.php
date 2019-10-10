<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Console\Commands\InputToDB as InputToDB;

class NewsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $db_mongo;
    protected $signature = 'news:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve news from api periodically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->db_mongo = DB::connection('mongodb');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    
    public function handle()
    {
        $links = $this->db_mongo->table('news_links')->pluck('rss_link')->toArray();
        $flag = false;
        for($i=0; $i<count($links); $i++) {
            (new InputToDB())->inputToMongo($links[$i]);
            //(new InputToDB())->inputToMongo(urldecode('https%3A%2F%2Fapi.foxsports.com%2Fv1%2Frss%3FpartnerKey%3DzBaFxRyGKCfxBagJG9b8pqLyndmvo7UU%26tag%3Dsoccer'));
            if($i==count($links)-1) $flag = true;
        }
        if($flag) $this->info('News has been Updated Duly.');
    }
}
