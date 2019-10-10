@extends('master')
@section('page')
<div class="row rightContent">
    @include('partials.sidebar')
    <div class="col-md-8">
        @include('partials.message')
        <div class="page-heading">
            <h2>Edit Product Number: {{ $product['id'] }}</h2>
        </div>
        <hr />      
        <form action="{{ route('product_update') }}" method="POST">
            <input name="_id" type="hidden" value="{{ $product['id'] }}">
            @method('PUT')
            @csrf
            <div class="form-group">
                    <label for="productNameInput">Product Name</label>
                    <input name="product_name" type="text" class="form-control" id="productNameInput" value="{{ $product['product_name'] }}" />
                </div>
                <div class="form-group">
                    <label for="slugInput">Slug</label>
                    <input name="slug" type="text" class="form-control" value="{{ $product['slug'] }}" id="slugInput" />
                </div>
                <div class="form-group">
                    <label for="descriptionInput">Description</label>
                    <textarea name="description" rows="6" maxlength="250" class="form-control" id="descriptionInput" value="{{ $product['description'] }}"></textarea>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input name="product_image" type="file" class="form-control" id="imageFileInput" />
                        <label for="imageFileInput" class="custom-file-label">Choose Image File</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="weightInput">Weight</label>
                    <input name="weight" type="number" step="0.01" class="form-control" id="weightInput" value="{{ $product['weight'] }}" />
                </div>
                <div class="form-group">
                    <label for="weightUnitsInput">Weight Units</label>
                    <input name="weight_units" type="text" class="form-control" id="weightUnitsInput" value="{{ $product['weight_units'] }}" />
                </div>
                <div class="form-group">
                    <label for="retailPriceInput">Retail Price</label>
                    <input name="retail_price" type="number" step="0.001" class="form-control" id="retailPriceInput" value="{{ $product['retail_price'] }}" />
                </div>
                <div class="form-group">
                    <label for="wholesalePriceInput">Wholesale Price</label>
                    <input name="wholesale_price" type="number" step="0.001" class="form-control" id="wholesalePriceInput" value="{{ $product['wholesale_price'] }}" />
                </div>
                <div class="form-group">
                    <label for="discountInput">Discount</label>
                    <input name="discount" type="number" step="0.01" class="form-control" id="discountInput" value="{{ $product['discount'] }}" />
                </div>
                <div class="form-group">
                    <label for="manufacturerInput">Manufacturer</label>
                    <input name="manufacturer" type="text" class="form-control" id="manufacturerInput" maxlength="200" value="{{ $product['manufacturer'] }}" />
                </div>
                <div class="form-group">
                    <label for="colorInput">Color</label>
                    <input name="color" type="text" class="form-control" id="colorInput" value="{{ $product['color'] }}" />
                </div>  
                <div class="form-group">
                    <label for="productLinkInput">Product Link</label>
                    <input name="product_link" type="url" class="form-control" id="productLinkInput" value="{{ $product['product_link'] }}" />
                </div>                     
                <div class="form-group">
                    <label for="statusOption">Category</label>
                    <select name="category_id" class="form-control" id="statusOption">
                        <option value="">--------- Selected: {{ $categories[$product['category_id']] }} ---------</option>
                        @foreach($categories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>               

            <button id="productSubmit" type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</div>
@endsection