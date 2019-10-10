@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-8">
        @include('partials.message')
        <div class="page-heading">
            <h2>Update Tag Number: {{$tag['id']}}</h2>
        </div>
        <hr />
        <form action="{{ route('tag_update') }}" method="POST">
            <input name="tag_id" type="hidden" value="{{ $tag['id'] }}">
            @method('PUT')
            @csrf
            <fieldset disabled>
            <div class="form-group">
                <label for="tagIdInput">Tag ID</label>
                <input name="tag_id" type="text" class="form-control" id="tagIdInput" placeholder="{{ $tag['id'] }}" />
            </div>
            </fieldset>
            <div class="form-group">
                <label for="tagNameInput">Tag Name</label>
                <input name="tag_name" type="text" class="form-control" id="tagNameInput" value="{{ $tag['tag_name'] }}"/>
            </div>
            <button type="submit" class="btn btn-primary">Update Tag</button>
        </form>
    </div>
</div>
@endsection