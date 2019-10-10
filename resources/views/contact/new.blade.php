@extends('master')
@section('page')
    @if(!Auth::check())
    
    <div class="row rightContent">
        
        <div class="col-md-9">
        @include('partials.message')
            <div class="page-heading">
                <h2>Contact Us</h2>
            </div>
            <hr />
            <form id="contactNew" action="{{ route('contact_store') }}" method="POST">
            @csrf
                <div class="form-group">
                    <label for="nameInput">Name *</label>
                    <input name="name" type="text" class="form-control" id="nameInput" required />
                </div> 
                <div class="form-group">
                    <label for="emailInput">Email *</label>
                    <input name="email" type="email" class="form-control" id="emailInput" required />
                </div>
                <div class="form-group">
                    <label for="contentInput">Content *</label>
                    <textarea name="content" rows="6" maxlength="400" class="form-control" id="contentInput" required></textarea>
                </div>
                               
                <button id="contactSubmit" type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    @endif
@endsection