<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function view()
    {

        $user = Auth::user();

        //   if ($user === null || !$user->can('create_customers')) { return redirect()->intended(route('login'));};

        return view('products.view', [
            'user' => Auth::user(),
            'products' => Product::all(),
        ]);

    }

    public function insertProductForm()
    {
        //$user = Auth::user();

        // if ($user === null || !$user->can('create_customers')) { return redirect()->intended(route('login'));};

        return view('products.create', [
            'products' => Product::all(),

        ]);

    }

    public function insertProductAction(Request $request)
    {

        //dd($request->all());
        $newProduct = new Product;
        $newProduct->name = $request->input('name');
        $newProduct->symbol = $request->input('symbol');
        $newProduct->is_gift = $request->input('is_gift');
        $newProduct->made_by_us = $request->input('made_by_us');
        $newProduct->selling_price = $request->input('selling_price');
        $newProduct->purchase_price = $request->input('purchase_price');
        $newProduct->consumed_price = $request->input('consumed_price');
        $newProduct->vat = 0.23;

        $newProduct->save();

        return back();

    }

    public function searchProduct(Request $request)
    {

        $needle = $request->input('q-name');
        $searchValues = preg_split('/\s+/', $needle, -1, PREG_SPLIT_NO_EMPTY);

        $needleSymbol = $request->input('q-symbol');

        $products = Product::where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->Where('name', 'like', "%{$value}%");
            }
        })->when($needleSymbol, function ($query, $needleSymbol) {
            return $query->where('symbol', 'like', '%' . $needleSymbol . '%');
        })->orderBy('name')->get();

        return view('products.search', [
            'needle' => $needle,
            'needleSymbol' => $needleSymbol,
            'products' => $products,
        ]);

    }

    function list() {

        return view('products.list',
            ['sidebar_item' => 'products-list']
        );
    }

    public function updateProductForm(Request $request, $id)
    {
        // $user = Auth::user();
        $product = Product::findOrFail($id);

        // if ($user === null || !$user->can('update_customers') ) { return redirect()->intended(route('login'));}

        return view('products.update', [
            'product' => $product,
            'productGift' => $product->is_gift,
            'productMade' => $product->made_by_us,

        ]);

    }

    public function updateProductAction(Request $request, $id)
    {

        $Product = Product::find($id);
        $Product->name = $request->input('name');
        $Product->symbol = $request->input('symbol');
        $Product->selling_price = $request->input('selling_price');
        $Product->purchase_price = $request->input('purchase_price');
        $Product->consumed_price = $request->input('consumed_price');
        $Product->is_gift = $request->input('is_gift');
        $Product->made_by_us = $request->input('made_by_us');

        $Product->update();

        return redirect('product/list/');

    }

    public function searchCustomersProductForm()
    {

        return view('products.form_search_customers');
    }

    public function searchCustomersProduct(Request $request)
    {

        $needle = $request->input('q-name');
        $searchValues = preg_split('/\s+/', $needle, -1, PREG_SPLIT_NO_EMPTY);

        $needleSymbol = $request->input('q-symbol');

        $customers = Customer::with('products')->whereHas('products', function ($query) use ($needle) {$query->where('name', 'like', "%{$needle}%")->orWhere('symbol', 'like', "%{$needle}%");})->paginate(20);

        return view('products.search_customers', [
            'needle' => $needle,
            'q-name' => $needle,
            'needleSymbol' => $needleSymbol,
            'customers' => $customers,
        ]);

    }
}
