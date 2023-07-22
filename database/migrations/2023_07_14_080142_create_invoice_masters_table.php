<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceMastersTable extends Migration
{
    public function up()
    {
        Schema::create('invoice_masters', function (Blueprint $table) {
            $table->id();
            $table->string('Invoice_no');
            $table->date('Invoice_Date');
            $table->string('CustomerName');
            $table->decimal('TotalAmount', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_masters');
    }
}
