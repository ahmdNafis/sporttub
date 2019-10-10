@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            @include('partials.message')
            <div class="page-heading">
                <h2>Type
                @can('create',App\Type::class)
                <span><a class="btn btn-warning" href="{{ route('type_create') }}">New</a>
                @endcan
                </h2>
            </div>
            <table class="table">
                <thead>
                    <tr>
                       @foreach($columns as $name) 
                            <th>{{ ucfirst(implode(' ', explode('_', $name))) }}</th>
                       @endforeach
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $current_state = '';
                        $id = null;
                    ?>
                    @foreach($data as $num => $arr)
                        <tr>
                        @foreach($arr as $col => $val)
                            @if($col == 'id')
                            <?php $id = $val; ?>
                            @else
                                @if($col == 'status')
                                <?php $current_state = $val; ?>
                                @endif
                            <td>{{ $val }}</td>
                            @endif
                        @endforeach
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                    @can('update', App\Type::class)<a class="dropdown-item" href="{{ route('type_edit', ['type_id' => $id]) }}">Edit</a>@endcan
                                    @can('delete', App\Type::class)<a class="dropdown-item" href="{{ route('type_remove', ['type_id' => $id]) }}">Remove</a>@endcan
                                    @can('update', App\Type::class)<a class="dropdown-item" href="{{ route('type_state', ['type_id' => $id, 'current_state' => $current_state]) }}">{{ $current_state == 'Active' ? 'Deactivate' : 'Activate' }}</a>@endcan
                                    </ul>
                                </div>    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection