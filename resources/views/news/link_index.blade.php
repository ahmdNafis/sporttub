@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
        @include('partials.message')
            <div class="page-heading">
                <h2>News Links
                @can('create',App\News::class)
                <span><a class="btn btn-warning" href="{{ route('news_link') }}">New</a>
                @endcan
                </h2>
            </div>
            <table class="table">
                <?php 
                    $oid = null;
                    array_push($columns, 'Action');
                ?>
                <thead>
                    <tr>
                        @foreach($columns as $name)
                            @if($name != 'oid')
                            <th>{{ ucfirst(implode(' ', explode('_', $name))) }}</th>
                            @endif
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
                        @elseif($col == 'rss_link')
                        <td>{{ urldecode($val) }}</td>
                        @else
                        <td>{{ $val }}</td>
                        @endif
                        @endforeach
                        
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    @can('update', App\News::class)<a class="dropdown-item" href="{{ route('link_edit', ['oid' => $oid]) }}">Edit</a>@endcan
                                    @can('delete', App\News::class)<a class="dropdown-item" href="{{ route('link_destroy', ['oid' => $oid]) }}">Remove</a>@endcan
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