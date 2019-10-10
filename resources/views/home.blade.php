@extends('master')
@section('page')
@include('partials.top_news')
    <div id="newsContainer">
        <?php
            $temp = [];
            $i = $j = $k = 0;
        ?>
        @while(!empty($data))
            <?php
                $temp[$j] = $data[$i];
                if(isset($data[$i+1])) $temp[$j+1] = $data[$i+1];
                //if(isset($data[$i+2])) $temp[$j+2] = $data[$i+2];
                unset($data[$i]);
                if(isset($data[$i+1])) unset($data[$i+1]);
                //if(isset($data[$i+2])) unset($data[$i+2]);
                $data = array_values($data);
                //$i++;
            ?>
            @if(count($temp) <= 2)
            <div class="row">
                @while(!empty($temp))
                @if($k==0)
                <div id="listNews"  class="col-md-2 fadeContent">
                    <div id="trendList">
                        <h4>Trending News</h4>
                        <ul>
                            @foreach($trend_categories as $id => $name) 
                            <li><a href="{{ route('news_specific', ['category_name' => $name]) }}">{{ $name.' News' }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div id="trendList">
                        <h4>Other News</h4>
                        <ul>
                            @foreach($categories_list as $id => $arr) 
                            <li><a href="{{ route('news_specific', ['category_name' => $arr['category_name']]) }}">{{ $arr['category_name'].' News' }}</a></li>
                            @endforeach
                         </ul>
                    </div>
                </div>
                @elseif($k%2==0)
                <div id="listNews" class="col-md-2 fadeContent">{{' '}}</div>
                @endif
                <div class="col-md-4 {{ $k%2==0 ? 'boxTop' : '' }}">
                    <div id="newsBox" class="jumbotron">
                        <div id="newsCategory">
                            <p>{{ $temp[$j]['category'] }}</p>
                        </div>
                        @if($temp[$j]['imagelink'] != null)
                        <img src="{{ $temp[$j]['imagelink'] }}" alt="{{ $temp[$j]['title'] }}" class="img-fluid" />
                        @endif
                        <h3 class="display-6"><a href="{{ route('news_details', ['oid' => (string)$temp[$j]['_id'], 'title' => $temp[$j]['title']]) }}">{{ $temp[$j]['title'] }}</a></h3>
                        <p class="newsDate">{{ $temp[$j]['date'] }}</p>
                        <hr class="my-4" />
                    <p class="lead">{{ $temp[$j]['description'] }}</p>
                    </div>
                </div>
                <?php
                    unset($temp[$j]);
                    $temp = array_values($temp);
                    $k++;
                ?>
                @endwhile
                @if($k==2)
                <div class="col-md-2 boxRight fadeContent">
                    <div id="trendList" class="newsWeek">
                        <h4>Last Week</h4>
                        <ul>
                            @foreach($last_week as $id => $title) 
                            <li><a href="{{ route('news_details', ['oid' => (string)$id, 'title' => $title]) }}">{{ $title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                </div> 
            </div>
            @endif       
            
        @endwhile
    </div>
@endsection