@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-7">
        @include('partials.message')
        <div class="page-heading">
            <h2>Update Privacy Policy</h2>
        </div>
        <hr />
        <form action="{{ route('policy_update') }}" method="POST">
            <input name="policy_id" type="hidden" value="{{ $data['id'] }}">
            @method('PUT')
            @csrf
            <fieldset disabled>
            <div class="form-group">
                <label for="policyIdInput">Policy ID</label>
                <input name="policy_id" type="text" class="form-control" id="policyIdInput" placeholder="{{ $data['id'] }}" />
            </div>
            </fieldset>
            <div class="form-group">
                <label for="contentInput">Content</label>
                <textarea name="content_privacy" rows="20" class="form-control" id="contentInput">{{ $data['content_privacy'] }}</textarea>
            </div>
            <div class="form-group">
                    <label for="statusOption">Privacy State</label>
                    <select name="privacy_status" class="form-control" id="statusOption">
                        <option value="">...Current state: {{ $data['privacy_status'] == 1 ? 'Active' : 'Inactive' }}...</option>
                        
                            <option value="{{ $data['privacy_status'] == 1 ? 0 : 1 }}">{{ $data['privacy_status'] == 1 ? 'Deactivate' : 'Activate' }}</option>
                        
                    </select>
                </div>           
            <button type="submit" class="btn btn-primary">Update Policy</button>
        </form>
    </div>
</div>
@endsection