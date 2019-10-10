@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            @include('partials.message')
            <div class="page-heading">
                <h2>Comment</h2>
            </div>
            <?php
                array_push($columns, 'Action')
            ?>
            <table class="table">
                <thead>
                    <tr>
                       @foreach($columns as $name) 
                            @if($name != 'user_id' && $name != 'id')
                            <th>{{ ucfirst(implode(' ', explode('_', $name))) }}</th>
                            @endif
                       @endforeach
                    </tr>
                </thead>
                <tbody >
                    <?php 
                        $current_flag = '';
                        $id = null;
                    ?>
                    @foreach($data as $num => $arr)
                        <tr>
                        @foreach($arr as $col => $val)
                            @if($col != 'user_id')
                                @if($col != 'id')
                                    <td>{{ $val }}</td>
                                    @if($col == 'flag')
                                        <?php $current_flag = $val; ?>
                                    @endif
                                @else 
                                <?php $id = $val; ?>
                                @endif
                            @endif
                        @endforeach
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        @if($current_flag == 'pending')
                                            @can('update', App\Comment::class)<a class="dropdown-item" href="{{ route('comment_flag', ['comment_id' => $id, 'selected_flag' => 'approved']) }}">Approve</a>@endcan
                                            @can('update', App\Comment::class)<a class="dropdown-item" href="{{ route('comment_flag', ['comment_id' => $id, 'selected_flag' => 'declined']) }}">Decline</a>@endcan
                                        @elseif($current_flag == 'approved')
                                            @can('update', App\Comment::class)<a class="dropdown-item" href="{{ route('comment_flag', ['comment_id' => $id, 'selected_flag' => 'declined']) }}">Decline</a>@endcan
                                        @else
                                            @can('update', App\Comment::class)<a class="dropdown-item" href="{{ route('comment_flag', ['comment_id' => $id, 'selected_flag' => 'approved']) }}">Approve</a>@endcan
                                        @endif
                                        @if(Gate::allows('edit-comment', (object)$data[$num]))
                                            <a class="dropdown-item" href="{{ route('comment_edit', ['comment_id' => $data[$num]['id']]) }}">Edit</a>
                                        @endif
                                        @can('delete', App\Comment::class)<a class="dropdown-item" href="{{ route('comment_remove', ['comment_id' => $data[$num]['id']]) }}">Remove</a>@endcan
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