<?php

use App\Complaint;
use App\Customer;
use App\CustomerEvent;
use App\Gift;
use App\Invoice;
use App\InvoiceComment;
use App\Notifications\InvoiceMade;
use App\Offer;
use App\Order;
use App\Product;
use App\Release;
use App\Test;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
//Route::middleware('auth:api')->
Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/customer/{id}/products', function (Request $request, $id) {
    $customer = Customer::findOrFail($id);

    $searchValues = preg_split('/\s+/', $request->q, -1, PREG_SPLIT_NO_EMPTY);

    // return $products = Product::where(function ($q) use ($searchValues) {
    //     foreach ($searchValues as $value) {
    //         $q->where('name', 'like', "%{$value}%")
    //             ->orWhere('symbol', 'like', "%{$value}%");
    //     }
    // })->orderBy('name', 'asc')->take(100)->get();

    $query = function ($q) use ($searchValues) {
        foreach ($searchValues as $value) {
            $q->where(function ($query) use ($value) {
                $query
                    ->orWhere('name', 'like', "%{$value}%")
                    ->orWhere('symbol', 'like', "%{$value}%");
            });
        }
    };

    return $products = Product::where($query)->orderBy('name', 'asc')->take(100)->get();

})->name('products');

Route::get('/allCustomers', function (Request $request) {

    $searchValues = preg_split('/\s+/', $request->q, -1, PREG_SPLIT_NO_EMPTY);

    // return $products = Product::where(function ($q) use ($searchValues) {
    //     foreach ($searchValues as $value) {
    //         $q->where('name', 'like', "%{$value}%")
    //             ->orWhere('symbol', 'like', "%{$value}%");
    //     }
    // })->orderBy('name', 'asc')->take(100)->get();

    $query = function ($q) use ($searchValues) {
        foreach ($searchValues as $value) {
            $q->where(function ($query) use ($value) {
                $query->orWhere('name', 'like', "%{$value}%");
            });
        }
    };
    return $customers = Customer::where($query)->orderBy('name', 'asc')->take(100)->get();

})->name('allCustomers');

Route::get('/customers', function (Request $request) {

    $searchValues = preg_split('/\s+/', $request->q, -1, PREG_SPLIT_NO_EMPTY);

    $query = function ($q) use ($searchValues) {
        foreach ($searchValues as $value) {
            $q->where(function ($query) use ($value) {
                $query->orWhere('name', 'like', "%{$value}%");
            });
        }
    };

    return $customers = Customer::where($query)->where('type', '=', 'Dostawca')->orderBy('name', 'asc')->take(100)->get();

})->name('customers');

Route::put('/customer/products/{customer}', function (Request $request, Customer $customer) {
    $products = $request->all();
    return $customer->products()->sync($products);

});

Route::get('/customer/products/{customer}', function (Request $request, Customer $customer) {

    return $products = $customer->products()->get();

});

Route::post('/customer/offer/create/{customer}', function (Request $request, Customer $customer) {
    print_r($request->input('customer_prices'));

    $year = date('Y');

    // $user = Auth::user();

    $seller = Customer::find(1);
    $newOffer = new Offer;
    $newOffer->user_id = $customer->user_id;
    $newOffer->customer_id = $customer->id;
    $newOffer->yearly_counter = Offer::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
    $newOffer->number = $newOffer->yearly_counter . '/' . $year;
    $newOffer->issued_at = date("Y-m-d");
    $newOffer->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
    $newOffer->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;
    $newOffer->save();

    $productsInput = $request->all();

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'product_name' => $productInput['product_name'],
            'selling_customer_price' => $productInput['selling_customer_price'],
            'purchase_customer_price' => $productInput['purchase_customer_price'],
            'consumed_customer_price' => $productInput['consumed_customer_price'],

        ];}, $productsInput);

    return $newOffer->products()->sync($pivotData);

});

