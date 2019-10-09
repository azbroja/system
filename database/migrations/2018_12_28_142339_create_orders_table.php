<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->string('number')->nullable();
            $table->unsignedInteger('yearly_counter')->nullable();
            $table->date('issued_at');
            $table->date('planned');
            $table->enum('pay_type', ['cash', 'transfer']);
            $table->text('invoice_comments')->nullable();
            $table->integer('pay_term');
            $table->integer('is_paid');
            $table->integer('document_type');
            $table->integer('priority');
            $table->date('sell_date');
            $table->text('seller_address')->nullable();
            $table->text('buyer_address_')->nullable();
            $table->text('buyer_address_recipient')->nullable();
            $table->text('buyer_address_delivery')->nullable();
            $table->text('comments')->nullable();
            $table->string('waybill')->nullable();
            $table->enum('collection', ['courier', 'driver', 'own']);
            $table->boolean('incoming')->nullable();
            $table->boolean('warehouse')->nullable();
            $table->boolean('production')->nullable();
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
