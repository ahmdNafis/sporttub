@extends('master')
@section('page')
@include('partials.message')
    <div class="row rightContent">
        @include('partials.sidebar')
        <div class="col-md-10">
            <div class="page-heading">
                <h2>Create Product</h2>
            </div>
            <form id="productNew" action="{{ route('product_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="form-group">
                    <label for="productNameInput">Product Name *</label>
                    <input name="product_name" type="text" class="form-control" id="productNameInput" required />
                </div>
                <div class="form-group">
                    <label for="slugInput">Slug *</label>
                    <input name="slug" type="text" class="form-control" id="slugInput" required />
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <textarea name="description" rows="6" maxlength="250" class="form-control" id="descriptionInput"></textarea>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input name="product_image" type="file" class="form-control" id="imageFileInput" required />
                        <label for="imageFileInput" class="custom-file-label">Choose Image File</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="weightInput">Weight</label>
                    <input name="weight" type="number" step="0.01" class="form-control" id="weightInput" />
                </div>
                <div class="form-group">
                    <label for="weightUnitsInput">Weight Units</label>
                    <input name="weight_units" type="text" class="form-control" id="weightUnitsInput" />
                </div>
                <div class="form-group">
                    <label for="retailPriceInput">Retail Price</label>
                    <input name="retail_price" type="number" step="0.001" class="form-control" id="retailPriceInput" />
                </div>
                <div class="form-group">
                    <label for="wholesalePriceInput">Wholesale Price</label>
                    <input name="wholesale_price" type="number" step="0.001" class="form-control" id="wholesalePriceInput" />
                </div>
                <div class="form-group">
                    <label for="discountInput">Discount</label>
                    <input name="discount" type="number" step="0.01" class="form-control" id="discountInput" />
                </div>
                <div class="form-group">
                    <label for="manufacturerInput">Manufacturer</label>
                    <input name="manufacturer" type="text" class="form-control" id="manufacturerInput" maxlength="200" />
                </div>
                <div class="form-group">
                    <label for="colorInput">Color</label>
                    <input name="color" type="text" class="form-control" id="colorInput" />
                </div>  
                <div class="form-group">
                    <label for="productLinkInput">Product Link</label>
                    <input name="product_link" type="url" class="form-control" id="productLinkInput" />
                </div>                     
                <div class="form-group">
                    <label for="statusOption">Category *</label>
                    <select name="category_id" class="form-control" id="statusOption" required>
                        <option value="">...Please select a category...</option>
                        @foreach($categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>               
                <div class="form-group">
                    <label for="statusOption">Product State *</label>
                    <select name="product_status" class="form-control" id="statusOption" required>
                        <option value="">...Please select a state...</option>
                        @foreach($status as $state)
                            <option value="{{ $state == 'Activate' ? 1 : 0 }}">{{ $state }}</option>
                        @endforeach
                    </select>
                </div>                

                <button id="productSubmit" type="submit" class="btn btn-primary">Create Product</button>
            </form>
        </div>
    </div>
@endsection