Route::put('/customer/offer/update/{id}', function (Request $request, $id) {

    $year = date('Y');

    // $user = Auth::user();

    $offer = Offer::findOrfail($id);
    $customer = Customer::findOrFail($offer->customer_id);

    $productsInput = $request->all();

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'selling_customer_price' => $productInput['selling_customer_price'],
            'purchase_customer_price' => $productInput['purchase_customer_price'],
            'consumed_customer_price' => $productInput['consumed_customer_price'],

        ];}, $productsInput);

    return $offer->products()->sync($pivotData);

});

Route::post('/customer/order/create/{customer}', function (Request $request, Customer $customer) {
    // print_r($request->input('customer_prices.*.quantity'));

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $year = date('Y');
    $now = new Carbon();

    $seller = Customer::find(1);
    DB::beginTransaction();

    $newOrder = new Order;
    $newOrder->user_id = $customer->user_id;
    $newOrder->customer_id = $customer->id;
    $newOrder->yearly_counter = Order::whereYear('issued_at', $year)->max('yearly_counter') + 1;
    $newOrder->issued_at = $now;
    $newOrder->sell_date = $now;
    $newOrder->warehouse = '0';
    $newOrder->production = '0';
    $newOrder->planned = $request->input('planned');
    $newOrder->document_type = $request->input('documentType');
    $newOrder->priority = $request->input('priority');
    $newOrder->collection = $request->input('collection');
    $newOrder->incoming = $request->input('orderTypeSend');
    $newOrder->pay_type = $request->input('payTypeSend');
    $newOrder->number = $newOrder->yearly_counter . '/' . $year;
    // $newOrder->issued_at = date("Y-m-d");
    $newOrder->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
    $newOrder->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;
    $newOrder->comments = $request->input('comments');
    $newOrder->invoice_comments = $request->input('invoiceComments');
    $newOrder->pay_term = $request->input('payTermSend');
    $newOrder->is_paid = '0';
    $newOrder->buyer_address_delivery = $request->input('addresDeliverySend');

    $newOrder->save();
    DB::commit();

    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);

    return $newOrder->products()->sync($pivotData);

});

Route::put('/customer/order/update/{id}', function (Request $request, $id) {
    // print_r($request->input('customer_prices.*.quantity'));

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }

    $year = date('Y');

    $order = Order::findOrfail($id);
    $customer = Customer::findOrFail($order->customer_id);

    $now = new Carbon();

    $order->pay_type = $request->input('payTypeSend');

    $order->warehouse = '0';
    $order->production = '0';
    $order->document_type = $request->input('documentType');
    $order->priority = $request->input('priority');
    $order->planned = $request->input('planned');
    $order->collection = $request->input('collection');
    $order->comments = $request->input('comments');
    $order->incoming = $request->input('orderTypeSend');
    $order->invoice_comments = $request->input('invoiceComments');
    $order->pay_term = $request->input('payTermSend');
    $order->buyer_address_delivery = $request->input('addresDeliverySend');
    $order->update();

    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);

    return $order->products()->sync($pivotData);

});

Route::post('/customer/release/create/{customer}', function (Request $request, Customer $customer) {
    //print_r($request->input('customer_prices.*.quantity'));

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $year = date('Y');
    $now = new Carbon();

    $seller = Customer::find(1);
    $newRelease = new Release;
    $newRelease->user_id = $customer->user_id;
    $newRelease->customer_id = $customer->id;
    $newRelease->yearly_counter = Release::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
    $newRelease->sell_date = $now;
    $newRelease->is_paid = '0';
    $newRelease->number = $newRelease->yearly_counter . '/' . $year;
    $newRelease->issued_at = date("Y-m-d");
    $newRelease->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
    $newRelease->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;
    $newRelease->comments = $request->input('comments');
    $newRelease->value = 1;
    $newRelease->save();

    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);

    $newRelease->products()->sync($pivotData);
    $newRelease->value = $newRelease->total_sum();
    $newRelease->update();
    return $newRelease->products()->sync($pivotData);

});

