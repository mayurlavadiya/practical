<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_details'; 
    protected $fillable = ['Invoice_Id', 'Product_Id', 'Rate', 'Unit', 'Qty', 'Disc_Percentage', 'NetAmount', 'TotalAmount'];

    public function product()
    {
        return $this->belongsTo(ProductMaster::class, 'Product_Id');
    }

    public function invoiceMaster()
    {
        return $this->belongsTo(InvoiceMaster::class, 'Invoice_Id');
    }
}
