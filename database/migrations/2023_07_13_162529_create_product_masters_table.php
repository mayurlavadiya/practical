<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMastersTable extends Migration
{
    public function up()
    {
        Schema::create('product_masters', function (Blueprint $table) {
            $table->increments('Product_ID');
            $table->string('Product_Name');
            $table->decimal('Rate', 8, 2);
            $table->string('Unit');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_masters');
    }
}