Route::post('/customer/complaint/create/{customer}', function (Request $request, Customer $customer) {
    // print_r($request->input('customer_prices.*.quantity'));

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $year = date('Y');
    $now = new Carbon();

    $seller = Customer::find(1);
    $newComplaint = new Complaint;
    $newComplaint->user_id = $customer->user_id;
    $newComplaint->customer_id = $customer->id;
    $newComplaint->yearly_counter = Complaint::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
    $newComplaint->issued_at = $now;
    $newComplaint->sell_date = $now;
    $newComplaint->made_date = $now;
    $newComplaint->number = $newComplaint->yearly_counter . '/' . $year;
    // $newComplaint->issued_at = date("Y-m-d");
    $newComplaint->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
    $newComplaint->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;
    $newComplaint->comments = $request->input('comments');
    $newComplaint->buyer_address_delivery = $request->input('addresDeliverySend');
    $newComplaint->is_paid = '0';
    $newComplaint->value = -1;
    $newComplaint->save();

    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);

    $newComplaint->products()->sync($pivotData);
    $newComplaint->value = $newComplaint->total_sum();
    $newComplaint->update();

    return $newComplaint->products()->sync($pivotData);

});

Route::put('/customer/complaint/update/{id}', function (Request $request, $id) {
    // print_r($request->input('customer_prices.*.quantity'));

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $year = date('Y');
    $now = new Carbon();

    $seller = Customer::findOrFail(1);
    $complaint = Complaint::findOrFail($id);
    $customer = Customer::findOrFail($complaint->customer_id);
    $complaint->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
    $complaint->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;
    $complaint->comments = $request->input('comments');
    $complaint->is_paid = '0';
    $complaint->value = -1;
    $complaint->buyer_address_delivery = $request->input('addresDeliverySend');
    $complaint->update();

    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);

    $complaint->products()->sync($pivotData);
    $complaint->value = $complaint->total_sum();
    $complaint->update();

    return $complaint->products()->sync($pivotData);

});

Route::post('/customer/test/create/{customer}', function (Request $request, Customer $customer) {
    // print_r($request->input('customer_prices.*.quantity'));

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $year = date('Y');
    $now = new Carbon();

    $seller = Customer::find(1);
    $newTest = new Test;
    $newTest->user_id = $customer->user_id;
    $newTest->customer_id = $customer->id;
    $newTest->yearly_counter = Test::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
    $newTest->issued_at = $now;
    $newTest->sell_date = $now;
    $newTest->pay_term = '7';
    $newTest->pay_type = 'transfer';
    $newTest->rate = $request->input('rate');
    $newTest->number = $newTest->yearly_counter . '/' . $year;
    // $newTest->issued_at = date("Y-m-d");
    $newTest->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
    $newTest->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;
    $newTest->comments = $request->input('comments');
    $newTest->is_paid = '0';
    $newTest->buyer_address_delivery = $request->input('addresDeliverySend');

    $newTest->save();

    $newCustomerEvent = new CustomerEvent;
    $newCustomerEvent->user_id = $customer->user_id;
    $newCustomerEvent->customer_id = $customer->id;
    $newCustomerEvent->note = 'TEST! Zadzwoń i zapytaj o toner do testów.';
    $newCustomerEvent->planned = $now->addWeekdays(14);
    $newCustomerEvent->contact_way = 'phone';
    $newCustomerEvent->is_completed = 0;
    $newCustomerEvent->save();

    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);

    return $newTest->products()->sync($pivotData);

});

