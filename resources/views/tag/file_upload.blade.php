@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-6">
            @include('partials.message')
            <div class="page-heading">
                <h2>Upload Tags</h2>
            </div>
            <form id="tagFile" action="{{ route('tag_upload') }}" method="POST" enctype="multipart/form-data">
            @csrf 
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" name="uploaded_file" id="tagFile" class="custom-file-input">
                        <label class="custom-file-label" for="tagFile">Choose file with .xlsx extension</label>
                    </div>
                </div>                

                <button id="tagFileSubmit" type="submit" class="btn btn-primary">Upload File</button>
            </form>
        </div>
    </div>
@endsection