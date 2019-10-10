<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Trending as Trending;

class UpdateTrends extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trend:update {weighting=4} {--dateFrom}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'trends:update
                            {weighting : Weight that should be passed, default is 4}
                            {--dateFrom= : Whether starting date should be updated}';

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
        $total_weight = $this->argument('weighting');
        $start_date_opt = $this->option('dateFrom');
        $diff_year = '';
        $start_flag = false;
        
        foreach(Trending::all()->toArray() as $key => $arr) {
            $trend_ins = Trending::find($arr['_id']);
            $interval = null;
            $start_date = new \DateTime($arr['date_from']);
            $end_date = new \DateTime($arr['date_to']);
            foreach($arr as $col => $val) {
                //if($col == '_id') $trend_ins = Trending::find($val);
                if($start_date_opt && $col == 'date_from') {
                    $start_date = new \DateTime($val);
                    $diff_year = date('y')-$start_date->format('y');
                    $interval = $diff_year > (int)$trend_ins->interval ? $diff_year : (int)$trend_ins->interval;
                    if($diff_year >= 0) {
                        $start_date->modify('+'.$interval.' years');
                        $trend_ins->date_from = $start_date->format('Y-m-d');
                        $start_flag = $trend_ins->save();
                        if($start_flag) $this->info(date('Y-m-d H:i:s').'  Starting Date has been Updated Successfully....');
                    //else a negative difference is contrary, as some games will have an interval of several years,
                    // hence user can set a date that is 2 years ahead 
                    //difference of zero is the same year
                    }                    
                }
                if($col == 'date_to' && $start_flag) {
                    $end_date->modify('+'.$interval.' years');
                    $trend_ins->date_to = $end_date->format('Y-m-d');
                    if($trend_ins->save()) $this->info(date('Y-m-d H:i:s').'  Ending Date has been Updated Successfully....');
                }
                if($col == 'weighting' && $start_flag) {
                    $weighting = 0;
                    $current_date = new \DateTime(date('Y-m-d'));
                    $date_diff = $current_date->diff($start_date); //start-current
                    if($date_diff->format('%r')!='-' ) {
                        if(($date_diff->m <= 3 && $date_diff->m >= 0)) {
                            //if greater than 2 days, than m+1
                            if($date_diff->d > 2) $weighting = $total_weight-($date_diff->m+1);
                            else $weighting = $total_weight-$date_diff->m;
                        }
                    }
                    $trend_ins->weighting = $weighting;
                    if($trend_ins->save()) $this->info(date('Y-m-d H:i:s').'  Weighting has been Updated Successfully....');
                }
            }
        }


    }
}
