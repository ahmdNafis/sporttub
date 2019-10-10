@extends('master')
@section('page')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            @include('partials.message')
            <div class="page-heading">
                <h2>Product
                @can('create',App\Product::class)
                <span><a class="btn btn-warning" href="{{ route('product_create') }}">New</a>
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
                            @if($col != 'id')
                                @if($col == 'image_link' && $val != 'None')
                                <td><img src="{{ asset('storage/uploadedFile/'.$val) }}" style="width:60px;height:60px" class="img-thumbnail" alt="product"></td>
                                @endif
                                @if($col != 'image_link')
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
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                        @can('update', App\Product::class)<a class="dropdown-item" href="{{ route('product_attach_form', ['product_id' => $id]) }}">Attach Tag</a>@endcan
                                        @if($data[$num]['tags'] > 0)
                                            @can('delete', App\Product::class)<a class="dropdown-item" href="{{ route('product_tag_remove', ['product_id' => $id]) }}">Remove Tag</a>@endcan
                                        @endif
                                        @can('update', App\Product::class)<a class="dropdown-item" href="{{ route('product_edit', ['product_id' => $id]) }}">Edit</a>@endcan
                                        @can('delete', App\Product::class)<a class="dropdown-item" data-toggle="modal" data-target="#removeModal">Remove</a>@endcan
                                        <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModelLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                            <div class="modal-body">
                                                <h5><span>Are you sure you want to remove selected product?</span></h5>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <a href="{{ route('product_remove', ['product_id' => $id]) }}" class="btn btn-primary">Confirm Deletion</a>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                        @can('update', App\Product::class)<a class="dropdown-item" href="{{ route('product_state', ['product_id' => $id, 'current_state' => $current_state]) }}">{{ $current_state == 'Active' ? 'Deactivate' : 'Activate' }}</a>@endcan
                                    </ul>
                                </div>    
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
   
@endsection