@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-12 dashContent">
        @include('partials.message')
        <div class="page-heading ">
            <h2>Edit Profile for - {{ $properties['first_name'].' '.$properties['last_name'] }}</h2>
        </div>
        <hr />
        <form action="{{ route('profile_update') }}" method="POST" class="row">
            <div class="col-md-8">
                <input name="user_id" type="hidden" value="{{ $properties['id'] }}">
                @method('PUT')
                @csrf
                <fieldset disabled>
                <div class="form-group col-md-10">
                    <label for="userIdInput">User ID</label>
                    <input name="user_id" type="text" class="form-control" id="userIdInput" value="{{ $properties['id'] }}" />
                </div>
                </fieldset>
                
                <div class="form-group col-md-10">
                    <label for="emailInput">Email</label>
                    <input name="email" type="email" class="form-control" id="emailInput" value="{{ $properties['email'] }}"/>
                </div>
                
                <div class="form-group col-md-10">
                    <label for="passwordInput">Password</label>
                    <input name="password" type="password" class="form-control" id="passwordInput" />
                </div>
                <div class="form-group col-md-10">
                    <label for="firstNameInput">First Name</label>
                    <input name="first_name" type="text" class="form-control" id="firstNameInput" value="{{ $properties['first_name'] }}"/>
                </div>
                <div class="form-group col-md-10">
                    <label for="lastNameInput">Last Name</label>
                    <input name="last_name" type="text" class="form-control" id="lastNameInput" value="{{ $properties['last_name'] }}"/>
                </div>
                <div class="form-group col-md-10">
                    <label for="residentAddressInput">Resident Address</label>
                    <input name="resident_address" type="text" class="form-control" id="residentAddressInput" value="{{ $properties['resident_address'] }}"/>
                </div>
                <div class="form-group col-md-10">
                    <label for="workAddressInput">Work Address</label>
                    <input name="work_address" type="text" class="form-control" id="workAddressInput" value="{{ $properties['work_address'] }}"/>
                </div>
                
                <div class="form-group col-md-10">
                    <label for="cellphoneInput">Cellphone</label>
                    <input name="cellphone" type="text" class="form-control" id="cellphoneInput" value="{{ $properties['cellphone'] }}"/>
                </div>
                <div class="col-md-10">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection