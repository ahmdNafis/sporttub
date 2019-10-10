@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-10">
        @include('partials.message')
        <div class="page-heading">
            <h2>Update Role Number: {{$role['id']}}</h2>
        </div>
        <hr />
        <form action="{{ route('role_update') }}" method="POST">
            <input name="role_id" type="hidden" value="{{ $role['id'] }}">
            @method('PUT')
            @csrf
            <fieldset disabled>
            <div class="form-group">
                <label for="roleIdInput">Role ID</label>
                <input name="role_id" type="text" class="form-control" id="roleIdInput" placeholder="{{ $role['id'] }}" />
            </div>
            </fieldset>
            <div class="form-group">
                <label for="roleNameInput">Role Name</label>
                <input name="role_name" type="text" class="form-control" id="roleNameInput" placeholder="{{ $role['role_name'] }}"/>
            </div>
            <div class="form-group">
                <label for="descriptionInput">Description</label>
                <textarea name="description" rows="6" maxlength="250" class="form-control" 
                id="descriptionInput">{{ $role['description'] }}</textarea>
            </div>
            <div class="form-group">
                    <label for="permissionOption">Permission</label>
                    <p>Existing Permission: <span>{{ $perm_str }}</span></p>
                    @foreach($permissions as $name)
                    @if(!in_array($name, $existing_perm))
                    <div class="form-check">
                        <input name="permission[]" type="checkbox" class="form-check-input" id="permissionOption" value="{{ $name }}">
                        <label class="form-check-label" for="permissionOption">{{ $name }}</label>
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="accessOption">Accessibility</label>
                    <?php $i=0; ?>
                    <p>Existing Access: <span>{{ $access['controller_name'] }}</span></p>
                    @foreach($controllers as $name)
                    @if($name != $access['controller_name'])
                    <div class="custom-control custom-radio">
                        <input name="accessibility" type="radio" class="custom-control-input" id="{{'accessOption'.$i}}" value="{{ $name }}">
                        <label class="custom-control-label" for="{{'accessOption'.$i++}}">{{ $name }}</label>
                    </div>
                    @endif
                    @endforeach
                </div>    
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</div>
@endsection