Route::post('/customer/invoice/create/{customer}', function (Request $request, Customer $customer) {
    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $year = date('Y');
    $now = new Carbon();
    $yearly_counter = Invoice::whereYear('issued_at', $year)->max('yearly_counter');

    $seller = Customer::find(1);
    $newInvoice = new Invoice;
    $newInvoice->user_id = $customer->user_id;
    $newInvoice->customer_id = $customer->id;
    $newInvoice->yearly_counter = $yearly_counter + 1;
    $newInvoice->issued_at = $now;
    $newInvoice->sell_date = $now;
    $newInvoice->incoming = '0';
    $newInvoice->pay_type = $request->input('payTypeSend');
    $newInvoice->place = 'Kraków';
    $newInvoice->number = $newInvoice->yearly_counter . '/' . $year;
    $newInvoice->seller_address = $seller->name . "\n" . $seller->street . "\n" . $seller->postal_code . ' ' . $seller->city . "\n NIP: " . $seller->nip;
    if ($customer->purchaser != null) {
        $newInvoice->buyer_address_ = $customer->purchaser;
        $newInvoice->buyer_address_recipient = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city;} else {
        $newInvoice->buyer_address_ = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city . "\n NIP: " . $customer->nip;

    }
    $lines = array_map('trim', explode("\n", $newInvoice->buyer_address_));
    $totalLines = count($lines);
    $nameLine = implode("\n", array_slice($lines, 0, -3));
    $addressLine = $lines[$totalLines - 3];
    $postalLine = $lines[$totalLines - 2];
    $nipLine = $lines[$totalLines - 1];
    if (!preg_match('#^(?<postal>\d{2}[\s–-]+\d{3})\s+(?<city>.+)$#', $postalLine, $matches) && !preg_match('#^(?<city>.+)\s+(?<postal>\d{2}[\s–-]+\d{3})$#', $postalLine, $matches)) {
        // throw new InvoiceException('Invalid postal line: ' . $postalLine, $invoice->id);
        return response()->json('Invalid postal line: ' . $postalLine, $newInvoice->id);
    }
    $postalCode = preg_replace('#\D+#', '-', $matches['postal']);
    $city = $matches['city'];
    if (!preg_match('#^nip:\s*([\d\s–-]+|brak)$|^(?:nip:)?[\s\/:]*pesel[\s:]+[\d\s–-]+$|^nip:$#i', $nipLine, $matches)) {
        // throw new InvoiceException('Invalid NIP line: ' . $nipLine, $invoice->id);
        return response('Invalid NIP line: ' . $nipLine, $newInvoice->id);

    }
    $nip = preg_replace('#\D#', '', $matches[1] ?? '') ?: null;
    $newInvoice->buyer_address__name = $nameLine;
    $newInvoice->buyer_address__address = $addressLine;
    $newInvoice->buyer_address__city = $city;
    $newInvoice->buyer_address__postal_code = $postalCode;
    $newInvoice->buyer_address__nip = $nip;
    $newInvoice->comments = $request->input('comments');
    $newInvoice->is_paid = '0';
    $newInvoice->pay_deadline = (new Carbon())->addDays($request->input('payTermSend'));
    $newInvoice->net_value = -1;
    $newInvoice->total_value = -1.23;
    $newInvoice->save();

    if ($request->input('invoice_products.*.pivot.release_id')) {
        Release::whereIn('id', $request->input('invoice_products.*.pivot.release_id'))->update(['is_paid' => true]);
        Release::whereIn('id', $request->input('invoice_products.*.pivot.release_id'))->update(['child_id' => $newInvoice->id]);

    };

    if ($request->input('invoice_products.*.pivot.order_id')) {
        Order::whereIn('id', $request->input('invoice_products.*.pivot.order_id'))->update(['is_paid' => true]);

    };

    if ($request->input('invoice_products.*.pivot.test_id')) {
        Test::whereIn('id', $request->input('invoice_products.*.pivot.test_id'))->update(['is_paid' => true]);
        Test::whereIn('id', $request->input('invoice_products.*.pivot.test_id'))->update(['rate' => 'Pozytywny']);

    };

    if ($newInvoice->pay_type == 'cash') {
        $comment = new InvoiceComment;
        $comment->invoice_id = $newInvoice->id;
        $comment->user_id = $newInvoice->user_id;
        $comment->note = 'Gotówka';
        $comment->save();
    };

    $productsInput = $request->input('customer_prices');
    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);
    $newInvoice->products()->sync($pivotData);
    $newInvoice->net_value = $newInvoice->total_sum_net();
    $newInvoice->total_value = $newInvoice->total_sum_gross();
    $newInvoice->update();

    // if ($yearly_counter === 1) {
    //     $when = now()->addSeconds(10);
    //     $user = User::findOrFail(5);
    //     $user->notify((new InvoiceMade($newInvoice))->delay($when));
    // };

    // $when = now()->addSeconds(10);
    // $user = User::findOrFail($newInvoice->user_id);
    // $user->notify((new InvoiceMade($newInvoice))->delay($when));

    return $newInvoice->products()->sync($pivotData);

});

