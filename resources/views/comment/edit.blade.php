@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-8">
        @include('partials.message')
        <div class="page-heading">
            <h2>Edit Comment Number: {{ $properties['id'] }}</h2>
        </div>
        <hr />      
        <form action="{{ route('comment_update') }}" method="POST">
            <input name="_id" type="hidden" value="{{ $properties['id'] }}">
            @method('PUT')
            @csrf
            <fieldset disabled>
            <div class="form-group">
                <label for="commentIDInput">Comment ID</label>
                <input name="comment_id" type="text" class="form-control" id="commentIDInput" placeholder="{{ $properties['id'] }}" />
            </div>
            </fieldset>
            <div class="form-group">
                <label for="contentInput">Content</label>
                <textarea name="content" rows="6" maxlength="250" class="form-control" 
                id="contentInput">{{ $properties['content'] }}</textarea>
            </div>
            <fieldset disabled>
            <div class="form-group">
                <label for="flagOption">Comment State</label>
                <input name="flag" type="text" class="form-control" id="flagOption" placeholder="{{ $properties['flag'] }}" />
            </div>
            </fieldset>
            <button type="submit" class="btn btn-primary">Update Comment</button>
        </form>
    </div>
</div>
@endsection