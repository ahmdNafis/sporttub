@extends('master')
@section('page')
@include('partials.message')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            <div class="page-heading">
                <h2>Create Role</h2>
            </div>
            <hr />
            <form id="roleNew" action="{{ route('role_store') }}" method="POST">
            @csrf
                <div class="form-group">
                    <label for="roleNameInput">Role Name *</label>
                    <input name="role_name" type="text" class="form-control" id="roleNameInput" required />
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <textarea name="description" rows="6" maxlength="250" class="form-control" id="descriptionInput"></textarea>
                </div>
                <div class="form-group">
                    <label for="permissionOption">Permission *</label>
                    @foreach($permissions as $name)
                    <div class="form-check">
                        <input name="permission[]" type="checkbox" class="form-check-input" id="permissionOption" value="{{ $name }}">
                        <label class="form-check-label" for="permissionOption">{{ $name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <label for="accessOption">Accessibility *</label>
                    <?php $i=0; ?>
                    @foreach($controllers as $name)
                    <div class="custom-control custom-radio">
                        <input name="accessibility" type="radio" class="custom-control-input" id="{{'accessOption'.$i}}" value="{{ $name }}">
                        <label class="custom-control-label" for="{{'accessOption'.$i++}}">{{ $name }}</label>
                    </div>
                    @endforeach
                </div>                        
                <div class="form-group">
                    <label for="statusOption">Role State *</label>
                    <select name="role_status" class="form-control" id="statusOption" required>
                        <option value="">...Please select a state...</option>
                        @foreach($status as $state)
                            <option value="{{ $state == 'Activate' ? 1 : 0 }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>                

                <button id="roleSubmit" type="submit" class="btn btn-primary">Create Role</button>
            </form>
        </div>
    </div>
@endsection