Route::put('/customer/invoice/update/{id}', function (Request $request, $id) {
    //print_r($request->all());

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $seller = Customer::find(1);

    $year = date('Y');
    $invoice = Invoice::findOrfail($id);
    $customer = Customer::findOrFail($request->input('customerID'));

    $now = new Carbon();
    $invoice->customer_id = $customer->id;
    $invoice->user_id = $customer->user_id;
    $invoice->pay_deadline = $request->input('payTermSend');
    $invoice->pay_type = $request->input('payTypeSend');
    $invoice->comments = $request->input('comments');
    $invoice->buyer_address_delivery = $request->input('addresDeliverySend');

    $invoice->seller_address = $seller->name . "\n" . $seller->street . "\n" . $seller->postal_code . ' ' . $seller->city . "\n NIP: " . $seller->nip;

    if ($customer->purchaser != null) {
        $invoice->buyer_address_ = $customer->purchaser;
        $invoice->buyer_address_recipient = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city;} else {
        $invoice->buyer_address_ = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city . "\n NIP: " . $customer->nip;

    }
    $lines = array_map('trim', explode("\n", $invoice->buyer_address_));
    $totalLines = count($lines);
    $nameLine = implode("\n", array_slice($lines, 0, -3));
    $addressLine = $lines[$totalLines - 3];
    $postalLine = $lines[$totalLines - 2];
    $nipLine = $lines[$totalLines - 1];
    if (!preg_match('#^(?<postal>\d{2}[\s–-]+\d{3})\s+(?<city>.+)$#', $postalLine, $matches) && !preg_match('#^(?<city>.+)\s+(?<postal>\d{2}[\s–-]+\d{3})$#', $postalLine, $matches)) {
        throw new InvoiceException('Invalid postal line: ' . $postalLine, $invoice->id);
    }
    $postalCode = preg_replace('#\D+#', '-', $matches['postal']);
    $city = $matches['city'];
    if (!preg_match('#^nip:\s*([\d\s–-]+|brak)$|^(?:nip:)?[\s\/:]*pesel[\s:]+[\d\s–-]+$|^nip:$#i', $nipLine, $matches)) {
        throw new InvoiceException('Invalid NIP line: ' . $nipLine, $invoice->id);
    }
    $nip = preg_replace('#\D#', '', $matches[1] ?? '') ?: null;
    $invoice->buyer_address__name = $nameLine;
    $invoice->buyer_address__address = $addressLine;
    $invoice->buyer_address__city = $city;
    $invoice->buyer_address__postal_code = $postalCode;
    $invoice->buyer_address__nip = $nip;

    $invoice->update();

    $productsInput = $request->input('customer_prices');
    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);
    $invoice->products()->sync($pivotData);
    $invoice->net_value = $invoice->total_sum_net();
    $invoice->total_value = $invoice->total_sum_gross();
    $invoice->update();

    return $invoice->products()->sync($pivotData);

});

