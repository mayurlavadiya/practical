@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Edit Invoice</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('invoice.update', $invoiceMaster->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control"
                    value="{{ $invoiceMaster->CustomerName }}" required>
            </div>
            <div class="form-group">
                <label for="product_id">Product:</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $productId => $productName)
                        @php
                            $product = App\Models\ProductMaster::find($productId);
                        @endphp
                        <option value="{{ $productId }}" data-rate="{{ $product->Rate }}"
                            data-unit="{{ $product->Unit }}"
                            {{ $productId == $invoiceMaster->invoiceDetails[0]->product_id ? 'selected' : '' }}>
                            {{ $productName }}
                        </option>
                    @endforeach
                </select>
            </div>   

            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="text" name="rate" id="rate" class="form-control" value="{{ $invoiceMaster->invoiceDetails[0]->Rate }}"
                    readonly>
            </div>
            <div class="form-group">
                <label for="unit">Unit:</label>
                <input type="text" name="unit" id="unit" class="form-control" value="{{ $invoiceMaster->invoiceDetails[0]->Unit }}"
                    readonly>
            </div>
            <div class="form-group">
                <label for="qty">Qty.:</label>
                <input type="text" name="qty" id="qty" class="form-control" value="{{ $invoiceMaster->invoiceDetails[0]->Qty }}"
                    required>
            </div>
            <div class="form-group">
                <label for="disc_percentage">Disc %:</label>
                <input type="text" name="disc_percentage" id="disc_percentage" class="form-control"
                    value="{{ $invoiceMaster->invoiceDetails[0]->Disc_Percentage }}" required>
            </div>
            <div class="form-group">
                <label for="net_amount">Net Amount:</label>
                <input type="text" name="net_amount" id="net_amount" class="form-control"
                    value="{{ $invoiceMaster->invoiceDetails[0]->NetAmount }}" readonly>
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount:</label>
                <input type="text" name="total_amount" id="total_amount" class="form-control"
                    value="{{ $invoiceMaster->TotalAmount }}" readonly>
            </div>
            <button type="button" id="update_button" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Set the initial selected product's rate and unit
        var initialRate = parseFloat('{{ $invoiceMaster->invoiceDetails[0]->Rate }}');
        var initialUnit = '{{ $invoiceMaster->invoiceDetails[0]->Unit }}';
        $('#rate').val(initialRate);
        $('#unit').val(initialUnit);

        // Event listener to update rate and unit when product selection changes
        $('#product_id').on('change', function() {
            var selectedProductId = $(this).val();
            var selectedProduct = @json($products->firstWhere('Product_ID', $invoiceMaster->invoiceDetails[0]->product_id));

            if (selectedProduct && selectedProduct.Product_ID === parseInt(selectedProductId)) {
                $('#rate').val(selectedProduct.Rate);
                $('#unit').val(selectedProduct.Unit);
            } else {
                $('#rate').val('');
                $('#unit').val('');
            }
        });

        // Rest of the JavaScript code remains the same...

        $('#update_button').on('click', function() {
            // Call the function to update the invoice
            updateInvoice();
        });

        function updateInvoice() {
            // Get the updated form data
            var customerName = $('#customer_name').val();
            var productId = $('#product_id').val();
            var rate = parseFloat($('#rate').val());
            var unit = $('#unit').val();
            var qty = parseFloat($('#qty').val());
            var discPercentage = parseFloat($('#disc_percentage').val());
            var netAmount = parseFloat($('#net_amount').val());
            var totalAmount = parseFloat($('#total_amount').val());

            // Validate the data (you can add more validation if required)
            if (customerName.trim() === '' || isNaN(rate) || isNaN(qty) || isNaN(discPercentage) || isNaN(netAmount) || isNaN(totalAmount)) {
                alert('Please fill all the required fields with valid data.');
                return;
            }

            // Prepare the data object to be sent to the server
            var data = {
                customer_name: customerName,
                invoice_details: [{
                    product_id: productId,
                    product_name: '', // You may set the product name here based on the selected product ID
                    rate: rate,
                    unit: unit,
                    qty: qty,
                    disc_percentage: discPercentage,
                    net_amount: netAmount,
                    total_amount: totalAmount
                }]
            };

            // Add CSRF token to the request headers
            var headers = {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            };

            // Send the AJAX request to update the invoice
            fetch('{{ route('invoice.update', $invoiceMaster->id) }}', {
                    method: 'PUT',
                    body: JSON.stringify(data),
                    headers: headers
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    console.log(JSON.stringify(data));

                    console.log(data.success);

                    if (data.success) {
                        alert('Invoice updated successfully!');
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to update invoice. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the invoice. Please try again.');
                });
        }
    </script>
@endsection
