<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $newProduct = new Product;
        $newProduct->name = 'Toner do drukarki HP 1100';
        $newProduct->symbol = '92A';
        $newProduct->selling_price = '100';
        $newProduct->purchase_price = '10';
        $newProduct->consumed_price = '2';
        $newProduct->is_gift = '0';
        $newProduct->made_by_us = '1';
        $newProduct->vat = '0.23';
        $newProduct->save();

        $newProduct = new Product;
        $newProduct->name = 'Toner do drukarki HP 1700';
        $newProduct->symbol = '96A';
        $newProduct->selling_price = '130';
        $newProduct->purchase_price = '12';
        $newProduct->consumed_price = '5';
        $newProduct->is_gift = '1';
        $newProduct->made_by_us = '0';
        $newProduct->vat = '0.23';
        $newProduct->save();

    }
}
