@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        
        
        <div class="col-md-10">
            <form action="{{ route('role_mass_remove') }}" method="POST">
            @method('PUT')
            @csrf
            @include('partials.message')
            <div class="page-heading">
                <h2>Taxonomy
                @can('create',App\Tag::class)
                <span><a class="btn btn-warning" href="{{ route('tag_create') }}">New</a></span>
                <span><a class="btn btn-warning" href="{{ route('tag_file_create') }}">Upload File</a></span>
                <span><button type="submit" class="btn btn-danger">Bulk Delete</button></span>
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
                <tbody >
                    <?php 
                        $current_state = '';
                        $id = null;
                    ?>
                    @foreach($data as $num => $arr)
                        <tr>
                        @foreach($arr as $col => $val)
                            @if($col == 'id')
                            <td><input name="tag_id[]" type="checkbox" class="form-check-input checkInput" id="tagIdOption" value="{{ $val }}"></td>
                            @endif
                            @if($col != 'id')
                                <td>{{ $val }}</td>
                                @if($col == 'status')
                                    <?php $current_state = $val; ?>
                                @endif
                            @else 
                            <?php $id = $val; ?>
                            @endif
                        @endforeach
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                    @can('update', App\Tag::class)<a class="dropdown-item" href="{{ route('tag_edit', ['tag_id' => $id]) }}">Edit</a>@endcan
                                    @can('delete', App\Tag::class)<a class="dropdown-item" href="{{ route('tag_remove', ['tag_id' => $id]) }}">Remove</a>@endcan
                                    @can('update', App\Tag::class)<a class="dropdown-item" href="{{ route('tag_state', ['tag_id' => $id, 'current_state' => $current_state]) }}">{{ $current_state == 'Active' ? 'Deactivate' : 'Activate' }}</a>@endcan
                                    </ul>
                                </div>    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        </div>
        
    </div>
@endsection