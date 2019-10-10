@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-8">
            @include('partials.message')
            <div class="page-heading">
                <h2>Create News Link</h2>
            </div>
            <hr />
        <form action="{{ route('create_link') }}" method="POST">
            @csrf
                <div class="form-group">
                    <label for="rss_link_input">Link</label>
                    <input name="rss_link" type="text" class="form-control" id="rss_link_input" required />
                </div>
                <div class="form-group">
                    <label for="category_list">Category</label>
                    <select name="category_id" class="form-control" id="category_list" required>
                        <option value="">...Please select a Category...</option>
                        @foreach($categories as $id => $value)
                            <option value="{{ $id }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Link</button>
            </form>
        </div>
    </div>
@endsection