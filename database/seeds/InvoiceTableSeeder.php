<?php

use App\Customer;
use App\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvoiceTableSeeder extends Seeder
{

    public function run()
    {

        $faker = Faker\Factory::create('pl_PL');

        $year = date('Y');
        //$yearly_counter = Invoice::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
        $limit = 30;
        $customers = Customer::all();

        for ($i = 0; $i < $limit; $i++) {

            $seller = Customer::where('type', 'Sprzedawca')->first();

            $newInvoice = new Invoice;
            $newInvoice->user_id = '1';
            $customer = $customers->random();

            $newInvoice->yearly_counter = Invoice::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;

            $newInvoice->number = 'FS ' . $newInvoice->yearly_counter . '/' . $year;
            $newInvoice->customer_id = $customer->id;

            $newInvoice->issued_at = date("Y-m-$i");
            $newInvoice->sell_date = date("Y-m-$i");
            $newInvoice->place = 'Kraków';
            $newInvoice->is_paid = 0;
            $newInvoice->pay_type = 'transfer';
            $newInvoice->pay_deadline = (new Carbon())->addDays($faker->numberBetween(7, 14));
            $newInvoice->total_value = -1;
            $newInvoice->net_value = -1;

            $newInvoice->seller_address = $seller->name . "\n" . $seller->street . "\n" . $seller->postal_code . ' ' . $seller->city . "\n NIP: " . $seller->nip;
            $newInvoice->buyer_address_ = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city . "\n NIP: " . $customer->nip;
            $newInvoice->buyer_address_delivery = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city . "\n NIP: " . $customer->nip;
            $newInvoice->buyer_address_recipient = '';
            $newInvoice->comments = '';
            $newInvoice->save();

            $products = [
                0 => [
                    'id' => $customer->products->pluck('id')->first(),
                    'product_name' => $customer->products->pluck('name')->first(),
                    'quantity' => $faker->numberBetween(1, 5),
                    'net_unit_price' => 79,
                ],
                1 => [
                    'id' => $customer->products->pluck('id')->last(),
                    'product_name' => $customer->products->pluck('name')->first(),
                    'quantity' => $faker->numberBetween(1, 5),
                    'net_unit_price' => 79,
                ],
            ];

            $products = array_values(array_filter(
                $products,
                function (array $product) {
                    return (int) $product['quantity'] >= 1;
                }
            ));

            $productsIds = array_column($products, 'id'); // [1, 3]

            $pivotData = array_map(function (array $product) use ($customer) {
                $faker = Faker\Factory::create('pl_PL');
                $net_unit_price = $faker->numberBetween(59, 199);
                return [
                    'customer_id' => $customer->id,
                    'product_name' => $customer->products->pluck('name')->first(),
                    'quantity' => $faker->numberBetween(1, 10),
                    'net_unit_price' => $net_unit_price,
                    'gross_unit_price' => round($net_unit_price * 1.23, 2),
                ];
            }, $products);

            $attachData = array_combine($productsIds, $pivotData);

            $newInvoice->products()->attach($attachData);

            $newInvoice->total_value = $newInvoice->total_sum_gross();
            $newInvoice->update();

        }
    }

}
