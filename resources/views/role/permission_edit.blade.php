@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-8">
        @include('partials.message')
        <div class="page-heading">
            <h2>Update Permission</h2>
        </div>
        <hr />
        <form action="{{ route('role_permission_update') }}" method="POST">
            <input name="role_id" type="hidden" value="{{ $role_id }}">
            @method('PUT')
            @csrf
            <fieldset disabled>
            <div class="form-group">
                <label for="roleIdInput">Role ID</label>
                <input name="role_id" type="text" class="form-control" id="roleIdInput" placeholder="{{ $role_id }}" />
            </div>
            </fieldset>
            <div class="form-group">
                    <label for="permissionOption">Permission</label>
                    @foreach($permissions as $name)
                    <div class="form-check">
                        <input name="permission[]" type="checkbox" class="form-check-input" id="permissionOption" value="{{ $name }}">
                        <label class="form-check-label" for="permissionOption">{{ $name }}</label>
                    </div>
                    @endforeach
                </div>
            <button type="submit" class="btn btn-primary">Remove Permission</button>
        </form>
    </div>
</div>
@endsection