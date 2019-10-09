<?php

use App\Customer;
use App\Product;
use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker\Factory::create('pl_PL');

        $limit = 500;
        $product = Product::find(1);

        $newCustomer = new Customer;
        $newCustomer->user_id = '1';
        $newCustomer->name = $faker->company;
        $newCustomer->city = $faker->city;
        $newCustomer->postal_code = $faker->postcode;
        $newCustomer->street = $faker->streetaddress;
        $newCustomer->regon = $faker->regon;
        $newCustomer->nip = $faker->regon;
        $newCustomer->bank_account_number = $faker->bankAccountNumber;
        $newCustomer->telephone1 = $faker->phonenumber;
        $newCustomer->telephone2 = $faker->phonenumber;
        $newCustomer->fax = $faker->phonenumber;
        $newCustomer->email = $faker->unique()->email;
        $newCustomer->www = $faker->url;
        $newCustomer->purchaser = '';
        $newCustomer->acquired_at = date("Y-m-d");
        $newCustomer->type = 'Sprzedawca';
        $newCustomer->address_delivery = 'Brak';
        $newCustomer->save();

        $newCustomer->products()->save($product, [
            'product_id' => $faker->numberBetween(1, 2),
            'selling_customer_price' => $faker->numberBetween(79, 119),
            'purchase_customer_price' => $faker->numberBetween(79, 119),
            'consumed_customer_price' => $faker->numberBetween(79, 119),

        ]);

        for ($i = 0; $i < $limit; $i++) {

            $newCustomer = new Customer;
            $newCustomer->user_id = '1';
            $newCustomer->name = $faker->company;
            $newCustomer->city = $faker->city;
            $newCustomer->postal_code = $faker->postcode;
            $newCustomer->street = $faker->streetaddress;
            $newCustomer->regon = $faker->regon;
            $newCustomer->nip = $faker->regon;
            $newCustomer->bank_account_number = $faker->bankAccountNumber;
            $newCustomer->telephone1 = $faker->phonenumber;
            $newCustomer->telephone2 = $faker->phonenumber;
            $newCustomer->fax = $faker->phonenumber;
            $newCustomer->email = $faker->unique()->email;
            $newCustomer->www = $faker->url;
            $newCustomer->acquired_at = null;
            $newCustomer->purchaser = '';
            $newCustomer->type = 'Odbiorca';
            $newCustomer->address_delivery = 'Brak';
            $newCustomer->save();

            $newCustomer->products()->save($product, [
                'product_id' => '1',
                'selling_customer_price' => $faker->numberBetween(79, 119),
                'purchase_customer_price' => $faker->numberBetween(79, 119),
                'consumed_customer_price' => $faker->numberBetween(79, 119),

            ]);

            $newCustomer->products()->save($product, [
                'product_id' => '2',
                'selling_customer_price' => $faker->numberBetween(79, 119),
                'purchase_customer_price' => $faker->numberBetween(79, 119),
                'consumed_customer_price' => $faker->numberBetween(79, 119),

            ]);

        }

    }
}
