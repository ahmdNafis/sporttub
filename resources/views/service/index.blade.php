@extends('master')
@section('page')
    @if(Auth::check() && in_array('Super Admin', Auth::user()->roles()->pluck('role_name')->toArray()))
    @include('partials.sidebar')
    @endif
    <div class="row rightContent">
        <div class="col-md-7">
            @include('partials.message')
        </div>
    </div>
    <div class="row profile">
        <div class="col-md-7">
        
           <h3>Terms of Service</h3>
           <hr />
           <p><span class="column">{{ $data['content_service'] }}</span></p>
           
           @can('update', App\Service::class)
           @if(!empty($data))
           <a href="{{ route('service_edit', ['id' => $data['id']]) }}" class="btn btn-primary btn-block btn-edit-profile">Edit Terms of Service</a>
           @else 
           <span>
           <a href="{{ route('service_new') }}" class="btn btn-primary btn-block btn-edit-profile">Create Terms of Service</a>  
           </span>
           @endif
           @endcan

        </div>
    </div>
    
@endsection