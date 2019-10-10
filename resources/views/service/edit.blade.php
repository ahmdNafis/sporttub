@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-9">
        @include('partials.message')
        <div class="page-heading">
            <h2>Update Terms of Service</h2>
        </div>
        <hr />
        <form action="{{ route('service_update') }}" method="POST">
            <input name="service_id" type="hidden" value="{{ $data['id'] }}">
            @method('PUT')
            @csrf
            <fieldset disabled>
            <div class="form-group">
                <label for="serviceIdInput">Service ID</label>
                <input name="service_id" type="text" class="form-control" id="serviceIdInput" placeholder="{{ $data['id'] }}" />
            </div>
            </fieldset>
            <div class="form-group">
                <label for="contentInput">Content</label>
                <textarea name="content_service" rows="20" class="form-control" id="contentInput">{{ $data['content_service'] }}</textarea>
            </div>
            <div class="form-group">
                    <label for="statusOption">Terms of Service State</label>
                    <select name="service_status" class="form-control" id="statusOption">
                        <option value="">...Current state: {{ $data['service_status'] == 1 ? 'Active' : 'Inactive' }}...</option>
                        
                            <option value="{{ $data['service_status'] == 1 ? 0 : 1 }}">{{ $data['service_status'] == 1 ? 'Deactivate' : 'Activate' }}</option>
                        
                    </select>
                </div>           
            <button type="submit" class="btn btn-primary">Update Terms of Service</button>
        </form>
    </div>
</div>
@endsection