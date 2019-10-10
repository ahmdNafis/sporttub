@extends('master')
@section('page')
    @if(Auth::check())
    @include('partials.sidebar')
    <div class="row profile">
        <?php
            $ignore = ['avatar', 'avatar_original', 'id'];
        ?>
        <div class="col-md-7">
        @include('partials.message')
            <?php $i=0; ?>
                
                <ul>
                <img alt="Profile Image" class="img-thumbnail profile-img" src="{{ !empty($user['avatar']) ? $user['avatar'] : 'https://cdn4.vectorstock.com/i/1000x1000/23/18/male-avatar-icon-flat-vector-19152318.jpg'  }}" />
                    @foreach($user as $col => $val)
                        @if(!in_array($col, $ignore))
                            @if($col == 'created_at')
                            <li><p><span class="column">{{ ucfirst(implode(' ', explode('_', $col))).' :' }}</span><span class="value">{{ date('d-m-y', strtotime($val)) }}</span></p></li>
                            @else 
                            <li><p><span class="column">{{ ucfirst(implode(' ', explode('_', $col))).' :' }}</span><span class="value">{{ !empty($val) ? $val : 'Not Present' }}</span></p></li>
                            @endif
                        @endif
                    @endforeach
                    <a href="{{ route('profile_edit', ['user_id' => $user['id']]) }}" class="btn btn-primary btn-block btn-edit-profile">Edit Profile</a>
                </ul> 
        </div>
    </div>
    @endif
@endsection