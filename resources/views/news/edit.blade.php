@extends('master')
@section('page')
@include('partials.message')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            <div class="page-heading">
                <h2>Update News Number: {{ $news['id'] }}</h2>
            </div>
            <form id="newsCreate" action="{{ route('news_update') }}" method="POST">
            <input name="news_id" type="hidden" value="{{ $news['id'] }}">
            <input name="oid" type="hidden" value="{{ $oid }}">
            @method('PUT')
            @csrf
                <div class="form-group">
                    <label for="titleInput">Title</label>
                    <input name="title" type="text" class="form-control" id="titleInput" value="{{ $metadata['title'] }}" />
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <textarea name="description" rows="2" maxlength="250" class="form-control" id="descriptionInput">{{ $metadata['description'] }}</textarea>
                </div>
                <div class="form-group">
                    <label for="contentInput">Body</label>
                    <textarea name="content" rows="15" maxlength="10000" class="form-control" id="contentInput">{{ $news['content'] }}</textarea>
                </div>
                <div class="form-group">
                    <label for="videolinkInput">Video Link</label>
                    <input name="videolink" type="url" class="form-control" id="videolinkInput" value="{{ $news['videolink'] }}" />
                </div>
                <div class="form-group">
                    <label for="newslinkInput">News Link</label>
                    <input name="newslink" type="url" class="form-control" id="newslinkInput" value="{{ $news['newslink'] }}" />
                </div>
                <div class="form-group">
                    <label for="categoryOption">Category</label>
                    <select name="category_id" class="form-control" id="categoryOption">
                        <option value="">.......Current Category: {{$categories[$news['category_id']]}}.......</option>
                        @foreach($categories as $key => $name)
                            @if($news['category_id'] != $key)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <button id="newsSubmit" type="submit" class="btn btn-primary" >Update News</button>
            </form>
        </div>
    </div>
@endsection