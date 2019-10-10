@extends('master')
@section('page')
    @if(Auth::check())
    @include('partials.sidebar')
    
    @include('partials.message')
    <div class="row rightContent">
        
        <div class="col-md-7">
            <div class="page-heading">
                <h2>Create Terms of Service</h2>
            </div>
            <hr />
            <form id="serviceNew" action="{{ route('service_store') }}" method="POST">
            @csrf
                <div class="form-group">
                    <label for="contentInput">Content *</label>
                    <textarea name="content_service" rows="20" class="form-control" id="contentInput" required></textarea>
                </div>
                <div class="form-group">
                    <label for="statusOption">Terms of Service State *</label>
                    <select name="service_status" class="form-control" id="statusOption" required>
                        <option value="">...Please select a state...</option>
                        @foreach($status as $state)
                            <option value="{{ $state == 'Activate' ? 1 : 0 }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>                

                <button id="serviceSubmit" type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
    @endif
@endsection