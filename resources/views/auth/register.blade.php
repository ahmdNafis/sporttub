@extends('master')

@section('page')
<div class="container loginField">
    <div class="row justify-content-center">
        <div class="col-md-6">
        
            <div class="card">

                <div class="card-header pg-title"><h3>Register</h3></div>
                
                <div class="row social-btns">
                    <span class="col-md-8 offset-md-2">
                        <a href="{{ route('facebook_login') }}" ><img src="{{ asset('storage/uploadedFile/social_icons/fb.png') }}" style="width:38px;height:38px;background-color:white;" alt="facebook"></a>
                        <a style="margin-left:15px;margin-right:15px;" href="{{ route('google_login') }}"><img src="{{ asset('storage/uploadedFile/social_icons/google.png') }}" style="height:64px;width:42px;background-color:white;" alt="google"></a>
                        <a href="{{ route('twitter_login') }}"><img src="{{ asset('storage/uploadedFile/social_icons/twitter.png') }}" style="width:38px;height:38px;background-color:black;" alt="twitter"></a>
                    </span>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required autocomplete="first_Name" autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-2">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required autocomplete="last_Name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

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
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            

                            <div class="col-md-8 offset-md-2">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password">
                            </div>
                        </div>

                        <div class="form-group row" style="margin-bottom:40px;">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-block btn-login">
                                    {{ __('Register') }}
                                </button>

                            </div>
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
