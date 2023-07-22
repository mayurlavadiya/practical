<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('invoice_details')) {
            Schema::create('invoice_details', function (Blueprint $table) {
                $table->increments('InvoiceDetail_id');
                $table->unsignedInteger('Invoice_Id');
                $table->unsignedInteger('Product_Id');
                $table->decimal('Rate', 8, 2);
                $table->string('Unit');
                $table->integer('Qty');
                $table->decimal('Disc_Percentage', 5, 2);
                $table->decimal('NetAmount', 8, 2);
                $table->decimal('TotalAmount', 8, 2);
                $table->timestamps();

                $table->foreign('Invoice_Id')->references('id')->on('invoice_masters');
                $table->foreign('Product_Id')->references('Product_ID')->on('product_masters');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
}
