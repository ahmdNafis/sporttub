@extends('master')
@section('page')
    @if(Auth::check())
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            <div class="dashContent">
                <div class="page-heading ">
                    <h2>Analytics</h2>
                </div>
                <hr />
                <ul>
                    <li class="charts"><div id="view_chart"></div></li> 
                    <li class="charts"><div id="most_visit_chart"></div></li>     
                    <li class="charts"><div id="visitor_chart"></div></li>
                    <li class="charts"><div id="share_chart"></div></li>
                </ul>
            </div>
            
        </div>
    </div>
    @endif
    @areachart('PageViews', 'view_chart')
    @areachart('Visitor15', 'visitor_chart')
    @barchart('MostVisitedPages', 'most_visit_chart')
    @donutchart('VisitorShare', 'share_chart')
    
@endsection