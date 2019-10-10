@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
        @include('partials.message')
            <div class="page-heading">
                <h2>Related News</h2>
            </div>
            <table class="table">
                <?php 
                    $current_state = null;
                    $oid = null;
                    array_push($columns, 'Action');
                ?>
                <thead>
                    <tr>
                        @foreach($columns as $name)
                            <th>{{ ucfirst(implode(' ', explode('_', $name))) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $arr)
                    <tr>
                        @foreach($arr as $col => $val)
                        @if($col == 'oid')
                        <?php 
                            $oid = $val;
                        ?>
                        @else
                        <td>{{ $val }}</td>
                            @if($col == 'state')
                                <?php $current_state = $val; ?>
                            @endif
                        @endif
                        @endforeach
                        
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                <ul class="dropdown-menu">
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
        </div>
    </div>
@endsection