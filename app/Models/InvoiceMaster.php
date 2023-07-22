<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceMaster extends Model
{
    use HasFactory;

    protected $table = 'invoice_masters';
    protected $primaryKey = 'id'; 

    protected $fillable = ['CustomerName', 'TotalAmount'];

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'Invoice_Id');
    }
}
