@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10 dashContent">
        @include('partials.message')
            <div class="page-heading">
                <h2>Category
                @can('create',App\Category::class)
                <span><a class="btn btn-warning" href="{{ route('category_new') }}">New</a></span>
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
                <tbody id="catIndexBody">
                    <?php 
                        $name = ''; 
                        $current_state = '';
                        $id = null;
                    ?>
                    @foreach($data as $num => $arr)
                        <tr>
                        @foreach($arr as $col => $val)
                            <?php    
                                if($col == 'category_name') $name = $val;
                                elseif($col == 'category_status') $current_state = $val;
                            ?>
                            @if($col != 'id')
                            <td>{{ $val }}</td>
                            @else 
                            <?php $id = $val; ?>
                            @endif
                        @endforeach
                            <td>
                                <!--  role="group" aria-label="Options" -->
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                    @can('view', App\Category::class)<a class="dropdown-item" href="{{ route('news_home', ['category_id' => $id]) }}">Related News</a>@endcan
                                    @can('update', App\Category::class)<a class="dropdown-item" href="{{ route('category_edit', ['category_name' => $name]) }}">Edit</a>@endcan
                                    @can('delete', App\Category::class)<a class="dropdown-item" href="{{ route('category_remove', ['category_name' => $name]) }}">Remove</a>@endcan
                                    @can('update', App\Category::class)<a class="dropdown-item" href="{{ route('state_update', ['category_name' => $name, 'current_state' => $current_state]) }}">{{ $current_state == 'Active' ? 'Deactivate' : 'Activate' }}</a>@endcan
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