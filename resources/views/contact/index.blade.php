@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10 dashContent">
        @include('partials.message')
            <div class="page-heading">
                <h2>Contacted List</h2>
            </div>
            <table class="table">
                <thead>
                    <tr>
                       @foreach($columns as $name) 
                            @if($name != 'id')
                            <th>{{ ucfirst(implode(' ', explode('_', $name))) }}</th>
                            @endif
                       @endforeach
                    </tr>
                </thead>
                <tbody id="catIndexBody">
                    <?php 
                        $id = null;
                    ?>
                    @foreach($data as $num => $arr)
                        <tr>
                        @foreach($arr as $col => $val)  
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
                                    @can('view', App\Contact::class)<a class="dropdown-item" href="{{ route('contact_show', ['id' => $id]) }}">Show</a>@endcan
                                    @can('delete', App\Contact::class)<a class="dropdown-item" href="{{ route('contact_destroy', ['id' => $id]) }}">Remove</a>@endcan
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