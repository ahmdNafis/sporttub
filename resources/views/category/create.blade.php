@extends('master')
@section('page')
@include('partials.message')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            <div class="page-heading">
                <h2>Create Category</h2>
            </div>
            <form id="categoryNew" action="/category/store" method="POST">
            @csrf
                <div class="form-group">
                    <label for="categoryNameInput">Category Name *</label>
                    <input name="category_name" type="text" class="form-control" id="categoryNameInput" required />
                </div>
                <div class="form-group">
                    <label for="dateFromInput">Start Date*</label>
                    <input name="date_from" type="date" class="form-control" id="dateFromInput" required />
                </div>
                <div class="form-group">
                    <label for="dateToInput">End Date*</label>
                    <input name="date_to" type="date" class="form-control" id="dateToInput" required />
                </div>
                <div class="form-group">
                    <label for="intervalOption">Interval *</label>
                    <select name="interval" class="form-control" id="intervalOption" required>
                        <option value="">...Please select an interval...</option>
                        @foreach($intervals as $id => $name)
                            <option value="{{ $id+1 }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <textarea name="description" rows="6" maxlength="250" class="form-control" id="descriptionInput"></textarea>
                </div>
                <div class="form-group">
                    <label for="typeOption">Types *</label>
                    <select name="type_id" class="form-control" id="typeOption" required>
                        <option value="">...Please select a type...</option>
                        @foreach($types as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="statusOption">Category State *</label>
                    <select name="category_status" class="form-control" id="statusOption" required disabled>
                        <option value="">...Please select a state...</option>
                        @foreach($status as $state)
                            <option value="{{ $state == 'Activate' ? 1 : 0 }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>                

                <button id="categorySubmit" type="submit" class="btn btn-primary" disabled>Create Category</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
        <script>
        // this is all it takes to capture it in jQuery
        // you put ready-snippet
       // $(function() {
            //you define socket - you can use IP
            var socket = io('http://127.0.0.1:3000');
            //you capture message data
            socket.on('category-event:.categoryfired', function(data){
                //console.log(data);
                //you append that data to DOM, so user can see it
                $('#data').append(data);
            });
        //});
        
        </script>
@endsection