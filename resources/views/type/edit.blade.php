@extends('master')
@section('page')
@if(Auth::check())
@include('partials.sidebar')
<div class="row rightContent">
    <div class="col-md-8">
        @include('partials.message')
        <div class="page-heading ">
            <h2>Edit Type - {{ $properties['type_name'] }}</h2>
        </div>
        <hr />
        <form action="{{ route('type_update') }}" method="POST">
            <input name="type_id" type="hidden" value="{{ $properties['id'] }}">
            @method('PUT')
            @csrf
            <fieldset disabled>
            <div class="form-group">
                <label for="typeIdInput">Type ID</label>
                <input name="type_id" type="text" class="form-control" id="typeIdInput" value="{{ $properties['id'] }}" />
            </div>
            </fieldset>
            <div class="form-group">
                <label for="typeNameInput">Type Name</label>
                <input name="type_name" type="text" class="form-control" id="typeNameInput" value="{{ $properties['type_name'] }}"/>
            </div>
            <fieldset disabled>
            <div class="form-group">
                <label for="statusOption">Type State</label>
                <input name="type_status" type="text" class="form-control" id="statusOption" value="{{ $properties['type_status'] == 1 ? 'Active' : 'Inactive' }}" />
            </div>
            </fieldset>
            <button type="submit" class="btn btn-primary">Update Type</button>
        </form>
    </div>
</div>
@endif
@endsection