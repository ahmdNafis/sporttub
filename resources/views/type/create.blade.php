@extends('master')
@section('page')
@include('partials.message')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-8">
            <div class="page-heading">
                <h2>Create Type</h2>
            </div>
            <hr />
            <form id="typeNew" action="/type/store" method="POST">
            @csrf
                <div class="form-group">
                    <label for="typeNameInput">type Name *</label>
                    <input name="type_name" type="text" class="form-control" id="typeNameInput" required />
                </div>
                <div class="form-group">
                    <label for="statusOption">Category State *</label>
                    <select name="type_status" class="form-control" id="statusOption" required>
                        <option value="">...Please select a state...</option>
                        @foreach($status as $state)
                            <option value="{{ $state == 'Active' ? 1 : 0 }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="typeSubmit" type="submit" class="btn btn-primary">Create Type</button>
            </form>
        </div>
    </div>
@endsection