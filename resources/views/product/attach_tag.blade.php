@extends('master')
@section('page')
@include('partials.message')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-6">
            <div class="page-heading ">
                <h2>Tagging Product</h2>
            </div>
            <hr />
            <form id="productAttachNew" action="{{ route('product_tag_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <input type="hidden" name="product_id" value="{{ $product_id }}">
                <div class="form-group">
                    <label for="tagOption">Tags</label>
                    <select name="tag_id[]" class="form-control" id="tagOption" multiple required>
                        <option value="">{{ count($tags->toArray()) > 0 ? '.......Please select one or more tags.......' : '.......Tags don\'t exist for this category.......' }}</option>
                        @foreach($tags as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>            

                <button id="productAttachSubmit" type="submit" class="btn btn-primary">Attach</button>
            </form>
        </div>
    </div>
@endsection