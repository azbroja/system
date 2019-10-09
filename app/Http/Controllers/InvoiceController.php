<?php

namespace App\Http\Controllers;

use App\Complaint;
use App\Customer;
use App\CustomerEvent;
use App\Document;
use App\Gift;
use App\Http\Controllers\Input;
use App\Invoice;
use App\InvoiceComment;
use App\Notifications\SendComplaint;
use App\Notifications\SendListNotPaid;
use App\Notifications\SendMessageWarehouse;
use App\Notifications\SendOffer;
use App\Notifications\SendOfferPromotion;
use App\Notifications\SendWaybill;
use App\Notifications\SendPayReminder;
use App\Offer;
use App\Order;
use App\Product;
use App\Release;
use App\Rubbish;
use App\Test;
use App\User;
use App\WorkHours;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function insertForm(Request $request, $customerID)
    {

        $correction = false;
        $user = Auth::user();
        // $invoices = Invoice::orderBy('created_at', 'desc')->first();
        //dd($request->all());
        if ($request->has('releases')) {
            $invoiceProducts = Release::with('products')->findOrFail($request->input('releases'))->pluck('products')->flatten();
            $payType = 'transfer';
            $payTerm = '7';
            $invoiceComments = '';
            $customer = Customer::findOrFail($customerID);

        } elseif ($request->has('orders')) {
            $invoiceProducts = Order::with('products')->findOrFail($request->input('orders'))->pluck('products')->flatten();
            $payType = Order::findOrFail($request->input('orders'))->pluck('pay_type')->flatten()->first();
            $payTerm = Order::findOrFail($request->input('orders'))->pluck('pay_term')->flatten()->first();
            $invoiceComments = Order::findOrFail($request->input('orders'))->pluck('invoice_comments')->flatten()->first();
            $rate = '';
            $customer_id = Order::findOrFail($request->input('orders'))->pluck('customer_id')->flatten()->first();
            $customer = Customer::find($customer_id);

        } elseif ($request->has('tests')) {
            $invoiceProducts = Test::with('products')->findOrFail($request->input('tests'))->pluck('products')->flatten();
            $payType = Test::findOrFail($request->input('tests'))->pluck('pay_type')->flatten()->first();
            $payTerm = Test::findOrFail($request->input('tests'))->pluck('pay_term')->flatten()->first();
            $invoiceComments = '';
            $customer = Customer::findOrFail($customerID);

        } elseif ($request->has('invoices')) {
            $invoiceProducts = Invoice::with('products')->findOrFail($request->input('invoices'))->pluck('products')->flatten();
            $payType = Invoice::findOrFail($request->input('invoices'))->pluck('pay_type')->flatten()->first();
            $payTerm = Invoice::findOrFail($request->input('invoices'))->pluck('pay_term')->flatten()->first();
            $invoiceComments = '';
            $customer = Customer::findOrFail($customerID);

        } else {
            $invoiceProducts = collect();
            $payType = 'transfer';
            $payTerm = '14';
            $invoiceComments = '';
            $customer = Customer::findOrFail($customerID);

        };
//         $year = '2019';
//     $now = new Carbon();
//     $yearly_counter = Invoice::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter');
       
// dd($yearly_counter);
        //dd($invoiceProducts);
        //dodac action z releases

        return view('invoices.create', [
            'proForma' => '0',
            'updateInvoice' => '0',
            'invoiceId' => '0',
            'user' => $user,
            'correction' => $correction,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'invoices' => $customer->invoices(),
            'paydays' => config('invoice.paydays'),
            'seller' => Customer::where('type', 'Sprzedawca')->first(),
            'invoice_products' => $invoiceProducts,
            'payType' => $payType,
            'payTerm' => $payTerm,
            'invoiceComments' => $invoiceComments,
        ]);
    }

    public function insertAction(Request $request, Customer $customer)
    {

        $year = date('Y');
        $now = new Carbon();
        $user = User::findOrFail($customer->user_id);
        $seller = Customer::where('type', 'Sprzedawca')->first();
        $newInvoice = new Invoice;
        $newInvoice->user_id = $user->id;
        // dd($document);

        $newInvoice->yearly_counter = Invoice::whereYear('issued_at', $year)->whereNull('parent_id')->orderByDesc('yearly_counter')->value('yearly_counter') + 1;

        $newInvoice->number = 'FS ' . $newInvoice->yearly_counter . '/' . $year;

        //jak zabezpieczyc się przed kilkoma naraz index unique
        $newInvoice->customer_id = $customer->id;
        $newInvoice->issued_at = $now;
        $newInvoice->sell_date = $now;
        $newInvoice->place = 'Kraków';
        $newInvoice->is_paid = 0;

        $newInvoice->pay_type = $request->input('pay_type');

        // $newInvoice->invoice_pay_term = $request->input('invoice_pay_term');

        //$carbon = new Carbon($request->input('invoice_pay_deadline'));
        //$carbon->format('Y-m-d');
        $newInvoice->pay_deadline = (new Carbon())->addDays($request->input('pay_term'));

        // $newInvoice->invoice_total_value = $request->input('invoice_total_value');

        $newInvoice->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
        if ($request->input('purchaser') != null) {
            $newInvoice->buyer_address_ = $request->input('purchaser');
            $newInvoice->buyer_address_recipient = $request->input('buyer_address_');} else {
            $newInvoice->buyer_address_ = $request->input('buyer_address_');
        }
        // $request->input('invoice_buyer_address_recipient');
        // $newInvoice->invoice_buyer_address_ = $request->input('invoice_buyer_address_');
        //$newInvoice->invoice_buyer_address_ = 'Brak';
        // $newInvoice->invoice_comments = $request->input('invoice_comments');
        $newInvoice->comments = $request->input('comments');
        $newInvoice->net_value = -1;
        $newInvoice->total_value = -1.23;

        $newInvoice->save();
        if ($request->has('releases')) {
            Release::whereIn('id', $request->input('releases'))->update(['is_paid' => true]);
            Release::whereIn('id', $request->input('releases'))->update(['child_id' => $newInvoice->id]);

        } elseif ($request->has('orders')) {
            Order::whereIn('id', $request->input('orders'))->update(['is_paid' => true]);

        } elseif ($request->has('tests')) {
            Test::whereIn('id', $request->input('tests'))->update(['is_paid' => true]);
            Test::whereIn('id', $request->input('tests'))->update(['rate' => 'Pozytywny']);

        };

        //dd($documentType);

        //dd($request->input('invoice_products_prices'));

        //$newInvoice->products()->sync($request->input('invoice_products_prices'));

//przenalizuj czy customer id musi być w invoices

//dd($request->input('invoice_products_prices'));

        // return back();

        // $products = array_values(array_filter(
        //     $request->input('invoice_products_prices'),
        //     function (array $product) {
        //         return (int) $product['quantity'] >= 1;
        //     }
        // ));

//dd($customer->documents()->whereHas('invoices')->with('invoices')->get());
        /*
        [
        ['id' => 1, 'quantity' => 3],
        ['id' => 3, 'quantity' => 7],
        ]
         */

//          $productsIds = array_column($products, 'id'); // [1, 3]
        // dd($productsIds);

//          $pivotData = array_map(function (array $product) use ($customer) {
        //              return [
        //                  'customer_id' => $customer->id,
        //                  'quantity' => $product['quantity'],
        //                  'net_unit_price' =>$product['net_unit_price'],
        //                  'gross_unit_price' => round(($product['net_unit_price'])*1.23, 2),
        //              ];
        //          }, $products);
        // np. [['quantity' => 3], ['quantity' => 2]]

        // $attachData = array_combine($productsIds, $pivotData);

        //       $newInvoice->products()->attach($attachData);

        //brak tego configa, brak automatycznego dodawania dni do daty
        //dobrze jakbym widział ceny produktów przy fakturze gdy dodaję produkty
        //dodałem id by przypisywać produkt ale teraz pozwala przypisać kilka razy ten sam produkt do customera
        //brak automomatycznej kwoty, choć może wskoczyć po dodaniu dokumentu ale chyba byłoby lepeiej po dodaniu produktu
        //ilość produktów

        //dd($request->input('products'));
        $pivotData = array_map(
            function (int $id, float $sellingPrice, int $quantity) use ($customer) {
                return [
                    'customer_id' => $customer->id,
                    'product_id' => $id,
                    'net_unit_price' => $sellingPrice,
                    'quantity' => $quantity,
                    'gross_unit_price' => round($sellingPrice * 1.23, 2),
                ];
            },
            $request->input('products.id'),
            $request->input('products.sellingPrice'),
            $request->input('products.quantity')
        );

        $newInvoice->products()->sync($pivotData);
        $newInvoice->net_value = $newInvoice->total_sum_net();
        $newInvoice->total_value = $newInvoice->total_sum_gross();
        $newInvoice->update();
        // dd($customer->products->pluck('id'));
        //  dd($products);

        // $newRelases = Relase::find($request->input('relases'));
        // dd($newRelase);
        // $newRelases->is_paid = 1;
        // $newRelases->update();

        return redirect()->intended(route('invoices-list', [$customer]));

    }

    public function view(Request $request, $id)
    {

        $user = Auth::user();
        $invoice = Invoice::find($id);

        return response()->view(
            'invoices.invoice',
            [
                'duplicat' => false,
                'invoice' => $invoice,
                'invoice_products' => $invoice->products,
                'seller' => Customer::where('id', '=', '1')->first(),
                'sum_net' => $invoice->total_sum_net(),
                'sum_gross' => $invoice->total_sum_gross(),
            ],
            200,
            $request->has('download') ? ['Content-Disposition' => 'attachment; filename=Faktura ' . $invoice->number . '.html'] : []
        );

    }

    public function viewDuplicat($id)
    {

        $user = Auth::user();
        $invoice = Invoice::find($id);
        $now = new Carbon();

        //dd($sum);

        return view('invoices.invoice', [
            'now' => $now,
            'duplicat' => true,
            'invoice' => $invoice,
            'invoice_products' => $invoice->products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'sum_net' => $invoice->total_sum_net(),
            'sum_gross' => $invoice->total_sum_gross(),
        ]);

    }

    public function insertProFormaForm(Request $request, $customerID)
    {
        $proForma = true;

        $correction = false;
        $user = Auth::user();
        $customer = Customer::find($customerID);
        $invoices = Invoice::orderBy('created_at', 'desc')->first();
        //dd($request->all());
        if ($request->has('releases')) {
            $invoiceProducts = Release::with('products')->findOrFail($request->input('releases'))->pluck('products')->flatten();
            $payType = '';
            $payTerm = '';
            $invoiceComments = '';

        } elseif ($request->has('orders')) {
            $invoiceProducts = Order::with('products')->findOrFail($request->input('orders'))->pluck('products')->flatten();
            $payType = Order::findOrFail($request->input('orders'))->pluck('pay_type')->flatten()->first();
            $payTerm = Order::findOrFail($request->input('orders'))->pluck('pay_term')->flatten()->first();
            $invoiceComments = Order::findOrFail($request->input('orders'))->pluck('invoice_comments')->flatten()->first();
            $rate = '';

        } elseif ($request->has('tests')) {
            $invoiceProducts = Test::with('products')->findOrFail($request->input('tests'))->pluck('products')->flatten();
            $payType = Test::findOrFail($request->input('tests'))->pluck('pay_type')->flatten()->first();
            $payTerm = Test::findOrFail($request->input('tests'))->pluck('pay_term')->flatten()->first();
            $invoiceComments = '';

        } elseif ($request->has('invoices')) {
            $invoiceProducts = Invoice::with('products')->findOrFail($request->input('invoices'))->pluck('products')->flatten();
            $payType = Invoice::findOrFail($request->input('invoices'))->pluck('pay_type')->flatten()->first();
            $payTerm = Invoice::findOrFail($request->input('invoices'))->pluck('pay_term')->flatten()->first();
            $invoiceComments = '';
        } else {
            $invoiceProducts = collect();
            $payType = '';
            $payTerm = '';
            $invoiceComments = '';

        };
        //dd($invoiceProducts);
        //dodac action z releases

        return view('invoices.create-pro-forma', [
            'proForma' => '1',
            'updateInvoice' => '0',
            'invoiceId' => '0',
            'user' => $user,
            'correction' => $correction,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'invoices' => $customer->invoices(),
            'paydays' => config('invoice.paydays'),
            'seller' => Customer::where('type', 'Sprzedawca')->first(),
            'invoices' => $invoices,
            'invoice_products' => $invoiceProducts,
            'payType' => $payType,
            'payTerm' => $payTerm,
            'invoiceComments' => $invoiceComments,
        ]);
    }

    public function updateInvoiceForm(Request $request, $id)
    {

        $user = Auth::user();
        $invoice = Invoice::findOrFail($id);
        $customer = Customer::findOrFail($invoice->customer_id);
        $updateInvoice = true;
        $invoice_products = $invoice->products;
        $invoicePayterm = $invoice->pay_deadline;
        $invoicePayType = $invoice->pay_type;

        $invoicePayType;
        return view('invoices.create', [
            'proForma' => '0',
            'invoiceId' => $invoice->id,
            'updateInvoice' => $updateInvoice,
            'buyer_address_delivery' => $invoice->buyer_address_delivery,
            'offerId' => $invoice->id,
            'invoiceComments' => $invoice->comments,
            'comments' => $invoice->comments,
            'payType' => $invoicePayType,
            'paydays' => config('invoice.paydays'),
            'payTerm' => $invoicePayterm,
            'invoiceId' => $invoice->id,
            'invoice_products' => $invoice_products,
            'updateOrder' => $updateInvoice,
            'user' => $user,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'seller' => Customer::where('id', '=', '1')->first(),
        ]);

    }

    public function makeInvoiceFromProforma(Request $request, $id)
    {

        $user = Auth::user();
        $invoice = Invoice::findOrFail($id);
        $customer = Customer::findOrFail($invoice->customer_id);
        $updateInvoice = '0';
        $invoice_products = $invoice->products;
        $invoicePayterm = '0';
        $invoicePayType = $invoice->pay_type;
        $proforma_number = $invoice->number;

        $invoicePayType;
        return view('invoices.create', [
            'proForma' => '0',
            'invoiceId' => $invoice->id,
            'updateInvoice' => $updateInvoice,
            'buyer_address_delivery' => $invoice->buyer_address_delivery,
            'offerId' => $invoice->id,
            'invoiceComments' => 'Wystawiono na podstawie Faktury ' . $proforma_number,
            'comments' => $invoice->comments,
            'payType' => $invoicePayType,
            'paydays' => config('invoice.paydays'),
            'payTerm' => $invoicePayterm,
            'invoiceId' => $invoice->id,
            'invoice_products' => $invoice_products,
            'updateOrder' => $updateInvoice,
            'user' => $user,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'seller' => Customer::where('id', '=', '1')->first(),
        ]);

    }

    public function updateInvoiceAction(Request $request, $id)
    {

        $newInvoice = Invoice::find($id);
        $newInvoice->pay_type = $request->input('pay_type');
        $newInvoice->buyer_address_ = $request->input('buyer_address_');
        $newInvoice->buyer_address_recipient = $request->input('buyer_address_recipient');
        $newInvoice->comments = $request->input('comments');
        $newInvoice->save();

        // $products = array_values(array_filter(
        //     $request->products,
        //     function (array $product) {
        //         return (int) $product['quantity'] >= 1;
        //     }
        // ));

        // $productsIds = array_column($products, 'id'); // [1, 3]

        // $pivotData = array_map(function (array $product) use ($customer) {
        //     return [
        //         'customer_id' => $customer,
        //         'quantity' => $product['quantity'],
        //         'net_unit_price' =>$product['net_unit_price'],
        //         'gross_unit_price' => round(($product['net_unit_price'])*1.23, 2),
        //     ];
        // }, $products);
        // // np. [['quantity' => 3], ['quantity' => 2]]

        // $attachData = array_combine($productsIds, $pivotData);

        // $newInvoice->products()->attach($attachData);

        $newInvoice->total_value = $newInvoice->total_sum_gross();
        $newInvoice->update();

    }

    public function madeInvoiceForm($id)
    {$user = Auth::User();
        $invoice = Invoice::findOrFail($id);
        $invoice->is_paid = '1';
        $invoice->update();

        return back();

    }

    public function invoiceIncomingMade(Request $request)
    {
        $user = Auth::User();
        $invoices = $request->input('invoices');
        Invoice::whereKey($invoices)->update(['is_paid' => true]);
        return back();

    }

    public function unmadeInvoiceForm($id)
    {$user = Auth::User();
        $invoice = Invoice::findOrFail($id);
        $invoice->is_paid = '0';
        $invoice->update();

        return back();

    }

    public function insertCorrectionForm(Request $request, $id)
    {
        if ($request->has('invoices')) {
            $invoiceProducts = Invoice::with('products')->findOrFail($request->input('invoices'))->pluck('products')->flatten();
            $payType = Invoice::findOrFail($request->input('invoices'))->pluck('pay_type')->flatten()->first();
            $payTerm = Invoice::findOrFail($request->input('invoices'))->pluck('pay_term')->flatten()->first();
            $invoiceComments = '';
        }

        $correction = true;
        $user = Auth::user();
        $invoice = Invoice::find($id);
        $products = Product::all();
        $documents = Document::all();
        $customer = Customer::findOrFail($invoice->customer_id);
        // dd($invoice->products());

        // if ($user === null || !$user->can('update_customers') ) { return redirect()->intended(route('home'));}

        return view('invoices.create-correction', [
            'invoiceId' => $invoice->id,
            'updateInvoice' => '0',
            'proForma' => '0',
            'correction' => $correction,
            'invoice' => $invoice,
            'user' => $user,
            'payType' => 'transfer',
            'payTerm' => '7',
            'invoiceComments' => '',
            'paydays' => config('invoice.paydays'),
            'customer' => $customer,
            'invoice_products' => $invoice->products,
            'customer_products' => $customer->products,
            'products' => $products,
            'seller' => Customer::where('type', 'Sprzedawca')->first(),
        ]);

    }
    public function insertCorrectionAction(Request $request, $id)
    {

        $correction = true;
        $product = Product::all();
        $seller = Customer::where('type', 'Sprzedawca')->first();
        $invoice = Invoice::find($id);
        $year = date('Y');
        $now = new Carbon();
        $user = Auth::user();

        $parentInvoice = Invoice::findOrFail($id);

        $customer = Customer::findOrFail($invoice->customer_id);

        $document = new Document;

        $document->customer()->associate($customer);

        $document->save();

        $newInvoiceCorrection = new Invoice;
        $newInvoiceCorrection->user_id = $user->id;
        $newInvoiceCorrection->customer_id = $customer->id;
        $newInvoiceCorrection->parent_id = $parentInvoice->id;
        $newInvoiceCorrection->yearly_counter = Invoice::whereYear('issued_at', $year)->whereNotNull('parent_id')->orderByDesc('yearly_counter')->value('yearly_counter') + 1;

        $newInvoiceCorrection->issued_at = $now;
        $newInvoiceCorrection->sell_date = $now;
        $newInvoiceCorrection->place = 'Kraków';
        $newInvoiceCorrection->is_paid = 0;
        $newInvoiceCorrection->pay_type = $request->input('pay_type');
        $newInvoiceCorrection->pay_deadline = (new Carbon())->addDays($request->input('pay_term'));
        $newInvoiceCorrection->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
        // $newInvoiceCorrection->seller_address = $request->input('seller_address');
        $newInvoiceCorrection->number = 'FK ' . $newInvoiceCorrection->yearly_counter . '/' . $year;
        $newInvoiceCorrection->pay_type = $request->input('pay_type');
        $newInvoiceCorrection->buyer_address_ = $invoice->buyer_address_;
        $newInvoiceCorrection->buyer_address_recipient = $invoice->buyer_address_recipient;

        $newInvoiceCorrection->comments = $request->input('comments');
        // $newInvoiceCorrection->document()->associate($document);
        $newInvoiceCorrection->total_value = -1;
        $newInvoiceCorrection->save();

        $productsInput = $request->input('products');
        //dd($request->all());

        $pivotData = array_map(function (array $productInput) use ($customer) {
            return $productInput + ['customer_id' => $customer->id,
                'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
            ];}, $productsInput);

        $newInvoiceCorrection->products()->sync($pivotData);

        $newInvoiceCorrection->total_value = ($newInvoiceCorrection->total_sum_gross() - $parentInvoice->total_sum_gross());
        $newInvoiceCorrection->update();

        // $products = array_values(array_filter(
        //     $request->products,
        //     function (array $product) {
        //         return (int) $product['quantity'] >= 1;
        //     }
        // ));

        // $productsIds = array_column($products, 'id'); // [1, 3]

        // $pivotData = array_map(function (array $product) use ($customer) {
        //     return [
        //         'customer_id' => $customer,
        //         'quantity' => $product['quantity'],
        //         'net_unit_price' =>$product['net_unit_price'],
        //         'gross_unit_price' => round(($product['net_unit_price'])*1.23, 2),
        //     ];
        // }, $products);
        // // np. [['quantity' => 3], ['quantity' => 2]]

        // $attachData = array_combine($productsIds, $pivotData);

        // $newInvoice->products()->attach($attachData);

    }

    public function correctionView($id)
    {
        $correction = true;
        $user = Auth::user();
        $invoice = Invoice::findOrFail($id);
        $parentInvoice = Invoice::findOrFail($invoice->parent_id);

//         $customers = Customer::whereNotNull('acquired_at')->whereDoesntHave('invoices', function ($query) {
        //             $query->where('issued_at', '>', Carbon::now()->subMonths(6));
        //         })
        //             ->whereDoesntHave('customer_events', function ($query) {
        //                 $query->where('is_completed', '=', '0')->where('planned', '>', Carbon::now()->subMonths(3));
        //             })
        //             ->update([
        //                 'acquired_at' => null,
        //                 'user_id' => 1,
        //             ]);
        // dd($customers);

        return view('invoices.correction-view', [
            'correction' => $correction,
            'invoice' => $invoice,
            'invoice_products' => $invoice->product,
            'seller' => Customer::findOrFail(1),
            'parentInvoice' => $parentInvoice,
            'invoice_parent_products' => $parentInvoice->products,
            'invoice_products' => $invoice->products,

        ]);

    }

    function list(Customer $customer) {
        $now = new Carbon();

        $invoices = Invoice::with(['invoice_comments' => function ($query) {
            $query->latest();
        }])->where('customer_id', '=', $customer->id)->latest()->paginate(15);
        return view('invoices.list', [
            'now' => $now,
            'customer' => $customer,
            'invoices' => $invoices,
            'menu_item' => 'invoices-list',

        ]);
    }

    public function listWarehouse()
    {
        $now = new Carbon();

        $invoices = Invoice::where('issued_at', '=', $now->today())->where('incoming', '=', '0')->latest()->paginate(15);

        return view('invoices.listWarehouse', [
            'now' => $now,
            'invoices' => $invoices,
            'menu_item' => 'invoices-list-warehouse',
        ]);
    }

    public function insertOrderForm($customerID)
    {
        $updateOrder = '0';
        $correction = false;
        $user = Auth::user();
        $customer = Customer::findOrFail($customerID);
        $documents = Document::all();
        $orders = Order::orderBy('created_at', 'desc')->first();

        return view('orders.create', [
            'order_priority' => '0',
            'order_planned' => date('Y-m-d'),
            'orderId' => '0',
            'comments' => '',
            'collection' => 'courier',
            'invoice_comments' => '',
            'pay_type' => '',
            'order_type' => '',
            'document_type' => '',
            'pay_term' => '14',
            'updateOrder' => $updateOrder,
            'user' => $user,
            'paydays' => config('invoice.paydays'),
            'correction' => $correction,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'orders' => $customer->orders(),
            'documents' => $documents,
            'seller' => Customer::where('type', 'Sprzedawca')->first(),
            'orders' => $orders,
            'order_products' => collect(),
        ]);
    }

    public function insertOrderAction(Request $request, $customerID)
    {
        // dd($request->all());

        $year = date('Y');
        $now = new Carbon();
        $user = Auth::user();
        $customer = Customer::findOrFail($customerID);
        $seller = Customer::where('type', 'Sprzedawca')->first();
        $newOrder = new Order;
        $newOrder->user_id = $user->id;
        $newOrder->yearly_counter = Order::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
        $newOrder->number = 'ZL ' . $newOrder->yearly_counter . '/' . $year;
        $newOrder->customer_id = $customer->id;
        $newOrder->issued_at = $now;
        $newOrder->sell_date = $now;
        $newOrder->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
        $newOrder->buyer_address_ = $request->input('buyer_address_');
        $newOrder->buyer_address_recipient = 'NULL';
        $newOrder->comments = $request->input('comments');
        $newOrder->save();

        $productsInput = $request->input('order_products_prices');
        //dd($request->input());

        $pivotData = array_map(function (array $productInput) use ($customer) {
            return $productInput + ['customer_id' => $customer->id,
                'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
            ];}, $productsInput);

        $newOrder->products()->sync($pivotData);
        $newOrder->update();
        // dd($customer->products->pluck('id'));
        //  dd($products);

        return redirect()->intended(route('orders-list', [$customer]));
    }

    public function orderList(Customer $customer)
    {

        $orders = Order::where('customer_id', '=', $customer->id)->latest('created_at')->paginate(15);
        return view('orders.list', [
            'customer' => $customer,
            'orders' => $orders,
            'menu_item' => 'orders-list',
        ]);
    }

    public function listAllOrders(Customer $customer)
    {
        $user = Auth::User();
        $orders = Order::where('user_id', '=', $user->id)->where('incoming', '=', '0')->where('is_paid', '=', '0')->latest('created_at')->get();
        $orders_incoming = Order::where('user_id', '=', $user->id)->where('is_paid', '=', '0')->where('incoming', '=', '1')->latest('created_at')->get();

        return view('orders.listAllOrders', [
            'customer' => $customer,
            'orders_incoming' => $orders_incoming,
            'orders' => $orders,
            'menu_item' => 'listAllOrders',
        ]);
    }

    public function listAllWarehouseOrders(Customer $customer)
    {
        $user = Auth::User();
        $orders = Order::where('is_paid', '=', '0')->where('incoming', '=', '0')->orderBy('warehouse', 'asc')->orderBy('priority', 'desc')->oldest('planned')->get();
        $orders_incoming = Order::where('is_paid', '=', '0')->where('incoming', '=', '1')->latest('created_at')->get();

        return view('orders.listAllOrders', [
            'customer' => $customer,
            'orders' => $orders,
            'orders_incoming' => $orders_incoming,
            'menu_item' => 'listAllOrders',
        ]);
    }

    public function listAllProductionOrders(Customer $customer)
    {
        $user = Auth::User();
        $orders = Order::where('is_paid', '=', '0')->where('incoming', '=', '0')->orderBy('production', 'asc')->orderBy('priority', 'desc')->orderBy('yearly_counter', 'asc')->get();


        // $customerEvents = CustomerEvent::with('user')->with('customer')->where('customer_id', '=', $customer->id)->orderBy('priority', 'desc')->latest('planned')->get();
        // $orders_incoming = Order::where('is_paid', '=', '0')->where('incoming', '=', '1')->latest('created_at')->get();

        return view('orders.listAllProductionOrders', [
            'customer' => $customer,
            'orders' => $orders,
            // 'orders_incoming' => $orders_incoming,
            'menu_item' => 'listAllOrders',
        ]);
    }

    public function updateOrderForm($id)
    {
        $user = Auth::user();
        $order = Order::findOrFail($id);
        $customer = Customer::findOrFail($order->customer_id);
        $updateOrder = true;
        $order_products = $order->products;
        $orderPayterm = $order->pay_term;
        $orderPayType = $order->pay_type;
        $orderType = $order->incoming;

        return view('orders.create', [
            'order_priority' => $order->priority,
            'order_planned' => $order->planned,
            'collection' => $order->collection,
            'order' => $order,
            'buyer_address_delivery' => $order->buyer_address_delivery,
            'offerId' => $order->id,
            'invoice_comments' => $order->invoice_comments,
            'comments' => $order->comments,
            'pay_type' => $orderPayType,
            'paydays' => config('invoice.paydays'),
            'pay_term' => $orderPayterm,
            'order_type' => $orderType,
            'document_type' => $order->document_type,
            'orderId' => $order->id,
            'order_products' => $order_products,
            'updateOrder' => $updateOrder,
            'user' => $user,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'seller' => Customer::where('id', '=', '1')->first(),
        ]);

    }

    public function orderView($id)
    {

        $user = Auth::user();
        $order = Order::findOrFail($id);
        $customer = Customer::findOrFail($order->customer_id);
        $contactPerson = $customer->contact_people()->first();

        return view('orders.order', [
            'customer' => $customer,
            'order' => $order,
            'order_products' => $order->products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'contactPerson' => $contactPerson,

        ]);

    }

    public function orderProductionView($id)
    {

        $user = Auth::user();
        $order = Order::findOrFail($id);
        $customer = Customer::findOrFail($order->customer_id);
        $contactPerson = $customer->contact_people()->first();

        return view('orders.orderProduction', [
            'customer' => $customer,
            'order' => $order,
            'order_products' => $order->products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'contactPerson' => $contactPerson,

        ]);

    }

    public function insertReleaseForm($customerID)
    {
        $correction = false;
        $user = Auth::user();
        $customer = Customer::find($customerID);
        $documents = Document::all();
        $releases = Release::orderBy('created_at', 'desc')->first();

        return view('releases.create', [
            'user' => $user,
            'paydays' => config('invoice.paydays'),
            'customer' => $customer,
            'customer_products' => $customer->products,
            'releases' => $customer->releases(),
            'seller' => Customer::where('type', 'Sprzedawca')->first(),
            'releases' => $releases,
            'release_products' => collect(),
        ]);
    }

    public function insertReleaseAction(Request $request, $customerID)
    {
        $year = date('Y');
        $now = new Carbon();
        $user = Auth::user();
        $customer = Customer::findOrFail($customerID);
        $seller = Customer::where('type', 'Sprzedawca')->first();
        $newRelase = new Release;
        $newRelase->user_id = $user->id;
        $newRelase->yearly_counter = Release::whereYear('issued_at', $year)->whereNull('parent_id')->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
        $newRelase->number = 'Wydanie ' . $newRelase->yearly_counter . '/' . $year;
        $newRelase->customer_id = $customer->id;
        $newRelase->issued_at = $now;
        $newRelase->sell_date = $now;
        $newRelase->is_paid = 0;
        $newRelase->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
        $newRelase->buyer_address_ = $request->input('buyer_address_');
        $newRelase->buyer_address_recipient = 'NULL';
        $newRelase->comments = 'Brak';
        $newRelase->value = -1;
        $newRelase->save();

        $productsInput = $request->input('release_products_prices');
        $pivotData = array_map(function (array $productInput) use ($customer) {
            return $productInput + ['customer_id' => $customer->id,
                'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
            ];}, $productsInput);

        $newRelase->products()->sync($pivotData);
        $newRelase->update();

        $newRelase->value = $newRelase->total_sum();
        $newRelase->update();

        return redirect()->intended(route('releases-list', [$customer]));

    }

    public function releaseList(Customer $customer)
    {

        $releases = Release::where('customer_id', '=', $customer->id)->latest('created_at')->paginate(15);

        return view('releases.list', [
            'customer' => $customer,
            'releases' => $releases,
            'menu_item' => 'releases-list',
        ]);
    }

    public function releaseView($id)
    {

        $user = Auth::user();
        $release = Release::find($id);

        //dd($sum);

        return view('releases.release', [
            'release' => $release,
            'release_products' => $release->products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'sum_net' => $release->total_sum_net(),
            'sum_gross' => $release->total_sum_gross(),
        ]);

    }

    public function insertComplaintForm(Customer $customer)
    {
        $correction = false;
        $updateComplaint = '0';
        $user = Auth::user();
        $complaints = Complaint::orderBy('created_at', 'desc')->first();

        return view('complaints.create', [
            'complaint_comments' => '',
            'user' => $user,
            'complaintID' => '0',
            'updateComplaint' => $updateComplaint,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'complaints' => $customer->complaints(),
            'seller' => Customer::where('type', 'Sprzedawca')->first(),
            'complaint_products' => collect(),
        ]);
    }

    public function insertComplaintAction(Request $request, Customer $customer)
    {

        $year = date('Y');
        $now = new Carbon();
        $user = Auth::user();
        $seller = Customer::where('type', 'Sprzedawca')->first();
        $newComplaint = new Complaint;
        $newComplaint->user_id = $user->id;
        $newComplaint->yearly_counter = Complaint::whereYear('issued_at', $year)->whereNull('parent_id')->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
        $newComplaint->number = 'Reklamacja ' . $newComplaint->yearly_counter . '/' . $year;
        $newComplaint->customer_id = $customer->id;
        $newComplaint->issued_at = $now;
        $newComplaint->sell_date = $now;
        $newComplaint->is_paid = 0;
        $newComplaint->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
        $newComplaint->buyer_address_ = $request->input('buyer_address_');
        $newComplaint->buyer_address_recipient = 'NULL';
        $newComplaint->comments = $request->input('comments');
        $newComplaint->value = -1;
        $newComplaint->save();

        $productsInput = $request->input('complaint_products_prices');
        $pivotData = array_map(function (array $productInput) use ($customer) {
            return $productInput + ['customer_id' => $customer->id,
                'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
            ];}, $productsInput);

        $newComplaint->products()->sync($pivotData);
        $newComplaint->update();

        $newComplaint->value = $newComplaint->total_sum();
        $newComplaint->update();

        return redirect()->intended(route('complaints-list', [$customer]));

    }

    public function complaintView($id)
    {

        $user = Auth::user();
        $complaint = Complaint::findOrFail($id);
        $customer = Customer::findOrFail($complaint->customer_id);
        $contactPerson = $customer->contact_people()->first();

        return view('complaints.complaint', [
            'customer' => $customer,
            'complaintID' => $complaint->id,
            'complaint_products' => $complaint->products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'contactPerson' => $contactPerson,
            'complaint' => $complaint,
        ]);

    }

    public function updateComplaintForm($id)
    {
        $correction = false;
        $complaint = Complaint::findOrFail($id);
        $updateComplaint = true;
        $customer = Customer::findOrFail($complaint->customer_id);
        $user = Auth::user();
        $documents = Document::all();
        $complaints = Complaint::orderBy('created_at', 'desc')->first();

        return view('complaints.create', [
            'user' => $user,
            'complaint_comments' => $complaint->comments,
            'customer' => $customer,
            'complaintID' => $complaint->id,
            'complaint_products' => $complaint->products,
            'customer_products' => $customer->products,
            'updateComplaint' => $updateComplaint,
            'complaints' => $customer->complaints(),
            'seller' => Customer::where('type', 'Sprzedawca')->first(),
            'complaints' => $complaints,
        ]);
    }

    public function complaintList(Customer $customer)
    {

        $complaints = Complaint::where('customer_id', '=', $customer->id)->latest('created_at')->get();

        return view('complaints.list', [
            'customer' => $customer,
            'complaints' => $complaints,
            'menu_item' => 'complaints-list',
        ]);
    }

    public function settleComplaintForm($id)
    {
        $complaint = Complaint::findOrFail($id);
        $customer = Customer::findOrFail($complaint->customer_id);

        return view('complaints.made_complaint', [
            'complaint' => $complaint,
            'customer' => $customer,
        ]);
    }
    public function settleComplaintAction(Request $request, $id)
    {
        $user = Auth::user();

        $complaint = Complaint::findOrFail($id);
        $complaint->comments = $request->input('comments');
        $complaint->worker = $request->input('worker');
        $complaint->made_date = $request->input('date');
        $complaint->is_paid = '1';
        $complaint->update();
        $customer = Customer::findOrFail($complaint->customer_id);

        return redirect('/complaints/list/' . $customer->id);

    }

    public function updateSettleComplaintForm($id)
    {
        $complaint = Complaint::findOrFail($id);
        $customer = Customer::findOrFail($complaint->customer_id);

        return view('complaints.made_complaint', [
            'complaint' => $complaint,
            'customer' => $customer,
        ]);
    }
    public function updateSettleComplaintAction(Request $request, $id)
    {
        $user = Auth::user();

        $complaint = Complaint::findOrFail($id);
        $complaint->comments = $request->input('comments');
        $complaint->worker = $request->input('worker');
        $complaint->made_date = $request->input('date');
        $complaint->is_paid = '1';
        $complaint->update();
        $customer = Customer::findOrFail($complaint->customer_id);

        return redirect('/complaints/list/' . $customer->id);

    }

    public function listNotPaid()
    {
        $now = new Carbon();
        $now_sub_day = $now->subDays(0);

        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));

        $invoices = Invoice::with(['invoice_comments' => function ($query) {
            $query->latest();
        }])->whereHas('customer.user', function ($query) use ($userID) {$query->whereKey($userID);})->where('is_paid', '=', '0')->where('pay_deadline', '<=', $now_sub_day)->where('incoming', '=', '0')->oldest('pay_deadline')->get();

        return view('invoices.listNotPaid', [
            'now' => $now,
            'invoices' => $invoices,
            'sidebar_item' => 'listNotPaid-list',
        ]);
    }

    public function createInvoiceCommentForm($id)
    {
        $invoice = Invoice::findOrFail($id);
        $customer = Customer::findOrFail($invoice->customer_id);

        return view('invoices.comment', [
            'invoice' => $invoice,
            'customer' => $customer,
        ]);
    }
    public function createInvoiceCommentAction(Request $request, $id)
    {
        $user = Auth::user();

        $comment = new InvoiceComment;
        $comment->invoice_id = $id;
        $comment->user_id = $user->id;
        $comment->note = $request->input('note');
        $comment->save();

        $invoice = Invoice::findOrFail($id);
        $customer = Customer::findOrFail($invoice->customer_id);

        return redirect('invoices/listNotPaid');

    }

    public function searchDocuments(Request $request)
    {
        
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $userLogin = Auth::User();
        $users = User::all();
        $needle1 = $request->input('q1');
        $needle2 = $request->input('q2');
        $isPaid = $request->input('isPaid');
        $typeOfPaid = $request->input('typeOfPaid');
        $qNumber = $request->input('qNumber');
        $user = $request->input('user');
        $documentType = $request->input('documentType');
        if ($documentType == null) {
            $document = Invoice::where('number', '=', '1');
        } elseif ($documentType == 'Invoice') {
            $document = Invoice::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Order') {
            $document = Order::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Gift') {
            $document = Gift::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Rubbish') {
            $document = Rubbish::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Offer') {
            $document = Offer::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Complaint') {
            $document = Complaint::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        };

        $invoices = $document->when($qNumber, function ($query, $qNumber) {
            return $query->where('number', 'like', '%' . $qNumber . '%');
        })
            ->when($needle1, function ($query, $needle1) {
                return $query->where('issued_at', '>=', $needle1);
            })
            ->when($needle2, function ($query, $needle2) {
                return $query->where('issued_at', '<=', $needle2);
            })
            ->when($user, function ($query, $user) {
                if ($user == 'two') {
                    $user = Auth::User();
                    return $query->whereHas('customer.user', function ($query) use ($user) {$query->where('id', '=', $user->id)->orWhere('id', '=', '1');});
                } else {
                    return $query->whereHas('customer.user', function ($query) use ($user) {$query->where('id', '=', $user);});
                }
            })
            ->when($typeOfPaid, function ($query, $typeOfPaid) {
                return $query->where('pay_type', '=', $typeOfPaid);
            })
            ->get();

        return view('invoices.search_documents', [
            'isPaid' => $isPaid,
            'typeOfPaid' => $typeOfPaid,
            'qNumber' => $qNumber,
            'userChose' => $user,
            'documentType' => $documentType,
            'needle1' => $needle1,
            'needle2' => $needle2,
            'userLogin' => $userLogin,
            'userID' => $userID,
            'users' => $users,
            'invoices' => $invoices,
            'sidebar_item' => 'search-documents',

        ]);

    }

    public function allInvoicesList(Request $request)
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $userLogin = Auth::User();
        $users = User::all();
        $needle1 = $request->input('q1');
        $needle2 = $request->input('q2');
        $isPaid = $request->input('isPaid');
        $typeOfPaid = $request->input('typeOfPaid');
        $qNumber = $request->input('qNumber');
        $user = $request->input('user');
        $documentType = $request->input('documentType');

        if ($documentType == null) {
            $document = Invoice::where('number', '=', '1');
        } elseif ($documentType == 'Invoice') {
            $document = Invoice::whereNull('is_proforma')
                ->where('incoming', '=', '0')->when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Order') {
            $document = Order::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Gift') {
            $document = Gift::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Rubbish') {
            $document = Rubbish::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Offer') {
            $document = Offer::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        } elseif ($documentType == 'Complaint') {
            $document = Complaint::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        };

        $invoices = $document->when($qNumber, function ($query, $qNumber) {
            return $query->where('number', 'like', '%' . $qNumber . '%');
        })
            ->when($needle1, function ($query, $needle1) {
                return $query->where('issued_at', '>=', $needle1);
            })
            ->when($needle2, function ($query, $needle2) {
                return $query->where('issued_at', '<=', $needle2);
            })
            ->when($user, function ($query, $user) {
                if ($user == 'two') {
                    $user = Auth::User();
                    return $query->whereHas('customer.user', function ($query) use ($user) {$query->whereKey($user)->orWhere('id', '=', '1');});
                } else {
                    return $query->whereHas('customer.user', function ($query) use ($user) {$query->whereKey($user);});
                }
            })
            ->when($typeOfPaid, function ($query, $typeOfPaid) {
                return $query->where('pay_type', '=', $typeOfPaid);
            })
            ->get();
            $now = new Carbon();

            $old_invoices = Invoice::whereNull('is_proforma')
            ->where('incoming', '=', '0')->where('is_paid', '=', 0)->where('pay_deadline', '<', $now)->sum('total_value');


        return view('invoices.search_all_documents', [
            'old_invoices' => $old_invoices,
            'isPaid' => $isPaid,
            'typeOfPaid' => $typeOfPaid,
            'qNumber' => $qNumber,
            'userChose' => $user,
            'documentType' => $documentType,
            'needle1' => $needle1,
            'needle2' => $needle2,
            'userLogin' => $userLogin,
            'userID' => $userID,
            'users' => $users,
            'invoices' => $invoices,
            'sidebar_item' => 'search-documents',

        ]);

    }

    public function incomingDocuments(Request $request)
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $userLogin = Auth::User();
        $now = new Carbon();
        $today = $now->today()->format('Y-m-d');
        $thisYear = $now->today()->format('Y-01-01');
        $customerSupplier = Customer::where('type', '=', 'Dostawca')->get();
        $document = Invoice::latest();

        $invoices = $document->where('user_id', '=', $userLogin->id)->where('incoming', '=', true)->where('issued_at', '>=', $thisYear)->latest()->paginate(15);

        return view('invoices.add_incoming_documents', [
            'now' => $now,
            'customerSupplier' => $customerSupplier,
            'userLogin' => $userLogin,
            'userID' => $userID,
            'invoices' => $invoices,
            'sidebar_item' => 'search-incoming-documents',

        ]);

    }

    public function searchIncomingDocuments(Request $request)
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $userLogin = Auth::User();
        $now = new Carbon();
        $today = $now->today()->format('Y-m-d');
        $users = User::all();
        $name = $request->input('name');
        $needle1 = $request->input('q1');
        $needle2 = $request->input('q2');
        $isPaid = $request->input('isPaid');
        $typeOfPaid = $request->input('typeOfPaid');
        $qNumber = $request->input('qNumber');
        $user = $request->input('user');
        $documentType = $request->input('documentType');
        $customerSupplier = Customer::where('type', '=', 'Dostawca')->get();
        $document = Invoice::when($needle1, function ($query, $needle1) {
            return $query->where('issued_at', '>=', $needle1);
        });

        $invoices = $document->where('incoming', '=', true)->when($qNumber, function ($query, $qNumber) {
            return $query->where('number', 'like', '%' . $qNumber . '%');
        })->when($isPaid, function ($query, $isPaid) {
            return $query->where('is_paid', '=', $isPaid);
        })
            ->when($needle1, function ($query, $needle1) {
                return $query->where('issued_at', '>=', $needle1);
            })
            ->when($needle2, function ($query, $needle2) {
                return $query->where('issued_at', '<=', $needle2);
            })
            ->when($user, function ($query, $user) {
                return $query->where('user_id', '=', $user);
            })
            ->when($name, function ($query, $name) {
                return $query->where('seller_address', 'like', '%' . $name . '%');
            })
            ->when($typeOfPaid, function ($query, $typeOfPaid) {
                return $query->where('pay_type', '=', $typeOfPaid);
            })
            ->oldest('pay_deadline')->get();

        return view('invoices.search_incoming_documents', [
            'name' => $name,
            'today' => $today,
            'customerSupplier' => $customerSupplier,
            'now' => $now,
            'isPaid' => $isPaid,
            'typeOfPaid' => $typeOfPaid,
            'qNumber' => $qNumber,
            'userChose' => $user,
            'documentType' => $documentType,
            'needle1' => $needle1,
            'needle2' => $needle2,
            'userLogin' => $userLogin,
            'userID' => $userID,
            'users' => $users,
            'invoices' => $invoices,
            'sidebar_item' => 'search-incoming-documents',

        ]);

    }

    public function madeIncomingDocuments(Request $request)
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $userLogin = Auth::User();
        $now = new Carbon();
        $today = $now->today()->format('Y-m-d');
        $customerSupplier = Customer::where('type', '=', 'Dostawca')->get();

        $invoices = Invoice::where('incoming', '=', true)->where('is_paid', '=', '0')
            ->oldest('pay_deadline')->get();

        return view('invoices.made_documents', [
            'today' => $today,
            'customerSupplier' => $customerSupplier,
            'now' => $now,
            'invoices' => $invoices,

        ]);

    }

    public function insertTestForm($customerID)
    {

        $user = Auth::user();
        $customer = Customer::find($customerID);
        $documents = Document::all();
        $tests = Test::orderBy('created_at', 'desc')->first();

        return view('tests.create', [
            'user' => $user,
            'paydays' => config('invoice.paydays'),
            'customer' => $customer,
            'customer_products' => $customer->products,
            'tests' => $customer->tests(),
            'documents' => $documents,
            'seller' => Customer::where('type', 'Sprzedawca')->first(),
            'tests' => $tests,
            'test_products' => collect(),
        ]);
    }

    public function insertTestAction(Request $request, $customerID)
    {
        // dd($request->all());

        $year = date('Y');
        $now = new Carbon();
        $user = Auth::user();
        $customer = Customer::findOrFail($customerID);
        $seller = Customer::where('type', 'Sprzedawca')->first();
        $newtest = new test;
        $newtest->user_id = $user->id;
        $newtest->yearly_counter = test::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
        $newtest->number = 'ZL ' . $newtest->yearly_counter . '/' . $year;
        $newtest->customer_id = $customer->id;
        $newtest->issued_at = $now;
        $newtest->sell_date = $now;
        $newtest->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
        $newtest->buyer_address_ = $request->input('buyer_address_');
        $newtest->buyer_address_recipient = 'NULL';
        $newtest->comments = $request->input('comments');
        $newtest->save();

        $productsInput = $request->input('order_products_prices');
        //dd($request->input());

        $pivotData = array_map(function (array $productInput) use ($customer) {
            return $productInput + ['customer_id' => $customer->id,
                'gross_unit_price' => round(($productInput['net_unit_price']) * 1.23, 2),
            ];}, $productsInput);

        $newtest->products()->sync($pivotData);
        $newtest->update();
        // dd($customer->products->pluck('id'));
        //  dd($products);

        return redirect()->intended(route('tests-list', [$customer]));
    }

    public function testList(Customer $customer)
    {

        $tests = Test::where('customer_id', '=', $customer->id)->latest('created_at')->paginate(15);
        return view('tests.list', [
            'customer' => $customer,
            'tests' => $tests,
            'menu_item' => 'tests-list',
        ]);
    }

    public function testView($id)
    {

        $user = Auth::user();
        $test = Test::findOrFail($id);
        $customer = Customer::findOrFail($test->customer_id);
        $contactPerson = $customer->contact_people()->first();

        return view('tests.test', [
            'customer' => $customer,
            'test' => $test,
            'test_products' => $test->products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'contactPerson' => $contactPerson,

        ]);

    }

    public function testProtocolView($id)
    {
        $now = new Carbon();
        $user = Auth::user();
        $test = Test::findOrFail($id);
        $customer = Customer::findOrFail($test->customer_id);
        $contactPerson = $customer->contact_people()->first();

        return view('tests.protocol', [
            'now' => $now,
            'customer' => $customer,
            'test' => $test,
            'test_products' => $test->products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'contactPerson' => $contactPerson,

        ]);

    }

    public function addGiftForm($id)
    {

        $order = Order::findOrFail($id);
        $customer = Customer::findOrFail($order->customer_id);
        $order_gift = $order->products()->where('is_gift', '=', '1')->first();

        return view('gifts.create', [
            'order' => $order,
            'order_id' => $order->id,
            'order_gift' => $order_gift,
            'customer' => $customer,
            'gift_products' => $order_gift,
            'updateGift' => '0',
            'gift_id' => '1',

        ]);
    }

    public function updateGiftForm($id)
    {

        $gift = Gift::findOrFail($id);
        $customer = Customer::findOrFail($gift->customer_id);
        $gift_products = $gift->products;

        return view('gifts.create', [
            'updateGift' => true,
            'gift_products' => $gift_products,
            'gift' => $gift,
            'order_gift' => '0',
            'order_id' => '1',
            'gift_id' => $gift->id,
            'customer' => $customer,
        ]);
    }

    public function listAllTests(Customer $customer)
    {
        $user = Auth::User();
        $tests = Test::where('user_id', '=', $user->id)->where('is_paid', '=', '0')->latest('created_at')->paginate(15);
        return view('tests.listAllTests', [
            'customer' => $customer,
            'tests' => $tests,
            'menu_item' => 'listAllTests',
        ]);
    }

    public function addGiftAction(Request $request, $id)
    {
        $user = Auth::user();
        //dd($order_gift);

        $order = Order::findOrFail($id);
        $order->is_paid = '1';
        $order->update();
        $gift = new Gift;

        $gift->comments = $request->input('comments');
        $gift->is_paid = '1';
        $gift->update();

        $customer = Customer::findOrFail($gift->customer_id);

        return redirect('/gifts/list/');

    }

    public function listAllGifts()
    {
        $user = Auth::User();
        $gifts = Gift::where('user_id', '=', $user->id)->where('is_paid', '=', '0')->latest('created_at')->paginate(15);

        return view('gifts.listAllGifts', [
            'gifts' => $gifts,
            'menu_item' => 'listAllGifts',
        ]);
    }
    public function listAllGiftsWarehouse()
    {
        $user = Auth::User();
        $gifts = Gift::where('is_paid', '=', '0')->latest('created_at')->paginate(15);

        return view('gifts.listAllGifts', [
            'gifts' => $gifts,
            'menu_item' => 'listAllGifts',
        ]);
    }

    public function listGifts(Customer $customer)
    {
        $user = Auth::User();
        $gifts = Gift::where('customer_id', '=', $customer->id)->latest('created_at')->paginate(15);
        return view('gifts.list', [
            'customer' => $customer,
            'gifts' => $gifts,
            'menu_item' => 'gifts-list',
        ]);
    }

    public function giftProtocolView($id)
    {
        $now = new Carbon();
        $user = Auth::user();
        $gift = Gift::findOrFail($id);
        $customer = Customer::findOrFail($gift->customer_id);

        return view('gifts.protocol', [
            'now' => $now,
            'customer' => $customer,
            'gift' => $gift,
            'gift_products' => $gift->products,
            'seller' => Customer::where('id', '=', '1')->first(),

        ]);

    }

    public function madeGiftProtocolForm($id)
    {$user = Auth::User();
        $gift = Gift::findOrFail($id);
        $gift->is_paid = '1';
        $gift->update();

        return redirect('/gifts/listAllGifts/');

    }

    public function madeGiftProtocolAction($id)
    {$user = Auth::User();
        $gift = Gift::findOrFail($id);
        $gift->is_paid = '1';
        $gift->update();

        return redirect('/gifts/listAllGifts/');

    }

    public function negativeTestForm($id)
    {
        $test = Test::findOrFail($id);
        $customer = Customer::findOrFail($test->customer_id);

        return view('tests.made_test', [
            'test' => $test,
            'customer' => $customer,
        ]);
    }
    public function negativeTestAction(Request $request, $id)
    {
        $user = Auth::user();
        $test = Test::findOrFail($id);
        $test->comments = $request->input('comments');
        $test->rate = 'Negatywny';
        $test->is_paid = '1';
        $test->update();
        $customer = Customer::findOrFail($test->customer_id);

        return redirect('/tests/list/' . $customer->id);

    }

    public function listAllRubbishes()
    {
        $user = Auth::User();
        $rubbishes = Rubbish::latest('created_at')->where('is_paid', '=', '0')->paginate(15);

        return view('rubbishes.listAllRubbishes', [
            'rubbishes' => $rubbishes,
            'menu_item' => 'listAllRubbish',
        ]);
    }

    public function rubbishList(Customer $customer)
    {
        $user = Auth::User();
        $rubbishes = Rubbish::where('customer_id', '=', $customer->id)->latest('created_at')->paginate(15);

        return view('rubbishes.rubbishList', [
            'customer' => $customer,
            'rubbishes' => $rubbishes,
            'menu_item' => 'rubbish-list',
        ]);
    }

    public function insertRubbishForm(Customer $customer)
    {
        $user = Auth::user();

        return view('rubbishes.create', [
            'user' => $user,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'seller' => Customer::findOrFail(1),
        ]);
    }

    public function insertRubbishAction(Request $request, Customer $customer)
    {
        $year = date('Y');
        $now = new Carbon();
        $user = Auth::user();
        $seller = Customer::findOrFail(1);
        $newRubbish = new Rubbish;
        $newRubbish->user_id = $user->id;
        $newRubbish->yearly_counter = Rubbish::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
        $newRubbish->number = '' . $newRubbish->yearly_counter . '/' . $year;
        $newRubbish->customer_id = $customer->id;
        $newRubbish->issued_at = $now;
        $newRubbish->place = 'Kraków';
        $newRubbish->is_paid = 0;
        $newRubbish->seller_address = $seller->name . "\n" . $seller->street . "\n" . $seller->postal_code . ' ' . $seller->city;
        $newRubbish->buyer_address_ = $customer->name . "\n" . $customer->street . "\n" . $customer->postal_code . ' ' . $customer->city;
        $newRubbish->car_number = ' ';
        $newRubbish->value = $request->input('value');
        $newRubbish->code = $request->input('code');
        $newRubbish->type = $request->input('type');

        $newRubbish->comments = $request->input('comments');
        $newRubbish->save();

        return redirect()->intended(route('rubbish-list', [$customer]));

    }

    public function rubbishView($id)
    {
        $now = new Carbon();
        $user = Auth::user();
        $rubbish = Rubbish::findOrFail($id);
        $customer = Customer::findOrFail($rubbish->customer_id);

        return view('rubbishes.protocol', [
            'now' => $now,
            'customer' => $customer,
            'rubbish' => $rubbish,
            'seller' => Customer::where('id', '=', '1')->first(),

        ]);

    }

    public function madeRubbishForm($id)
    {
        $user = Auth::User();
        $rubbish = Rubbish::findOrFail($id);
        $customer = Customer::findOrFail($rubbish->customer_id);
        $rubbish->is_paid = '1';
        $rubbish->update();

        return redirect()->intended(route('rubbish-list', [$customer]));

    }

    public function rubbishProtocolView($id)
    {
        $now = new Carbon();
        $user = Auth::user();
        $rubbish = Rubbish::findOrFail($id);
        $customer = Customer::findOrFail($rubbish->customer_id);

        return view('rubbishes.protocol', [
            'now' => $now,
            'customer' => $customer,
            'rubbish' => $rubbish,
            'seller' => Customer::where('id', '=', '1')->first(),

        ]);

    }

    public function createInvoiceDemandForm($id)
    {$now = new Carbon();

        $user = Auth::User();
        $invoice = Invoice::findOrFail($id);
        $customer = Customer::findOrFail($invoice->customer_id);

        $comment = new InvoiceComment;
        $comment->invoice_id = $invoice->id;
        $comment->user_id = $user->id;
        $comment->note = 'Wezwanie do zapłaty';
        $comment->save();

        return view('invoices.demand', [
            'now' => $now,
            'customer' => $customer,
            'invoice' => $invoice,
            'seller' => Customer::where('id', '=', '1')->first(),

        ]);
    }

    public function listAllComplaints()
    {
        $user = Auth::User();
        $complaints = Complaint::where('user_id', '=', $user->id)->where('is_paid', '=', 0)->oldest('issued_at')->get();

        return view('complaints.listAll', [
            'complaints' => $complaints,
            'menu_item' => 'listAllComplaints',
        ]);
    }

    public function listAllComplaintsProduction()
    {
        $user = Auth::User();
        $complaints = Complaint::where('is_paid', '=', 0)->oldest('issued_at')->get();

        return view('complaints.listAll', [
            'complaints' => $complaints,
            'menu_item' => 'listAllComplaintsProduction',
        ]);
    }

    public function madeOrder($id)
    {
        $user = Auth::User();
        $order = Order::findOrFail($id);
        $order->is_paid = '1';
        $order->update();

        return redirect('orders/list/' . $order->customer_id);

    }

    public function warehouse($id)
    {
        $user = Auth::User();
        $order = Order::findOrFail($id);
        $order->warehouse = '1';
        $order->update();

        return redirect('/orders/listAllOrdersWarehouse');

    }

    public function production($id)
    {
        $user = Auth::User();
        $order = Order::findOrFail($id);
        $order->production = '1';
        $order->update();

        return redirect('/orders/listAllOrdersProduction');

    }

    public function createOrderWaybillWForm($id)
    {
        $user = Auth::User();
        $order = Order::findOrFail($id);
        $customer = Customer::findOrFail($order->customer_id);

        return view('orders.comment', [
            'customer' => $customer,
            'order' => $order,
        ]);
    }
    public function createOrderWaybillWAction(Request $request, $id)
    {
        $user = Auth::user();

        $order = Order::findOrFail($id);
        $order->waybill = $request->input('waybill');

        $order->update();
        $when = now()->addMinutes(1);

        $clientUser = User::findOrFail($order->user_id);
        $clientUser->notify((new SendMessageWarehouse($order))->delay($when));

        return redirect('/orders/listAllOrdersWarehouse');



    }

    public function createOrderWaybillForm($id)
    {
        $user = Auth::User();
        $order = Order::findOrFail($id);
        $customer = Customer::findOrFail($order->customer_id);

        return view('orders.comment', [
            'customer' => $customer,
            'order' => $order,
        ]);
    }
    public function createOrderWaybillAction(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::findOrFail($id);

        $customer = Customer::findOrFail($order->customer_id);

        $order = Order::findOrFail($id);
        $order->waybill = $request->input('waybill');
        $order->update();

        return redirect()->intended(route('orders-list', [$customer]));

    }

    public function createComplaintMessagePForm($id)
    {
        $user = Auth::User();
        $complaint = Complaint::findOrFail($id);
        $customer = Customer::findOrFail($complaint->customer_id);

        return view('complaints.comment', [
            'customer' => $customer,
            'complaint' => $complaint,
        ]);
    }
    public function createComplaintMessagePAction(Request $request, $id)
    {
        $user = Auth::user();

        $complaint = Complaint::findOrFail($id);
        $complaint->message = $request->input('message');
        $complaint->update();
        $when = now()->addMinutes(1);
        
        $clientUser = User::findOrFail($complaint->user_id);
        $clientUser->notify((new SendComplaint($complaint))->delay($when));
        $clientUser2 = User::findOrFail(5);
        $clientUser2->notify((new SendComplaint($complaint))->delay($when));

        return redirect('/');

    }

    public function createComplaintMessageForm($id)
    {
        $user = Auth::User();
        $complaint = Complaint::findOrFail($id);
        $customer = Customer::findOrFail($complaint->customer_id);

        return view('complaints.comment', [
            'customer' => $customer,
            'complaint' => $complaint,
        ]);
    }
    public function createComplaintMessageAction(Request $request, $id)
    {
        $user = Auth::user();

        $complaint = Complaint::findOrFail($id);
        $complaint->message = $request->input('message');

        $complaint->update();

        return redirect()->intended(route('complaints-list', [$customer]));

    }

    public function createEmailAction($id)
    {
        $now = Carbon::today();

        $user = Auth::user();
        $offer = Offer::findOrFail($id);

        $customer = Customer::findOrFail($offer->customer_id);
        $clientUser = User::findOrFail($offer->user_id);
        \Notification::route('mail', $customer->email)->notify(new SendOffer($offer));

        $newCustomerEvent = new CustomerEvent;
        $newCustomerEvent->user_id = $customer->user_id;
        $newCustomerEvent->customer_id = $customer->id;
        $newCustomerEvent->note = 'Wysłano ofertę';
        $newCustomerEvent->planned = $now;
        $newCustomerEvent->contact_way = 'phone';
        $newCustomerEvent->is_completed = 1;
        $newCustomerEvent->priority = '0';
        $newCustomerEvent->save();

        return redirect('/offers/list/' . $customer->id);

    }

    public function createEmailPromotionAction($id)
    {
        $now = Carbon::today();

        $user = Auth::user();
        $offer = Offer::findOrFail($id);

        $customer = Customer::findOrFail($offer->customer_id);
        $clientUser = User::findOrFail($offer->user_id);
        \Notification::route('mail', $customer->email)->notify(new SendOfferPromotion($offer));

        $newCustomerEvent = new CustomerEvent;
        $newCustomerEvent->user_id = $customer->user_id;
        $newCustomerEvent->customer_id = $customer->id;
        $newCustomerEvent->note = 'Wysłano ofertę z dołączoną promocją';
        $newCustomerEvent->planned = $now;
        $newCustomerEvent->contact_way = 'phone';
        $newCustomerEvent->is_completed = 1;
        $newCustomerEvent->priority = '0';
        $newCustomerEvent->save();

        return redirect('/offers/list/' . $customer->id);

    }

    public function generateComplaintPDF(Request $request)
    {
        $user = Auth::user() ? Auth::user() : redirect()->intended(route('login'));

        $now = new Carbon();

        $from = \Carbon\CarbonImmutable::now()->startOfMonth()->subMonth();
        $to = $from->endOfMonth();


        $complaints = Complaint::where('worker', 'like', '%' . $request->input('name'). '%')->where('issued_at', '>=', $from->format('Y-m-d'))->where('issued_at', '<=', $to->format('Y-m-d'))->where('is_paid', '=', 1)->get();

        $data = [
            'complaints' => $complaints,
        ];
        $pdf = mb_convert_encoding(\View::make('pdf.complaints', $data), 'HTML-ENTITIES', 'UTF-8');

        return \PDF::loadHtml($pdf)->download('Lista_reklamacji.pdf');
    }

    public function generateInvoicePDF($id)
    {
        $invoice = Invoice::findOrFail($id);

        $data = ['duplicat' => false,
            'invoice' => $invoice,
            'invoice_products' => $invoice->products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'sum_net' => $invoice->total_sum_net(),
            'sum_gross' => $invoice->total_sum_gross(),
        ];
        $pdf = mb_convert_encoding(\View::make('pdf.invoice', $data), 'HTML-ENTITIES', 'UTF-8');

        return \PDF::loadHtml($pdf)->download('Faktura_' . $invoice->number . '.pdf');
    }

    public function generateDemandPDF($id)
    {
        $now = Carbon::today();
        $user = Auth::User();
        $invoice = Invoice::findOrFail($id);
        $customer = Customer::findOrFail($invoice->customer_id);

        $data = [
            'invoice' => $invoice,
            'now' => $now,
            'customer' => $customer,
            'invoice' => $invoice,
            'seller' => Customer::where('id', '=', '1')->first(),
        ];
        $pdf = mb_convert_encoding(\View::make('pdf.demand', $data), 'HTML-ENTITIES', 'UTF-8');

        return \PDF::loadHtml($pdf)->download('Wezwanie_FV_nr_' . $invoice->number . '.pdf');
    }

    public function generateHoursPDF(Request $request)
    {
        $id = $request->input('user_s');
        $month = $request->input('month');
        $user = User::findOrFail($id);
        $now = Carbon::today();

        $working_hours = WorkHours::where('user_id', '=', $user->id)->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->get();
        $working_hours_8 = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '1')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();

        $working_hours_w = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '2')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();

        $working_hours_o = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '3')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();

        $working_hours_wnz = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '4')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();

        $working_hours_ch = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '5')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();

        $working_hours_n = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '6')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();

        $working_hours_m = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '7')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();

        $working_hours_k = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '8')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();

        $working_hours_p = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '9')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $month)
            ->oldest('date')->count();
        $data = [
            'user' => $user,
            'now' => $now,
            'month' => $month,
            'working_hours' => $working_hours,
            'working_hours_8' => $working_hours_8,
            'working_hours_w' => $working_hours_w,
            'working_hours_o' => $working_hours_o,
            'working_hours_wnz' => $working_hours_wnz,
            'working_hours_ch' => $working_hours_ch,
            'working_hours_n' => $working_hours_n,
            'working_hours_m' => $working_hours_m,
            'working_hours_k' => $working_hours_k,
            'working_hours_p' => $working_hours_p,
        ];

        $pdf = mb_convert_encoding(\View::make('pdf.all_hours_list', $data), 'HTML-ENTITIES', 'UTF-8');

        return \PDF::loadHtml($pdf)->download('Karta.pdf');
    }

    public function addIncomingInvoice(Request $request)
    {
        dd($request->all());
        $year = date('Y');
        $now = new Carbon();
        $buyer = Customer::findOrFail(1);
        $customer = Customer::findOrFail($request->input('supplierId'));
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
        $newInvoice->net_value = $request->input('netValue');
        $newInvoice->total_value = $request->input('grossValue');
        $newInvoice->invoice_type = $request->input('invoiceType');
        $newInvoice->save();

        return redirect('/invoices-incoming/add/');

    }

    public function searchComplaints(Request $request)
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $userLogin = Auth::User();
        $users = User::all();
        $name = $request->input('name');
        $needle1 = $request->input('q1');
        $needle2 = $request->input('q2');
        $isPaid = $request->input('isPaid');
        $typeOfPaid = $request->input('typeOfPaid');
        $qNumber = $request->input('qNumber');
        $user = $request->input('user');
        $documentType = $request->input('documentType');

        if ($documentType == null) {
            $document = Invoice::where('number', '=', '1');
        } elseif ($documentType == 'Complaint') {
            $document = Complaint::when($isPaid, function ($query, $isPaid) {
                return $query->where('is_paid', '=', $isPaid);
            });
        };

        $invoices = $document->when($qNumber, function ($query, $qNumber) {
            return $query->where('number', 'like', '%' . $qNumber . '%');
        })
            ->when($name, function ($query, $name) {
                return $query->where('worker', 'like', '%' . $name . '%');
            })
            ->when($needle1, function ($query, $needle1) {
                return $query->where('issued_at', '>=', $needle1);
            })
            ->when($needle2, function ($query, $needle2) {
                return $query->where('issued_at', '<=', $needle2);
            })
            ->get();


        return view('complaints.search_complaints', [
            'isPaid' => $isPaid,
            'name' => $name,
            'typeOfPaid' => $typeOfPaid,
            'qNumber' => $qNumber,
            'userChose' => $user,
            'documentType' => $documentType,
            'needle1' => $needle1,
            'needle2' => $needle2,
            'userLogin' => $userLogin,
            'userID' => $userID,
            'users' => $users,
            'invoices' => $invoices,
            'sidebar_item' => 'search-complaints',

        ]);

    }

    public function sendWaybill($id)
    {
        $now = Carbon::today();

        $user = Auth::user();
        $order = Order::findOrFail($id);

        $customer = Customer::findOrFail($order->customer_id);
        $clientUser = User::findOrFail($order->user_id);
        \Notification::route('mail', $customer->email)->notify(new SendWaybill($order));

        return redirect('/orders/list/' . $customer->id);

    }


    public function sendPayReminder($id)
    {
        $now = Carbon::today();
        $user = Auth::user();
        $invoice = Invoice::findOrFail($id);

        $customer = Customer::findOrFail($invoice->customer_id);
        $clientUser = User::findOrFail($invoice->user_id);
        \Notification::route('mail', $customer->email)->notify(new SendPayReminder($invoice));

        $comment = new InvoiceComment;
        $comment->invoice_id = $id;
        $comment->user_id = $user->id;
        $comment->note = 'Wysłano przypomnienie @';
        $comment->save();

        return redirect('/invoices/list/' . $customer->id);

    }

    

    public function sendOldInvoices()
    {

        $now = new Carbon();
        $now_sub_day = $now->subDays(0);

        $user = Auth::user() ? Auth::user() : redirect()->intended(route('login'));

        $invoices = Invoice::with(['invoice_comments' => function ($query) {
            $query->latest();
        }])->whereHas('customer.user', function ($query) use ($user) {$query->whereKey($user->id);})->where('is_paid', '=', '0')->where('pay_deadline', '<=', $now_sub_day)->where('incoming', '=', '0')->oldest('pay_deadline')->get();

        \Notification::route('mail', 'biuro@.pl')->notify(new SendListNotPaid($invoices));

        return redirect('/invoices/listNotPaid');

    }

    public function generateXml()
    {

        $now = new Carbon();

        $from = \Carbon\CarbonImmutable::now()->startOfMonth()->subMonth();
        $to = $from->endOfMonth();

        $user = Auth::user() ? Auth::user() : redirect()->intended(route('login'));

        $invoices = Invoice::where('issued_at', '>=', $from->format('Y-m-d'))->where('issued_at', '<=', $to->format('Y-m-d'))->where('incoming', '=', 0)->where('is_proforma','=', null)->get();

        $seller = Customer::findOrFail(1);

    $invoicesMap = collect(['Naglowek' => ['KodFormularza' => [
        'attributes' => [
            'kodSystemowy' => 'JPK_VAT (3)',
            'wersjaSchemy' => '1-1',
        ],
        'value' => 'JPK_VAT',
    ],
    'WariantFormularza' => '3',
    'CelZlozenia' => '0',
    'DataWytworzeniaJPK' => $now->format('Y-m-d\TH:i:s'),
    'DataOd' => $from->format('Y-m-d'),
    'DataDo' => $to->format('Y-m-d'),
    'NazwaSystemu' => 'eDek - Elektroniczne Deklaracje</NazwaSystemu',

],
'Podmiot1' => [
        'NIP' => $seller->nip,
        'PelnaNazwa' => $seller->name,
    
  
]]
)->merge($invoices->map(function (Invoice $invoice, int $key) {
        return ['SprzedazWiersz' => [ 
            'LpSprzedazy' => $key+1,
            'NrKontrahenta' => $invoice['buyer_address__nip'] ? $invoice['buyer_address__nip'] : 'BRAK',
            'NazwaKontrahenta' => $invoice['buyer_address__name'],
            'AdresKontrahenta' => $invoice['buyer_address__address'].' '.$invoice['buyer_address__postal_code'].' '.$invoice['buyer_address__city'],
            'DowodSprzedazy' => $invoice['number'],
            'DataWystawienia' => date('Y-m-d', strtotime($invoice['issued_at'])),
            'DataSprzedazy' => date('Y-m-d', strtotime($invoice['sell_date'])),
            'K_19' => $invoice['net_value'],
            'K_20' => ($invoice['total_value']-$invoice['net_value'])
        ]];
    }, $invoices)->merge([
        'SprzedazCtrl' => [
        'LiczbaWierszySprzedazy' => $invoices->count(),
        'PodatekNalezny' => number_format(($invoices->sum('total_value') - $invoices->sum('net_value')), 2, '.', ''),
                ]]))->toArray();




    $xmlWriter = new \Sabre\Xml\Writer();

    $xmlWriter->openMemory();
$xmlWriter->setIndent(true);
$xmlWriter->startDocument('1.0', 'utf-8');
$xmlWriter->writeElement('JPK', function (\Sabre\Xml\Writer $writer) use ($invoicesMap) {
    $writer->writeAttribute('xmlns:etd','http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2016/01/25/eD/DefinicjeTypy/');
    $writer->writeAttribute('xmlns','http://jpk.mf.gov.pl/wzor/2017/11/13/1113/');   
     $writer->write($invoicesMap);
});

return response($xmlWriter->outputMemory())->header('Content-Type', 'text/xml; charset=utf-8');
    }

}
