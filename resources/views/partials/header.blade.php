@if(Route::currentRouteName() != 'login')
<nav class="navbar navbar-expand-lg fixed-top navTop">
  
  <a class="navbar-brand" href="#"><span class="sportLeft">Sport</span><span class="sportRight">tub</span></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#newsList" aria-controls="newsList" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="newsList">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">HOME <span class="sr-only">(current)</span></a>
      </li>
        @if(!Auth::check())
        @foreach($categories as $id => $name)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('news_specific', ['category_name'=> $name]) }}">{{ strtoupper($name) }}</a>
            </li>
        @endforeach
        @else 
            <li class="nav-item">
              <a class="nav-link" href="{{ in_array('Super Admin', Auth::user()->roles()->pluck('role_name')->toArray()) ? route('dashboard') : route('profile_show', ['user_id' => Auth::id()]) }}">DASHBOARD</a>
            </li>
        @endif
    </ul><!--
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>-->
    @if(Auth::check())
      
        <img src="{{ Auth::user()->avatar != '' ? Auth::user()->avatar : 'https://cdn4.vectorstock.com/i/1000x1000/23/18/male-avatar-icon-flat-vector-19152318.jpg' }}" class="rounded" alt="$share_var['user']->first_name.' '.$share_var['user']->last_name" />
        <a href="{{ route('profile_show', ['user_id' => Auth::id()]) }}" class="profileName">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</a>
      
      <a id="btn-logout" href="{{ route('logout') }}" class="btn btn-light">Logout</a>
    @else
      <a id="btn-register" href="/register" class="btn btn-light btn-sm">Sign Up</a>
      <a id="btn-login" href="/login" class="btn btn-light btn-sm">Login</a>
    @endif
  </div>

</nav>
@endif