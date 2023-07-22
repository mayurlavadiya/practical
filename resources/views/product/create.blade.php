@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <a class="btn btn-primary float-right" href="{{ route('invoice.create') }}">Create Invoice</a>
        <h1>Add Products</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('product.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" name="product_name" id="product_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="text" name="rate" id="rate" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="unit">Unit:</label>
                <input type="text" name="unit" id="unit" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>

        <table class="table mt-4 table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Product Name</th>
                    <th>Rate</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $productId => $productName)
                    <tr>
                        <td>{{ $productName }}</td>
                        <td>{{ $rates[$productId] }}</td>
                        <td>{{ $units[$productId] }}</td>
                        <td>
                            <a href="{{ route('product.edit', ['Product_Id' => $productId]) }}"
                                class="btn btn-primary">Edit</a>

                            <form action="{{ route('product.destroy', $productId) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
