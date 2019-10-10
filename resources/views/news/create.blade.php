@extends('master')
@section('page')
@include('partials.message')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            <div class="page-heading">
                <h2>Create News</h2>
            </div>
            <form id="newsCreate" action="{{ route('news_store') }}" method="POST">
            @csrf
                <div class="form-group">
                    <label for="titleInput">Title *</label>
                    <input name="title" type="text" class="form-control" id="titleInput" required />
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <textarea name="description" rows="2" maxlength="250" class="form-control" id="descriptionInput"></textarea>
                </div>
                <div class="form-group">
                    <label for="contentInput">Body *</label>
                    <textarea name="content" rows="15" maxlength="10000" class="form-control" id="contentInput" required></textarea>
                </div>
                <div class="form-group">
                    <label for="videolinkInput">Video Link</label>
                    <input name="videolink" type="url" class="form-control" id="videolinkInput" />
                </div>
                <div class="form-group">
                    <label for="newslinkInput">News Link</label>
                    <input name="newslink" type="url" class="form-control" id="newslinkInput" />
                </div>
                <div class="form-group">
                    <label for="categoryOption">Category *</label>
                    <select name="category_id" class="form-control" id="categoryOption" required>
                        <option value="">...Please select a category...</option>
                        @foreach($categories as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="stateOption">Published State *</label>
                    <select name="published_status" class="form-control" id="stateOption" required>
                        <option value="">...Please select a state...</option>
                        @foreach($status as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="newsSubmit" type="submit" class="btn btn-primary" >Create News</button>
            </form>
        </div>
    </div>
@endsection