@extends('master')
@section('page')
    @if(Auth::check())
    @include('partials.sidebar')
    
    @include('partials.message')
    <div class="row rightContent">
        
        <div class="col-md-7">
            <div class="page-heading">
                <h2>Create Privacy Policy</h2>
            </div>
            <hr />
            <form id="privacyNew" action="{{ route('policy_store') }}" method="POST">
            @csrf
                <div class="form-group">
                    <label for="contentInput">Content *</label>
                    <textarea name="content_privacy" rows="6" maxlength="250" class="form-control" id="contentInput" required></textarea>
                </div>
                <div class="form-group">
                    <label for="statusOption">Privacy State *</label>
                    <select name="privacy_status" class="form-control" id="statusOption" required>
                        <option value="">...Please select a state...</option>
                        @foreach($status as $state)
                            <option value="{{ $state == 'Activate' ? 1 : 0 }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>                

                <button id="privacySubmit" type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
    @endif
@endsection