Route::post('/customer/invoice/pro-forma/create/{customer}', function (Request $request, Customer $customer) {
    //print_r($request->input('invoice_products.*.pivot'));

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $year = date('Y');
    $now = new Carbon();

    $seller = Customer::find(1);
    $newInvoice = new Invoice;
    $newInvoice->user_id = $customer->user_id;
    $newInvoice->customer_id = $customer->id;
    $newInvoice->is_proforma = true;
    $newInvoice->issued_at = $now;
    $newInvoice->sell_date = $now;
    $newInvoice->yearly_counter = (Invoice::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->whereIsProforma($newInvoice->is_proforma)->value('yearly_counter') ?? 0) + 1;

    $newInvoice->incoming = '0';
    $newInvoice->pay_type = $request->input('payTypeSend');
    $newInvoice->place = 'Kraków';
    $newInvoice->number = 'PRO FORMA ' . $newInvoice->yearly_counter . '/' . $year;
    $newInvoice->seller_address = $seller->name . "\n" . $seller->street . "\n" . $seller->postal_code . ' ' . $seller->city . "\n NIP: " . $seller->nip;
    if ($customer->purchaser != null) {
        $newInvoice->buyer_address_ = $customer->purchaser;
        $newInvoice->buyer_address_recipient = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city;} else {
        $newInvoice->buyer_address_ = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city . "\n NIP: " . $customer->nip;

    }
    $lines = array_map('trim', explode("\n", $newInvoice->buyer_address_));
    $totalLines = count($lines);
    $nameLine = implode("\n", array_slice($lines, 0, -3));
    $addressLine = $lines[$totalLines - 3];
    $postalLine = $lines[$totalLines - 2];
    $nipLine = $lines[$totalLines - 1];
    if (!preg_match('#^(?<postal>\d{2}[\s–-]+\d{3})\s+(?<city>.+)$#', $postalLine, $matches) && !preg_match('#^(?<city>.+)\s+(?<postal>\d{2}[\s–-]+\d{3})$#', $postalLine, $matches)) {
        throw new InvoiceException('Invalid postal line: ' . $postalLine, $invoice->id);
    }
    $postalCode = preg_replace('#\D+#', '-', $matches['postal']);
    $city = $matches['city'];
    if (!preg_match('#^nip:\s*([\d\s–-]+|brak)$|^(?:nip:)?[\s\/:]*pesel[\s:]+[\d\s–-]+$|^nip:$#i', $nipLine, $matches)) {
        throw new InvoiceException('Invalid NIP line: ' . $nipLine, $invoice->id);
    }
    $nip = preg_replace('#\D#', '', $matches[1] ?? '') ?: null;
    $newInvoice->buyer_address__name = $nameLine;
    $newInvoice->buyer_address__address = $addressLine;
    $newInvoice->buyer_address__city = $city;
    $newInvoice->buyer_address__postal_code = $postalCode;
    $newInvoice->buyer_address__nip = $nip;

    $newInvoice->comments = $request->input('comments');
    $newInvoice->is_paid = '0';
    $newInvoice->pay_deadline = (new Carbon())->addDays($request->input('payTermSend'));
    $newInvoice->net_value = -1;
    $newInvoice->total_value = -1.23;
    $newInvoice->save();

    if ($request->input('invoice_products.*.pivot.release_id')) {
        Release::whereIn('id', $request->input('invoice_products.*.pivot.release_id'))->update(['is_paid' => true]);
        Release::whereIn('id', $request->input('invoice_products.*.pivot.release_id'))->update(['child_id' => $newInvoice->id]);

    };

    if ($request->input('invoice_products.*.pivot.order_id')) {
        Order::whereIn('id', $request->input('invoice_products.*.pivot.order_id'))->update(['is_paid' => true]);

    };

    if ($request->input('invoice_products.*.pivot.test_id')) {
        Test::whereIn('id', $request->input('invoice_products.*.pivot.test_id'))->update(['is_paid' => true]);
        Test::whereIn('id', $request->input('invoice_products.*.pivot.test_id'))->update(['rate' => 'Pozytywny']);

    };

    $productsInput = $request->input('customer_prices');
    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);
    $newInvoice->products()->sync($pivotData);
    $newInvoice->net_value = $newInvoice->total_sum_net();
    $newInvoice->total_value = $newInvoice->total_sum_gross();
    $newInvoice->update();
    return $newInvoice->products()->sync($pivotData);

});

