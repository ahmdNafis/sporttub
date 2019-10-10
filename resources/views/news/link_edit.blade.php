@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-8">
            @include('partials.message')
            <div class="page-heading">
                <h2>Update News Link</h2>
            </div>
            <hr />
            <form id="newsCreate" action="{{ route('link_update') }}" method="POST">
            <input name="oid" type="hidden" value="{{ $data['oid'] }}">
            @method('PUT')
            @csrf
                <div class="form-group">
                    <label for="linkInput">Link</label>
                    <input name="rss_link" type="text" class="form-control" id="linkInput" value="{{ $data['rss_link'] }}" />
                </div>
                <div class="form-group">
                    <label for="hostInput">Host Name</label>
                    <input name="host_name" type="text" class="form-control" id="hostInput" value="{{ $data['host_name'] }}" />
                </div>
                <div class="form-group">
                    <label for="categoryOption">Category</label>
                    <select name="category_id" class="form-control" id="categoryOption">
                        <option value="">.......Current Category: {{$category[$data['category_id']]}}.......</option>
                        @foreach($category as $key => $name)
                            @if($data['category_id'] != $key)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <button id="newsSubmit" type="submit" class="btn btn-primary" >Update Link</button>
            </form>
        </div>
    </div>
@endsection