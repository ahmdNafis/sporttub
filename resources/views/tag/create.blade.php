@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-6">
            @include('partials.message')
            <div class="page-heading">
                <h2>Create Tag</h2>
            </div>
            <form id="tagNew" action="{{ route('tag_store') }}" method="POST">
            @csrf
                <div class="form-group">
                    <label for="tagNameInput">Tag Name *</label>
                    <input name="tag_name" type="text" class="form-control" id="tagNameInput" required />
                </div>
                               
                <div class="form-group">
                    <label for="categoryOption">Category State *</label>
                    <select name="category_id" class="form-control" id="categoryOption" required>
                        <option value="">...Please select a category...</option>
                        @foreach($categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>               
                <div class="form-group">
                    <label for="statusOption">Tag State *</label>
                    <select name="tag_status" class="form-control" id="statusOption" required>
                        <option value="">...Please select a state...</option>
                        @foreach($status as $state)
                            <option value="{{ $state == 'Activate' ? 1 : 0 }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>                

                <button id="tagSubmit" type="submit" class="btn btn-primary">Create Tag</button>
            </form>
        </div>
    </div>
@endsection