Route::post('/customer/invoice/correction/create/{customer}', function (Request $request, Customer $customer) {
    //print_r($request->input('invoice_products.*.pivot'));

    if (!$request->input('customer_prices') || !($request->input('customer_prices', ['quantity' => 'min:1']))) {
        return 'Dodaj produkty';
    }
    $seller = Customer::findOrFail(1);

    $year = date('Y');
    $now = new Carbon();

    $parentInvoice = Invoice::findOrFail($request->input('invoiceId'));

    $newInvoiceCorrection = new Invoice;
    $newInvoiceCorrection->user_id = $customer->user_id;
    $newInvoiceCorrection->customer_id = $customer->id;
    $newInvoiceCorrection->parent_id = $parentInvoice->id;
    $newInvoiceCorrection->yearly_counter = Invoice::whereYear('issued_at', $year)->whereNotNull('parent_id')->orderByDesc('yearly_counter')->value('yearly_counter') + 1;

    $newInvoiceCorrection->issued_at = $now;
    $newInvoiceCorrection->sell_date = $now;
    $newInvoiceCorrection->place = 'Kraków';
    $newInvoiceCorrection->is_paid = 0;
    $newInvoiceCorrection->pay_type = $request->input('payTypeSend');
    $newInvoiceCorrection->pay_deadline = (new Carbon())->addDays($request->input('payTermSend'));
    $newInvoiceCorrection->seller_address = $seller->name . "\n" . $seller->street . "\n" . $seller->postal_code . ' ' . $seller->city . "\n NIP: " . $seller->nip;

    $newInvoiceCorrection->number = $newInvoiceCorrection->yearly_counter . '/' . $year;
    $newInvoiceCorrection->buyer_address_ = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city . "\n NIP: " . $customer->nip;

    $lines = array_map('trim', explode("\n", $newInvoiceCorrection->buyer_address_));
    $totalLines = count($lines);
    $nameLine = implode("\n", array_slice($lines, 0, -3));
    $addressLine = $lines[$totalLines - 3];
    $postalLine = $lines[$totalLines - 2];
    $nipLine = $lines[$totalLines - 1];
    if (!preg_match('#^(?<postal>\d{2}[\s–-]+\d{3})\s+(?<city>.+)$#', $postalLine, $matches) && !preg_match('#^(?<city>.+)\s+(?<postal>\d{2}[\s–-]+\d{3})$#', $postalLine, $matches)) {
        throw new InvoiceException('Invalid postal line: ' . $postalLine, $invoice->id);
    }
    $postalCode = preg_replace('#\D+#', '-', $matches['postal']);
    $city = $matches['city'];
    if (!preg_match('#^nip:\s*([\d\s–-]+|brak)$|^(?:nip:)?[\s\/:]*pesel[\s:]+[\d\s–-]+$|^nip:$#i', $nipLine, $matches)) {
        throw new InvoiceException('Invalid NIP line: ' . $nipLine, $invoice->id);
    }
    $nip = preg_replace('#\D#', '', $matches[1] ?? '') ?: null;
    $newInvoiceCorrection->buyer_address__name = $nameLine;
    $newInvoiceCorrection->buyer_address__address = $addressLine;
    $newInvoiceCorrection->buyer_address__city = $city;
    $newInvoiceCorrection->buyer_address__postal_code = $postalCode;
    $newInvoiceCorrection->buyer_address__nip = $nip;

    $newInvoiceCorrection->comments = $request->input('comments');
    $newInvoiceCorrection->net_value = -1;
    $newInvoiceCorrection->total_value = -1;
    $newInvoiceCorrection->incoming = '0';

    $newInvoiceCorrection->save();

    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);

    $newInvoiceCorrection->products()->sync($pivotData);

    $newInvoiceCorrection->total_value = ($newInvoiceCorrection->total_sum_gross() - $parentInvoice->total_sum_gross());
    $newInvoiceCorrection->net_value = ($newInvoiceCorrection->total_sum_net() - $parentInvoice->total_sum_net());
    $newInvoiceCorrection->update();

    return $newInvoiceCorrection->products()->sync($pivotData);

});

