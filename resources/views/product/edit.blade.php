@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h1>Edit Product</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('product.update', ['Product_Id' => $product->Product_Id]) }}" method="POST">

        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" class="form-control" value="{{ $product->Product_Name }}" required>
        </div>
        <div class="form-group">
            <label for="rate">Rate:</label>
            <input type="text" name="rate" id="rate" class="form-control" value="{{ $product->Rate }}" required>
        </div>
        <div class="form-group">
            <label for="unit">Unit:</label>
            <input type="text" name="unit" id="unit" class="form-control" value="{{ $product->Unit }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

@endsection
