@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
        @include('partials.message')
            <div class="page-heading">
                <h2>User</h2>
            </div>
            <table class="table">
                <thead>
                    <tr>
                       @foreach($columns as $name) 
                            <th>{{ ucfirst(implode(' ', explode('_', $name))) }}</th>
                       @endforeach
                    </tr>
                </thead>
                <tbody >
                    <?php 
                        $current_state = '';
                        $id = null;
                        $roles = [];
                    ?>
                    @foreach($data as $num => $arr)
                        <tr>
                        @foreach($arr as $col => $val)
                            @if($col != 'id')
                                @if($col == 'roles')
                                    <?php $roles = $val != 'None' ? $val : []; ?>
                                    <td>{{ $val != 'None' ? implode(' | ', $roles) : $val }}</td> 
                                @else 
                                <td>{{ $val }}</td>
                                @endif
                                
                                @if($col == 'status')
                                    <?php $current_state = $val; ?>
                                @endif
                            @else 
                            <?php $id = $val; ?>
                            @endif
                        @endforeach
                            <td>
                                <div class="btn-group" role="group" aria-label="Options">
                                    @can('update', App\User::class)
                                    <form class="form-inline" action="{{ route('user_role') }}" method="POST">
                                        @csrf
                                        <?php $activity = count($roles) < 2 ? 'attach' : 'detach' ?>
                                        <input type="hidden" name="role_activity" value="{{ $activity }}">
                                        <input type="hidden" name="user_id" value="{{ $id }}" >
                                        <select name="role_id" class="form-control">
                                            <option value="">...Please select a role...</option>
                                            <?php $iter_roles = count($roles) < 2 ? $cont_roles : $roles ?>
                                            @foreach($iter_roles as $rl_id => $role)
                                                @if($activity == 'attach')
                                                    @if(!in_array($role, $roles))
                                                    <option value="{{ $rl_id }}">{{ $role }}</option>
                                                    @endif
                                                @else
                                                <option value="{{ $rl_id }}">{{ $role }}</option>
                                                @endif
                                            @endforeach
                                        </select> <button type="submit" class="btn btn-primary">{{ count($roles) < 2 ? 'attach' : 'detach' }}</button>
                                    </form>
                                    @endcan
                                    <div class="dropdown ">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            @can('update', App\User::class)<a class="dropdown-item" href="{{ route('user_edit', ['user_id' => $id]) }}">Edit</a>@endcan                                    
                                            @can('delete', App\User::class)<a class="dropdown-item disabled" href="{{ route('user_remove', ['user_id' => $id]) }}">Remove</a>@endcan
                                            @can('update', App\User::class)<a class="dropdown-item" href="{{ route('user_state', ['user_id' => $id, 'current_state' => $current_state]) }}">{{ $current_state == 'Active' ? 'Deactivate' : 'Activate' }}</a>@endcan
                                        </ul>
                                    </div>
                                </div>    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection