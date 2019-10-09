<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Document;
use App\Http\Controllers\Input;
use App\Offer;
use App\User;
use Auth;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function insertOfferForm($customerID)
    {
        $user = Auth::user();
        $customer = Customer::find($customerID);
        $updateOffer = '0';
        $offerProducts = collect();

        return view('offers.create', [
            'offerId' => '0',
            'offerProducts' => $offerProducts,
            'updateOffer' => $updateOffer,
            'user' => $user,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'seller' => Customer::where('id', '=', '1')->first(),
        ]);

    }

    public function insertOfferAction(Request $request, $customerID)
    {
        dd($request->all());
        //$product = Product::all();
        $customer = Customer::find($customerID);

        $year = date('Y');

        $user = Auth::user();
        $customer = Customer::findOrFail($customerID);
        $seller = Customer::where('type', 'Sprzedawca')->first();
        $newOffer = new Offer;
        $newOffer->user_id = $user->id;
        $newOffer->customer_id = $customer->id;

        $newOffer->yearly_counter = Offer::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
        $newOffer->number = 'OF ' . $newOffer->yearly_counter . '/' . $year;
        $newOffer->issued_at = date("Y-m-d");
        $newOffer->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
        $newOffer->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;

        $document = new Document;
        $document->customer()->associate($customer);

        $document->save();

        $newOffer->document()->associate($document);

        $newOffer->save();

        $products = $request->products;

        // $products = array_values(array_filter(
        //     $request->products,
        //     function (array $product) {
        //         return (int) $product['quantity'] >= 1;
        //     }
        // ));

        // $productsIds = array_column($products, 'id'); // [1, 3]

        // $pivotData = array_map(function (array $product) use ($customer) {
        //     return [
        //         'customer_id' => $customer->id,
        //         'net_unit_price' =>$product['net_unit_price'],
        //         'gross_unit_price' => round(($product['net_unit_price'])*1.23, 2),
        //     ];
        // }, $products);

        // $attachData = array_combine($productsIds, $pivotData);

        // $newOffer->products()->attach($attachData);
        // $customer->products()->sync($request->input('customer_prices'));
        //dd($request->input('customer_prices'));
        $productsInput = $request->input('customer_prices');
        $pivotData = array_map(function (array $productInput) use ($customer) {
            return $productInput + ['customer_id' => $customer->id,
                'selling_customer_price' => $productInput['selling_customer_price'],
                'purchase_customer_price' => $productInput['purchase_customer_price'],
                'consumed_customer_price' => $productInput['consumed_customer_price'],

            ];}, $productsInput);

//dd($productInput);

        $newOffer->products()->sync($pivotData);

        return back();

    }

    public function updateOfferForm($id)
    {
        $user = Auth::user();
        $offer = Offer::findOrFail($id);
        $customer = Customer::findOrFail($offer->customer_id);
        $updateOffer = true;
        $offerProducts = $offer->products;

        return view('offers.create', [
            'offerId' => $offer->id,
            'offerProducts' => $offerProducts,
            'updateOffer' => $updateOffer,
            'user' => $user,
            'customer' => $customer,
            'customer_products' => $customer->products,
            'seller' => Customer::where('id', '=', '1')->first(),
        ]);

    }

    public function updateOfferAction(Request $request, $id)
    {
        dd($request->all());
        //$product = Product::all();
        $customer = Customer::find($customerID);

        $year = date('Y');

        $user = Auth::user();
        $customer = Customer::findOrFail($customerID);
        $seller = Customer::where('type', 'Sprzedawca')->first();
        $newOffer = new Offer;
        $newOffer->user_id = $user->id;
        $newOffer->customer_id = $customer->id;

        $newOffer->yearly_counter = Offer::whereYear('issued_at', $year)->orderByDesc('yearly_counter')->value('yearly_counter') + 1;
        $newOffer->number = 'OF ' . $newOffer->yearly_counter . '/' . $year;
        $newOffer->issued_at = date("Y-m-d");
        $newOffer->seller_address = $seller->name . ', ' . $seller->street . ', ' . $seller->postal_code . ' ' . $seller->city . ', NIP: ' . $seller->nip;
        $newOffer->buyer_address_ = $customer->name . ', ' . $customer->street . ', ' . $customer->postal_code . ' ' . $customer->city . ', NIP: ' . $customer->nip;

        $document = new Document;
        $document->customer()->associate($customer);

        $document->save();

        $newOffer->document()->associate($document);

        $newOffer->save();

        $products = $request->products;

        // $products = array_values(array_filter(
        //     $request->products,
        //     function (array $product) {
        //         return (int) $product['quantity'] >= 1;
        //     }
        // ));

        // $productsIds = array_column($products, 'id'); // [1, 3]

        // $pivotData = array_map(function (array $product) use ($customer) {
        //     return [
        //         'customer_id' => $customer->id,
        //         'net_unit_price' =>$product['net_unit_price'],
        //         'gross_unit_price' => round(($product['net_unit_price'])*1.23, 2),
        //     ];
        // }, $products);

        // $attachData = array_combine($productsIds, $pivotData);

        // $newOffer->products()->attach($attachData);
        // $customer->products()->sync($request->input('customer_prices'));
        //dd($request->input('customer_prices'));
        $productsInput = $request->input('customer_prices');
        $pivotData = array_map(function (array $productInput) use ($customer) {
            return $productInput + ['customer_id' => $customer->id,
                'selling_customer_price' => $productInput['selling_customer_price'],
                'purchase_customer_price' => $productInput['purchase_customer_price'],
                'consumed_customer_price' => $productInput['consumed_customer_price'],

            ];}, $productsInput);

//dd($productInput);

        $newOffer->products()->sync($pivotData);

        return back();

    }

    public function view($id)
    {

        $offer = Offer::find($id);
        $user = User::find($offer->user_id);
        $customer = Customer::find($offer->customer_id);
        $contactPerson = $customer->contact_people()->first();
        $offer_products = $offer->products->groupBy('made_by_us')->sortByDesc('name');
        //dd($sum);

        return view('offers.offer', [
            'user' => $user,
            'customer' => $customer,
            'offer' => $offer,
            'offer_products' => $offer_products,
            'seller' => Customer::where('id', '=', '1')->first(),
            'contactPerson' => $contactPerson,

        ]);
    }

    function list(Customer $customer) {
        $user = User::find($customer->user_id);

        $offers = Offer::where('customer_id', '=', $customer->id)->latest('created_at')->paginate(15);
        //dd($offers);

        return view('offers.list', [
            'user' => $user,
            'customer' => $customer,
            'offers' => $offers,
            'menu_item' => 'offers-list',

        ]);
    }
}
