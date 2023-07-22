@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <a class="btn btn-primary float-right" href="{{ route('invoice.create') }}">Create invoice</a>

        <h1>Invoice Details</h1>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Customer Name</th>
                    <th>Product ID</th>
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
            <tbody>
                <?php
                $totalAmount = 0;
                ?>
                @foreach ($invoiceMasters as $invoiceMaster)
                    @foreach ($invoiceMaster->invoiceDetails as $invoiceDetail)
                        <tr>
                            <td>{{ $invoiceMaster->CustomerName }}</td>
                            <td>{{ $invoiceDetail->product->Product_ID }}</td>
                            <td>{{ $invoiceDetail->product->Product_Name }}</td>
                            <td>{{ $invoiceDetail->Rate }}</td>
                            <td>{{ $invoiceDetail->Unit }}</td>
                            <td>{{ $invoiceDetail->Qty }}</td>
                            <td>{{ $invoiceDetail->Disc_Percentage }}</td>
                            <td>{{ $invoiceDetail->NetAmount }}</td>
                            <td>{{ $invoiceDetail->TotalAmount }}</td>
                            <td>
                                <a href="{{ route('invoice.edit', $invoiceMaster->id) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <a href="{{ route('invoice.delete', $invoiceMaster->id) }}"
                                    class="btn btn-sm btn-danger">Delete</a>
                                </a>
                            </td>

                        </tr>
                    @endforeach
                    <?php
                    $totalAmount += $invoiceMaster->TotalAmount;
                    ?>
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8" class="text-right">Total:</th>
                    <th>{{ $totalAmount }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
