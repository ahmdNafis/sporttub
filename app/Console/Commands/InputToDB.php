<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use App\News as News;

class InputToDB {
    private $db_mongo;

    public function __construct() {
        $this->db_mongo = DB::connection('mongodb');
    }

    public function inputToMongo($url) {
        $xml = simplexml_load_file(urldecode($url), 'SimpleXMLElement', LIBXML_NOEMPTYTAG);
        $data = ((array) $xml)['channel']->item;
        $link_array = explode('.', trim((string) ((array) $xml)['channel']->link));
        $xml_host_name = $link_array[count($link_array)/2];
        $mongo_instance = $this->db_mongo->table('news_links')->where('rss_link', $url)->get(['_id', 'category_id'])->first();
        $category_id = (int) $mongo_instance['category_id'];
        $news_link_id = (string) $mongo_instance['_id'];
        $filtered = [];
        $i=0;
        if($data->count()>0) {
            foreach($data as $items => $arr) {
                foreach($arr as $key => $value) {
                        switch ($key) {
                            case 'pubDate':
                                $filtered[$i]['published_date'] = Date('Y-m-d H:i:s', strtotime($value));
                                break;
                            case 'category':
                                $filtered[$i]['category_id'] = $category_id;
                            case 'enclosure':
                                $filtered[$i]['thumbnail_url'] = $value['url'];
                                break;
                            case 'guid':
                                break;
                            default:
                                if(!array_key_exists('category', $arr)) $filtered[$i]['category_id'] = $category_id;
                                $filtered[$i][$key] = trim(preg_replace("/[\t\n]+/", '', $value));
                                break;
                        }
                    
                }
                $filtered[$i]['news_link_id'] = $news_link_id;
                if(!$this->recordExist('title', $filtered[$i]['title'], 'news_list')) {
                    $this->db_mongo->table('news_list')->insert($filtered[$i]);
                    if($this->inputToSql($filtered[$i])) continue;               
                }
                $i++;
            }
            return true;
        } else return false;
    }

    private function inputToSQL(Array $data) {
        if(count($data)>0) {    
            $full_text = '';
            $content = '';
            try {
                $full_text = file_get_contents($data['link']);
            } catch (\Exception $e) {
                
                $options = ['http' => ['method' => 'GET', 'header' => ['Accept-Encoding: gzip, deflate, br', 'Accept-Language: en-US,en;q=0.5', 
                'Connection: keep-alive', 'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:60.0) Gecko/20100101 Firefox/60.0'], 'request_fulluri' => true, 'proxy' => '188.186.180.135:8080']];
                
                $context = stream_context_create($options);
                $full_text = gzdecode(file_get_contents(trim($data['link']), false, $context));
            }
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($full_text);
            foreach($dom->getElementsByTagName('p') as $text) {
                $content .= strlen(trim($text->nodeValue)) > 100 ? preg_replace("/[\t\n]+/", '', trim($text->nodeValue)) : '';
            }
            $images = [];
            foreach($dom->getElementsByTagName('img') as $img) {
                $temp = $img->getAttribute('src');
                if(!in_array($temp, $images)) array_push($images, $temp);
            }
            $inputValues=[];
            foreach($data as $key => $val) {
                switch ($key) {
                    case 'link':
                        $inputValues['newslink'] = preg_replace("/^www\.|\.com$/", '', $val);
                        break;
                    case 'title':
                    case 'description':
                        break;
                    default:
                        $inputValues[$key] = trim($val);
                        break;
                }
            }
            $inputValues['content'] = $content;
            $inputValues['imagelink'] = count($images) >= 1 ? $images[0] : null;
            $inputValues['object_id'] = (string) $this->db_mongo->table('news_list')->where('title', $data['title'])->pluck('_id')[0];
            $inputValues['published_status'] = true;
            libxml_use_internal_errors(false);
            return News::create($inputValues) instanceof App\News ? true : false;
        } else return false;
    }

    private function recordExist($column, $val, $table) {
        if($this->db_mongo->table($table)->where($column, $val)->get()->count()>0) return true;
        else false;
    }
}
