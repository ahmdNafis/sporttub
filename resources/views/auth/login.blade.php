@extends('master')

@section('page')
<div class="container loginField">
    <div class="row justify-content-center">
        <div class="col-md-6">
        
            <div class="card">

                <div class="card-header pg-title"><h3>Login</h3></div>
                
                <div class="row social-btns">
                    <span class="col-md-8 offset-md-2">
                        <a href="{{ route('facebook_login') }}" ><img src="{{ asset('storage/uploadedFile/social_icons/fb.png') }}" style="width:38px;height:38px;background-color:white;" alt="facebook"></a>
                        <a style="margin-left:15px;margin-right:15px;" href="{{ route('google_login') }}"><img src="{{ asset('storage/uploadedFile/social_icons/google.png') }}" style="height:64px;width:42px;background-color:white;" alt="google"></a>
                        <a href="{{ route('twitter_login') }}"><img src="{{ asset('storage/uploadedFile/social_icons/twitter.png') }}" style="width:38px;height:38px;background-color:black;" alt="twitter"></a>
                    </span>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            

                            <div class="col-md-8 offset-md-2">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-8 offset-md-2">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-block btn-login">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link offset-md-2" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                
                                <a class="btn btn-link ml-3" href="{{ route('home') }}">Go Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
