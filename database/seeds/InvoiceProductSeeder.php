<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\User;
use App\Customer;
use App\Product;
use App\Invoice;


class InvoiceProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // public function run()
    // {

    //     $newCustomer = new Customer;
    //     $newCustomer->user_id = 1;
    //     $newCustomer->name = 'Testowy Klient';
    //     $newCustomer->city = 'Kraków';
    //     $newCustomer->postal_code = '31-111';
    //     $newCustomer->street = 'Królewska 11';
    //     $newCustomer->regon = '22342122';
    //     $newCustomer->nip = '33322345232';
    //     $newCustomer->telephone1 = '1226598595';
    //     $newCustomer->telephone2 = '1254564642';
    //     $newCustomer->fax = '123423423';
    //     $newCustomer->email = 'biuro@test.pl';
    //     $newCustomer->www = 'www.dojutra.pl';
    //     $newCustomer->competitors = 'brak';
    //     $newCustomer->priority = '1';
    //     $newCustomer->type = 'Odbiorca';
    //     $newCustomer->address_delivery = 'Brak';
    //     $newCustomer->save();



 public function run()
    {

        $faker = Faker\Factory::create('pl_PL');

        $limit = 100;

        for ($i = 0; $i < $limit; $i++) {
            $net_unit_price = $faker->numberBetween(59,199);
            $product => Product::findOrFail($faker->numberBetween(1,2));
            DB::table('invoice_product')->insert([
        'customer_id' => $faker->numberBetween(1,50),
        'product_id' => $product->id,
        'invoice_id' => $faker->unique()->numberBetween(1,50),
        'quantity' => $faker->numberBetween(1,5),
        'product_name' => $product->name,
        'net_unit_price' => $net_unit_price,
        'gross_unit_price' => $net_unit_price*1.23,
            ]);
        }

    }
}
