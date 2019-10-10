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
        
           <h3>Privacy Policy</h3>
           <hr />
           <p><span class="column">{{ $data['content_privacy'] }}</span></p>
           
           @can('update', App\Privacy::class)
           @if(!empty($data))
           <a href="{{ route('policy_edit', ['id' => $data['id']]) }}" class="btn btn-primary btn-block btn-edit-profile">Edit Privacy Policy</a>
           @else 
           <span>
           <a href="{{ route('policy_new') }}" class="btn btn-primary btn-block btn-edit-profile">Create Privacy Policy</a>  
           </span>
           @endif
           @endcan

        </div>
    </div>
    
@endsection