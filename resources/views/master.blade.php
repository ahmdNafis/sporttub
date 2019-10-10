<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
        crossorigin="anonymous">
        <title>Sporttub | Home of American Sports</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/navTop.css') }}" rel="stylesheet">
        @if(Route::currentRouteName() == 'login')
        
        @endif
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        
        <link href="{{ asset('css/news_show.css') }}" rel="stylesheet">
        
        <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    </head>
    <body @if(Route::currentRouteName() == 'login') style="background-image: radial-gradient(#4B77BE 10%, #003171 90%); background-repeat: no-repeat; background-attachment: fixed; height: 100%; margin:0;" @endif>
        @include('partials.header')
        <div class="container-fluid" >
            @yield('page')
        </div>
        @if(!Auth::check() && Route::currentRouteName() != 'login')
            @include('partials.footer')
        @endif
        
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
            $(function() {
                $('#alertSection').hide();
                let typingTimer;
                $('#categoryNameInput').on('keyup', (e) => {
                    clearTimeout(typingTimer);
                    console.log(e.target.value);
                    let typing = completedTyping(e.target.value);
                    typingTimer = setTimeout(typing, 10000);
                });
                $('#statusOption').on('click', (e) => {
                    if($('#statusOption').val() != '') $('#categorySubmit').removeAttr('disabled');
                    else $('#categorySubmit').prop('disabled', true);
                });
                
            });
            function completedTyping(value) {
                $.get('/category/check_presence/'+value, value, (data) => {
                    if(data != 'Activate') {
                        $('#data span').text(data);
                        $('#categorySubmit').prop('disabled', true);
                        $('#alertSection').show();
                    } else {
                        $('#alertSection').hide();
                        if($('#statusOption').removeAttr('disabled').val() != '') $('#categorySubmit').removeAttr('disabled');
                    }
                });
            }
        </script>

    </body>
</html>
