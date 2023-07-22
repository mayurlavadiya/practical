<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\ProductMaster;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    public function create()
    {
        $products = ProductMaster::pluck('Product_Name', 'Product_ID');
        $invoiceDetails = [];

        return view('invoice.create', compact('products', 'invoiceDetails'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'customer_name' => 'required',
                'invoice_details' => 'required|array|min:1',
                'invoice_details.*.product_id' => 'required|exists:product_masters,Product_ID',
                // Validate that the product_id exists in the product_masters table
                'invoice_details.*.rate' => 'required|numeric|min:0',
                'invoice_details.*.unit' => 'required',
                'invoice_details.*.qty' => 'required|numeric|min:1',
                'invoice_details.*.disc_percentage' => 'required|numeric|min:0',
                'invoice_details.*.net_amount' => 'required|numeric|min:0',
                'invoice_details.*.total_amount' => 'required|numeric|min:0',
            ]);

            $invoiceMaster = new InvoiceMaster();
            $invoiceMaster->CustomerName = $request->input('customer_name');
            $invoiceMaster->Invoice_no = 'INVOICE-' . date('YmdHis');
            $invoiceMaster->Invoice_Date = date('Y-m-d');
            $invoiceMaster->TotalAmount = 0;
            $invoiceMaster->save();

            foreach ($request->input('invoice_details') as $invoiceDetailData) {
                $invoiceDetail = new InvoiceDetail();
                $invoiceDetail->Invoice_Id = $invoiceMaster->id;
                $invoiceDetail->Product_Id = $invoiceDetailData['product_id']; // Use the correct 'Product_Id' from the invoice_details array
                $invoiceDetail->Rate = $invoiceDetailData['rate'];
                $invoiceDetail->Unit = $invoiceDetailData['unit'];
                $invoiceDetail->Qty = $invoiceDetailData['qty'];
                $invoiceDetail->Disc_Percentage = $invoiceDetailData['disc_percentage'];
                $invoiceDetail->NetAmount = $invoiceDetailData['net_amount'];
                $invoiceDetail->TotalAmount = $invoiceDetailData['total_amount'];
                $invoiceMaster->TotalAmount += $invoiceDetail->TotalAmount;
                $invoiceDetail->save();
            }

            $invoiceMaster->save();

            DB::commit();

            // return redirect()->route('invoice.view', $invoiceMaster->id)->with('success', 'Invoice saved successfully!');
            return response()->json($invoiceMaster)->with('success', 'Invoice saved successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($invoiceMaster);
        }
    }

    public function view()
    {
        $invoiceMasters = InvoiceMaster::with('invoiceDetails')->get();

        // Check if any invoice records are not found
        if ($invoiceMasters->isEmpty()) {
            return redirect()->back()->with('error', 'No invoices found.');
        }

        return view('invoice.view', compact('invoiceMasters'));
    }

    public function edit($id)
    {
        $invoiceMaster = InvoiceMaster::findOrFail($id);
        $products = ProductMaster::pluck('Product_Name', 'Product_ID');
        $invoiceDetails = $invoiceMaster->invoiceDetails->toArray();
    
        return view('invoice.edit', compact('invoiceMaster', 'products', 'invoiceDetails'));
    }

    public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $request->validate([
            'customer_name' => 'required',
            'product_id' => 'required|exists:product_masters,Product_ID',
            // Validate that the product_id exists in the product_masters table
            'rate' => 'required|numeric|min:0',
            'unit' => 'required',
            'qty' => 'required|numeric|min:1',
            'disc_percentage' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $invoiceMaster = InvoiceMaster::findOrFail($id);
        $invoiceMaster->CustomerName = $request->input('customer_name');
        $invoiceMaster->TotalAmount = $request->input('total_amount');
        $invoiceMaster->save();

        // Assuming there is only one invoice detail in the edit form, update it.
        $invoiceDetail = $invoiceMaster->invoiceDetails->first();
        $invoiceDetail->Product_Id = $request->input('product_id');
        $invoiceDetail->Rate = $request->input('rate');
        $invoiceDetail->Unit = $request->input('unit');
        $invoiceDetail->Qty = $request->input('qty');
        $invoiceDetail->Disc_Percentage = $request->input('disc_percentage');
        $invoiceDetail->NetAmount = $request->input('net_amount');
        $invoiceDetail->TotalAmount = $request->input('total_amount');
        $invoiceDetail->save();

        DB::commit();

        return response()->json(['success' => true, 'invoice_id' => $invoiceMaster->id])->with('success', 'Invoice updated successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['success' => false, 'message' => 'Failed to update invoice. Please try again.']);
    }
}

    public function destroy($id)
    {
        $invoiceMaster = InvoiceMaster::findOrFail($id);

        $invoiceMaster->delete();

        return redirect()->route('invoice.view')->with('success', 'Invoice deleted successfully!');
    }

}