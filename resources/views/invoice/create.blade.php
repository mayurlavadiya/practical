@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <a class="btn btn-primary float-right" href="{{ route('invoice.view') }}">View Invoice</a>
        <br><br> 
        <a class="btn btn-primary float-right" href="{{ route('product.create') }}">View Product</a>

        <h1>Create Invoice</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('invoice.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" required>
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
                            data-unit="{{ $product->Unit }}">
                            {{ $productName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="text" name="rate" id="rate" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="unit">Unit:</label>
                <input type="text" name="unit" id="unit" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="qty">Qty.:</label>
                <input type="text" name="qty" id="qty" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="disc_percentage">Disc %:</label>
                <input type="text" name="disc_percentage" id="disc_percentage" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="net_amount">Net Amount:</label>
                <input type="text" name="net_amount" id="net_amount" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount:</label>
                <input type="text" name="total_amount" id="total_amount" class="form-control" readonly>
            </div>
            <button type="button" id="add_button" class="btn btn-primary">Add</button>
        </form>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Rate</th>
                    <th>Unit</th>
                    <th>Qty.</th>
                    <th>Disc %</th>
                    <th>Net Amount</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="invoice_table_body">
                @foreach ($invoiceDetails as $invoiceDetail)
                    <tr>
                        <td>{{ $invoiceDetail['product_id'] }}</td>
                        <td>{{ $invoiceDetail['product_name'] }}</td>
                        <td>{{ $invoiceDetail['rate'] }}</td>
                        <td>{{ $invoiceDetail['unit'] }}</td>
                        <td>{{ $invoiceDetail['qty'] }}</td>
                        <td>{{ $invoiceDetail['disc_percentage'] }}</td>
                        <td>{{ $invoiceDetail['net_amount'] }}</td>
                        <td>{{ $invoiceDetail['total_amount'] }}</td>
                        <td>
                            <a href="#" class="btn btn-primary">Edit</a>
                            <a href="#" class="btn btn-danger">Remove</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6"></td>
                    <td><strong>Total Amount:</strong></td>
                    <td><strong><span id="total_invoice_amount">0.00</span></strong></td>
                    <td>
                        <button type="submit" id="save_button" class="btn btn-success float-right">Save Invoice</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var storeInvoiceUrl = '{{ route('invoice.store') }}';
        var viewInvoiceUrl = '{{ route('invoice.view', ':id') }}';
    </script>
    <script>
        $(document).ready(function() {
            $('#product_id').on('change', function() {
                var rate = $(this).find(':selected').data('rate');
                var unit = $(this).find(':selected').data('unit');
                $('#rate').val(rate);
                $('#unit').val(unit);
                calculateNetAmount();
                calculateTotalAmount();
            });

            $('#qty, #disc_percentage').on('input', function() {
                calculateNetAmount();
                calculateTotalAmount();
            });

            $('#add_button').on('click', function() {
                addInvoiceDetail();
            });

            $('#save_button').on('click', function() {
                if ($('#customer_name').val().trim() === '' || $('#invoice_table_body tr').length === 0) {
                    return;
                }
                saveInvoice();
            });

            function calculateNetAmount() {
                var rate = parseFloat($('#rate').val());
                var discPercentage = parseFloat($('#disc_percentage').val());

                if (!isNaN(rate) && !isNaN(discPercentage)) {
                    var netAmount = rate - (rate * (discPercentage / 100));
                    $('#net_amount').val(netAmount.toFixed(2));
                } else {
                    $('#net_amount').val('');
                }
            }

            function calculateTotalAmount() {
                var netAmount = parseFloat($('#net_amount').val());
                var qty = parseFloat($('#qty').val());

                if (!isNaN(netAmount) && !isNaN(qty)) {
                    var totalAmount = netAmount * qty;
                    $('#total_amount').val(totalAmount.toFixed(2));
                } else {
                    $('#total_amount').val('');
                }
            }

            function addInvoiceDetail() {
                var product = $('#product_id').find(':selected');
                var productId = product.val();
                var productName = product.text();
                var rate = product.data('rate');
                var unit = product.data('unit');
                var qty = parseFloat($('#qty').val());
                var discPercentage = parseFloat($('#disc_percentage').val());
                var netAmount = parseFloat($('#net_amount').val());
                var totalAmount = parseFloat($('#total_amount').val());

                if (productId && !isNaN(qty) && !isNaN(discPercentage) && !isNaN(netAmount) && !isNaN(
                        totalAmount)) {
                    var row = `
                        <tr>
                            <td>${productId}</td>
                            <td>${productName}</td>
                            <td>${rate}</td>
                            <td>${unit}</td>
                            <td>${qty}</td>
                            <td>${discPercentage}</td>
                            <td>${netAmount}</td>
                            <td>${totalAmount}</td>
                            <td>
                                <a href="#" class="btn btn-primary">Edit</a>
                                <a href="#" class="btn btn-danger">Remove</a>
                            </td>
                        </tr>
                    `;

                    $('#invoice_table_body').append(row);
                    clearFormFields();
                    calculateTotalInvoiceAmount(); // Calculate and update total amount in the footer
                }
            }

            function calculateTotalInvoiceAmount() {
                var totalInvoiceAmount = 0;
                $('#invoice_table_body tr').each(function() {
                    var totalAmount = parseFloat($(this).find('td:eq(7)').text());
                    if (!isNaN(totalAmount)) {
                        totalInvoiceAmount += totalAmount;
                    }
                });

                // Update the total amount in the footer
                $('#total_invoice_amount').text(totalInvoiceAmount.toFixed(2));
            }

            function clearFormFields() {
                $('#product_id').val('');
                $('#product_name').val('');
                $('#rate').val('');
                $('#unit').val('');
                $('#qty').val('');
                $('#disc_percentage').val('');
                $('#net_amount').val('');
                $('#total_amount').val('');
            }

            function saveInvoice() {
                var customerName = $('#customer_name').val();
                var invoiceDetails = [];

                $('#invoice_table_body tr').each(function() {
                    var row = $(this);
                    var productId = row.find('td:eq(0)').text();
                    var productName = row.find('td:eq(1)').text();
                    var rate = row.find('td:eq(2)').text();
                    var unit = row.find('td:eq(3)').text();
                    var qty = row.find('td:eq(4)').text();
                    var discPercentage = row.find('td:eq(5)').text();
                    var netAmount = row.find('td:eq(6)').text();
                    var totalAmount = row.find('td:eq(7)').text();

                    invoiceDetails.push({
                        product_id: productId,
                        product_name: productName,
                        rate: rate,
                        unit: unit,
                        qty: qty,
                        disc_percentage: discPercentage,
                        net_amount: netAmount,
                        total_amount: totalAmount
                    });
                });

                var data = {
                    customer_name: customerName,
                    invoice_details: invoiceDetails
                };

                var headers = {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                };

                fetch(storeInvoiceUrl, {
                        method: 'POST',
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

                        if (data) {
                            var invoiceId = data.invoice_id;
                            var redirectUrl = viewInvoiceUrl.replace(':invoiceid', invoiceId);
                            window.location.href = redirectUrl;
                            alert('Invoice saved successfully!');
                        } else {
                            alert(data.message || 'Failed to save invoice. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while saving the invoice. Please try again.');
                    });
            }


        });
    </script>
@endsection
