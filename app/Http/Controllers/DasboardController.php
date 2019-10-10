<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Analytics;
use Spatie\Analytics\Period;


class DasboardController extends Controller
{
    
    private $user;
    //sporttube view id 201162653
    public function __construct(User $user) {
        $this->user = $user;
        //$this->map = new Lavacharts;
    }
    public function index() {
        
        $period = 15;

        $this->most_visit_page($period);
        $types = $this->user_types($period);
        $this->visitors_views($period);

        return view('dashboard', ['types' => $types]);
    }

    private function most_visit_page(int $days) {
        $most_visit_data = Analytics::fetchMostVisitedPages(Period::days($days));

        $most_table = \Lava::Datatable();
        $most_table->addStringColumn('Page Titles')->addNumberColumn('Views');
        foreach($most_visit_data->toArray() as $in => $arr) {
            $most_table->addRow([$arr['pageTitle'], $arr['pageViews']]);
        }

        \Lava::BarChart('MostVisitedPages', $most_table, [
            'title' => 'Most Visited Pages',
        ]);
    }

    private function user_types(int $days) {
        $user_types_data = Analytics::fetchUserTypes(Period::days($days));
        
        $user_table = \Lava::Datatable();
        $user_table->addStringColumn('Visitor Types');
        $user_table->addNumberColumn('Percent');
        

        $sessions_total = 0;
        foreach($user_types_data->toArray() as $in => $arr) {
            $sessions_total .= $arr['sessions'];
        }
        foreach($user_types_data->toArray() as $in => $arr) {
            $percent = ($arr['sessions']/$sessions_total)*100;
            $user_table->addRow([$arr['type'], $percent]);
        }

        
        \Lava::DonutChart('VisitorShare', $user_table, [
            'title' => 'Visitors Share'
        ]);

        return $user_types_data;
    }

    private function visitors_views(int $days) {
        $filtered = $page_views = $visitors = [];

        $view_table = \Lava::DataTable();
        $view_table->addDateColumn('Day of Month')->addNumberColumn('Number of Views');
        
        $visitor_table = \Lava::Datatable();
        $visitor_table->addDateColumn('Day of Month')->addNumberColumn('Number of Visitors');

        $data = Analytics::fetchVisitorsAndPageViews(Period::days($days));
        foreach($data->toArray() as $in => $arr) {
            foreach($arr as $col => $val) {
                switch ($col) {
                    case 'date':
                        $filtered[$in][$col] = Date('d/m', strtotime($val));
                        break;
                    case 'pageViews':
                        array_push($page_views, $val);
                        $filtered[$in][$col] = $val;
                        break;
                    case 'visitors':
                        array_push($visitors, $val);
                        $filtered[$in][$col] = $val;
                        break;
                }
            }
        }

        foreach($data as $index => $arr) {
            $view_table->addRow([$arr['date'], $arr['pageViews']]);
            $visitor_table->addRow([$arr['date'], $arr['visitors']]);
        }

        \Lava::AreaChart('PageViews', $view_table, [
            'title' => 'Views of Last 15 days',
            'legend' => [
                'position' => 'in'
            ]
        ]);

        \Lava::AreaChart('Visitor15', $visitor_table, [
            'title' => 'Visitors of Last 15 days',
            'legend' => [
                'position' => 'in'
            ]
        ]);
    }

}