Route::post('/gift/create/{orderId}', function (Request $request, $orderId) {
    //print_r($request->all());
    $year = date('Y');

    // $user = Auth::user();
    $order = Order::findOrFail($orderId);
    $customer = Customer::findOrFail($order->customer_id);

    $seller = Customer::find(1);
    $newGift = new Gift;
    $newGift->user_id = $customer->user_id;
    $newGift->customer_id = $customer->id;
    $newGift->yearly_counter = Gift::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
    $newGift->number = $newGift->yearly_counter . '/' . $year;
    $newGift->issued_at = date("Y-m-d");
    $newGift->sell_date = date("Y-m-d");

    $newGift->is_paid = '0';

    $newGift->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
    $newGift->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;
    $newGift->total_value = -1;

    $newGift->save();
    //print_r($customer->name);
    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);
    $newGift->products()->sync($pivotData);
    $newGift->total_value = $newGift->total_sum_gross();
    $newGift->update();
    return $newGift->products()->sync($pivotData);

});

Route::put('/gift/update/{giftId}', function (Request $request, $giftId) {
    //print_r($request->all());
    $year = date('Y');

    // $user = Auth::user();
    $gift = Gift::findOrFail($giftId);
    $customer = Customer::findOrFail($gift->customer_id);

    //print_r($customer->name);
    $productsInput = $request->input('customer_prices');

    $pivotData = array_map(function (array $productInput) use ($customer) {
        return $productInput + ['customer_id' => $customer->id,
            'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
        ];}, $productsInput);
    $gift->products()->sync($pivotData);
    $gift->total_value = $gift->total_sum_gross();
    $gift->update();
    return $gift->products()->sync($pivotData);

});

Route::post('/customer/invoice/incoming/{customerID}', function (Request $request, $customerID) {
    //print_r($request->all());

    $year = date('Y');
    $now = new Carbon();
    $buyer = Customer::findOrFail(1);
    $customer = Customer::findOrFail($customerID);
    $seller = $customer;
    $newInvoice = new Invoice;
    $newInvoice->user_id = $request->input('userID');
    $newInvoice->customer_id = $customer->id;
    $newInvoice->yearly_counter = null;
    $newInvoice->issued_at = $now;
    $newInvoice->incoming = true;
    $newInvoice->sell_date = $now;
    $newInvoice->pay_type = 'transfer';
    $newInvoice->place = 'Kraków';
    $newInvoice->number = $request->input('invoiceNumber');
    $newInvoice->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
    $newInvoice->buyer_address__name = $buyer->name;
    $newInvoice->buyer_address__address = $buyer->street;
    $newInvoice->buyer_address__city = $buyer->city;
    $newInvoice->buyer_address__postal_code = $buyer->postal_code;
    $newInvoice->buyer_address__nip = $buyer->nip;

    $newInvoice->is_paid = '0';
    $newInvoice->pay_deadline = $request->input('payTerm');
    $newInvoice->net_value = str_replace(',', '.', $request->input('netValue'));
    $newInvoice->total_value = str_replace(',', '.', $request->input('grossValue'));
    $newInvoice->invoice_type = $request->input('invoiceType');
    $newInvoice->save();

    return $newInvoice->is_paid = '0';

});
