<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('invoices')->nullable();
            $table->string('number')->nullable();
            $table->unsignedInteger('yearly_counter')->nullable();
            $table->date('issued_at'); //data wystawienia
            $table->date('sell_date'); //data sprzedaży
            $table->string('place')->nullable(); //miejsce wystawienia faktury - miasto
            $table->boolean('is_paid')->nullable();
            $table->boolean('is_proforma')->nullable();
            $table->boolean('incoming')->nullable(); //
            $table->enum('pay_type', ['cash', 'transfer']);
            $table->enum('invoice_type', ['cost', 'resale'])->nullable();;
            $table->date('pay_deadline'); //data kiedy upływa termin płatności
            $table->decimal('net_value', 10, 2); //wartość netto faktury
            $table->decimal('total_value', 10, 2); //wartość całokowica faktury
            $table->text('seller_address')->nullable(); //wkleja do kazdego adres sprzedawcy, bez sensu mysle, ze lepsze jest samo id i pobranie go z tabeli customers ale moze sie myle
            $table->text('buyer_address_')->nullable(); //tutaj byl wklejany adres klienta ale jest tez id klienta wiec tego nie rozumiem po co
            $table->text('buyer_address_recipient')->nullable(); //czasami jest inny adres dostawy
            $table->text('buyer_address_delivery')->nullable(); //adres odbiorcy
            $table->string('comments')->nullable(); // uwagi do faktury
            $table->text('buyer_address__name')->nullable();
            $table->text('buyer_address__address')->nullable();
            $table->text('buyer_address__city')->nullable();
            $table->text('buyer_address__postal_code')->nullable();
            $table->text('buyer_address__nip')->nullable();

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
        Schema::dropIfExists('invoices');
    }
}
