@extends('master')
@section('page')
    <div class="row">
        <div class="col-md-10 newsTop">
            
            <ul>
                @foreach($data as $num => $arr)
                <li>
                    <p>{{  $arr['date']  }}</p>
                    <h4><a href="{{ route('news_details', ['oid' => $arr['oid']]) }}">{{ $arr['title'] }}</a></h4>
                    
                    <p class="text-justify">{{ $arr['description'] }}</p>
                    <hr />
                </li>
                @endforeach    
            </ul>
            
        </div>
    </div>
@endsection