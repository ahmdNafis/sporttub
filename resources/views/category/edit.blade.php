@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-9 dashContent">
        @include('partials.message')
        <div class="page-heading ">
            <h2>Edit Category - {{ $properties['category_name'] }}</h2>
        </div>
        <form action="{{ route('category_update') }}" method="POST" class="row">
            <div class="col-md-12">
                <input name="_id" type="hidden" value="{{ $properties['id'] }}">
                @method('PUT')
                @csrf
                <fieldset disabled>
                <div class="form-group col-md-10">
                    <label for="categoryNameInput">Category ID</label>
                    <input name="category_id" type="text" class="form-control" id="categoryNameInput" placeholder="{{ $properties['id'] }}" />
                </div>
                </fieldset>
                <div class="form-group col-md-10">
                    <label for="categoryNameInput">Category Name</label>
                    <input name="category_name" type="text" class="form-control" id="categoryNameInput" placeholder="{{ $properties['category_name'] }}"/>
                </div>
                <div class="form-group col-md-10">
                    <label for="descriptionInput">Description</label>
                    <textarea name="description" rows="6" maxlength="250" class="form-control" 
                    id="descriptionInput">{{ $properties['description'] }}</textarea>
                </div>
                <fieldset disabled>
                <div class="form-group col-md-10">
                    <label for="statusOption">Category State</label>
                    <input name="category_status" type="text" class="form-control" id="statusOption" placeholder="{{ $properties['category_status'] == 1 ? 'Active' : 'Inactive' }}" />
                </div>
                </fieldset>
                <div class="col-md-10">
                <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection