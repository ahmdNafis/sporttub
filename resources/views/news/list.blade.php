@extends('master')
@section('page')
    <div class="row rightContent">
    @include('partials.sidebar')
        <div class="col-md-11">
            @include('partials.message')
            <form action="{{ route('news_remove_multiple') }}" method="POST">
            @method('PUT')
            @csrf
            <div class="page-heading">
                <h2>News
                @can('create',App\News::class)
                <span><a class="btn btn-warning" href="{{ route('news_create') }}">New</a>
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
                <tbody>
                    <?php 
                        $oid = null;
                        $current_state = null;
                    ?>
                    @foreach($data as $key => $arr)
                    <tr>
                        @foreach($arr as $col => $content)
                            @if($col == 'object_id')
                            <td><input name="news_oid[]" type="checkbox" class="form-check-input checkInput" id="newsIdOption" value="{{ $content }}"></td>
                            @endif
                            @if($col != 'object_id')
                            <td>{{ $content }}</td>
                                @if($col == 'published_status')
                                    <?php $current_state = $content ?>
                                @endif
                            @else
                            <?php $oid = $content; ?>
                            @endif
                        @endforeach
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                @can('view', App\News::class)<a class="dropdown-item" href="{{ route('news_details', ['oid' => $oid]) }}">Show</a>@endcan
                                @can('update', App\News::class)<a class="dropdown-item" href="{{ route('news_edit', ['oid' => $oid]) }}">Edit</a>@endcan
                                @can('delete', App\News::class)<a class="dropdown-item" href="{{ route('news_remove', ['oid' => $oid]) }}">Remove</a>@endcan
                                @can('update', App\News::class)<a class="dropdown-item" href="{{ route('news_state', ['oid' => $oid, 'current_state' => $current_state]) }}">{{ $current_state == 'Published' ? 'Unpublish' : 'Publish' }}</a>@endcan
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