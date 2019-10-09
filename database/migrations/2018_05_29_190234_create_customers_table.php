<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('street')->nullable();
            $table->string('regon')->nullable();
            $table->string('nip')->nullable();
            $table->date('acquired_at')->nullable();;
            $table->string('bank_account_number')->nullable();
            $table->string('telephone1')->nullable();
            $table->string('telephone2')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('www')->nullable();
            $table->text('comments')->nullable();
            $table->string('sell_products')->default('1')->nullable();
            $table->integer('current')->default('1')->nullable();
            $table->string('purchaser')->nullable();
            $table->text('address_delivery')->nullable();
            $table->integer('products_amount')->default('1')->nullable();
            $table->text('bdo_number')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
