@extends('master')
@section('page')
    @if(Auth::check())
    @include('partials.sidebar')
    <div class="row profile">
        <div class="col-md-7">
        @include('partials.message')
            
                <ul>
                    @foreach($data as $col => $val)
                            @if($col == 'created_at' || $col == 'updated_at')
                            <li><p><span class="column">{{ ucfirst(implode(' ', explode('_', $col))).' :' }}</span><span class="value">{{ date('d-m-y', strtotime($val)) }}</span></p></li>
                            @else 
                            <li><p><span class="column">{{ ucfirst(implode(' ', explode('_', $col))).' :' }}</span><span class="value">{{ !empty($val) ? $val : 'Not Present' }}</span></p></li>
                            @endif
                    @endforeach
                    
                </ul> 
        </div>
    </div>
    @endif
@endsection