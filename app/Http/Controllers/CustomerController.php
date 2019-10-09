<?php

namespace App\Http\Controllers;

use App\ContactPerson;
use App\Customer;
use App\CustomerEvent;
use App\Http\Controllers\Input;
use App\Product;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function view()
    {

        $user = Auth::user();

        //  if ($user === null || !$user->can('create_customers')) { return abort(403, 'Brak Uprawnień.');};

        return view('customers.view', [
            'user' => Auth::user(),
            'customer' => Customer::all(),
        ]);

    }

    public function insertForm()
    {
        $user = Auth::user();

        return view('customers.create', [
            'user' => $user,
            // 'customer' => Customer::all(),
            // 'permissions' => Permission::all(),
            // 'roles' => Role::all(),
            'sidebar_item' => 'create-customer',

        ]);

    }

    public function insertAction(Request $request)
    {

        $user = Auth::user();

        //dd($request->all());
        $newCustomer = new Customer;
        $newCustomer->user_id = $user->id;
        $newCustomer->name = $request->input('name');
        $newCustomer->city = $request->input('city');
        $newCustomer->postal_code = $request->input('postal_code');
        $newCustomer->street = $request->input('street');
        $newCustomer->regon = $request->input('regon');
        $newCustomer->bdo_number = $request->input('bdo_number');
        $newCustomer->nip = $request->input('nip');
        $newCustomer->telephone1 = $request->input('telephone1');
        $newCustomer->telephone2 = $request->input('telephone2');
        $newCustomer->fax = $request->input('fax');
        $newCustomer->email = $request->input('email');
        $newCustomer->www = $request->input('www');
        $newCustomer->comments = $request->input('comments');
        $newCustomer->type = $request->input('type');
        $newCustomer->address_delivery = $request->input('address_delivery');
        $newCustomer->purchaser = $request->input('purchaser');
        $newCustomer->save();

        return redirect()->intended(route('customer-update', [$newCustomer->id]));

    }

    public function updateForm(Request $request, Customer $customer)
    {
        $now = new Carbon();

        $user = Auth::user();
        $users = User::all()->where('is_seller', '=', true);

        $customer_user = User::findOrFail($customer->user_id);
        $today = $now->today()->format('Y-m-d');

        // if ($user === null || !$user->can('update_customers') ) { return redirect()->intended(route('home'));}

        return view('customers.update', [
            'today' => $today,
            'customer_user' => $customer_user,
            'customer' => $customer,
            'user' => $user,
            'users' => $users,
            'activeMenuElement' => 'class="menu-item-customer-update active"',
            'menu_item' => 'customer-update',
        ]);

    }

    public function updateAction(Request $request, $id)
    {

        //dd($request->input());

        $newCustomer = Customer::find($id);
        $newCustomer->name = $request->input('name');
        $newCustomer->user_id = $request->input('user');
        if ($request->input('user') == null) {
            return redirect()->intended(route('customer-update', [$newCustomer->id]));
        }

        $newCustomer->city = $request->input('city');
        $newCustomer->postal_code = $request->input('postal_code');
        $newCustomer->street = $request->input('street');
        $newCustomer->regon = $request->input('regon');
        $newCustomer->bdo_number = $request->input('bdo_number');
        if ($newCustomer->acquired_at == null) {
            $newCustomer->acquired_at = $request->input('acquired_at');
        }
        $newCustomer->nip = $request->input('nip');
        $newCustomer->telephone1 = $request->input('telephone1');
        $newCustomer->telephone2 = $request->input('telephone2');
        $newCustomer->fax = $request->input('fax');
        $newCustomer->email = $request->input('email');
        $newCustomer->www = $request->input('www');
        $newCustomer->comments = $request->input('comments');
        $newCustomer->type = $request->input('type');
        $newCustomer->address_delivery = $request->input('address_delivery');
        $newCustomer->purchaser = $request->input('purchaser');

        $newCustomer->save();

        return back();

    }

    public function insertContactPersonForm(Customer $customer)
    {
        $user = Auth::user();

        return view('contact_people.create', [
            'user' => $user,
            'customer' => $customer,
        ]);

    }

    public function insertContactPersonAction(Request $request, Customer $customer)
    {

        $user = Auth::user();

        $newContactPerson = new ContactPerson;
        $newContactPerson->customer_id = $customer->id;
        $newContactPerson->name = $request->input('name');
        $newContactPerson->surname = $request->input('surname');
        $newContactPerson->telephone1 = $request->input('telephone1');
        $newContactPerson->telephone2 = $request->input('telephone2');
        $newContactPerson->email = $request->input('email');
        $newContactPerson->comments = $request->input('comments');
        $newContactPerson->save();

        return back();

    }

    public function updateContactPersonForm(Request $request, $id)
    {

        $contactPerson = ContactPerson::find($id);
        $customer = Customer::find($contactPerson->customer_id);

        return view('contact_people.update', [
            'contactPerson' => $contactPerson,
            'customer' => $customer,
        ]);

    }

    public function updateContactPersonAction(Request $request, $id)
    {

        $contactPerson = ContactPerson::find($id);
        $contactPerson->name = $request->input('name');
        $contactPerson->surname = $request->input('surname');
        $contactPerson->telephone1 = $request->input('telephone1');
        $contactPerson->telephone2 = $request->input('telephone2');
        $contactPerson->email = $request->input('email');
        $contactPerson->comments = $request->input('comments');
        $contactPerson->save();

        return back();
    }

    public function insertCustomerEventForm(Customer $customer)
    {
        $user = Auth::user();

        return view(
            'customer_events.create',
            [
                'user' => $user,
                'customer' => $customer,

            ]
        );
    }

    public function insertCustomerEventAction(Request $request, Customer $customer)
    {

        $user = Auth::user();
        $newCustomerEvent = new CustomerEvent;
        $newCustomerEvent->user_id = $user->id;
        $newCustomerEvent->customer_id = $customer->id;
        $newCustomerEvent->note = $request->input('note');
        $newCustomerEvent->planned = $request->input('planned');
        $newCustomerEvent->contact_way = $request->input('contact_way');
        $newCustomerEvent->is_completed = $request->input('is_completed');
        $newCustomerEvent->priority = $request->input('priority');
        $newCustomerEvent->note = $request->input('note');
        $newCustomerEvent->save();

        return redirect()->intended(route('events-list', [$customer]));

    }

    public function updateCustomerEventForm(Request $request, $id)
    {
        $user = Auth::User();

        $customer_event = CustomerEvent::findOrFail($id);
        $customer = Customer::findOrFail($customer_event->customer_id);

        if ($user->id != $customer->user_id) {
            return redirect()->intended(route('events-list', [$customer->id]));
        }
        //$contact_ways = CustomerEvent::all()->pluck('contact_way');

        return view('customer_events.update', [
            'customer_event' => $customer_event,
            'customer' => $customer,
            // 'contact_ways' => $contact_ways,
        ]);

    }

    public function updateCustomerEventAction(Request $request, $id)
    {
        $CustomerEvent = CustomerEvent::findOrFail($id);
        $customer = Customer::find($CustomerEvent->customer_id);
        $CustomerEvent->note = $request->input('note');
        $CustomerEvent->planned = $request->input('planned');
        $CustomerEvent->contact_way = $request->input('contact_way');
        $CustomerEvent->is_completed = $request->input('is_completed');
        $CustomerEvent->priority = $request->input('priority');
        $CustomerEvent->note = $request->input('note');
        $CustomerEvent->save();

        return redirect()->intended(route('events-list', [$customer->id]));
    }

    public function customerEventslist(Customer $customer)
    {
        $customerEvents = CustomerEvent::with('user')->with('customer')->where('customer_id', '=', $customer->id)->orderBy('priority', 'desc')->latest('planned')->get();

        return view('customer_events.list', [
            'customer' => $customer,
            'customerEvents' => $customerEvents,
            'menu_item' => 'events-list',
        ]
        );
    }

    public function insertCustomerProductForm($customerID, $productID)
    {
        $user = Auth::user();
        //$customer = Customer::find($note->customer_id);

        return view('customer_products.create', [
            'user' => $user,
            'customer' => Customer::find($customerID),
            'product' => Product::find($productID),
        ]);

    }

    public function insertCustomerProductAction(Request $request, $customerID, $productID)
    {

        //$user = Auth::user();
        $product = Product::findOrFail($productID);
        $customer = Customer::findOrFail($customerID);

        $customer->products()->save($product, [
            'selling_customer_price' => $request->input('selling_customer_price'),
            'purchase_customer_price' => $request->input('purchase_customer_price'),
            'consumed_customer_price' => $request->input('consumed_customer_price'),
        ]);

        return back();

        //save zapisuje do id

    }

    public function updateCustomerProductForm(Customer $customer)
    {
        $customer = Customer::findOrFail($customer->id);

        return view('customer_products.update', [
            'customer' => $customer,
            'menu_item' => 'update-customer-product',

        ]);

    }
    public function updateCustomerProductAction(Request $request, Customer $customer)
    {
        //  dd($request->input());

        $customer = Customer::findOrFail($customer);

        $customer->products()->sync($request->input('customer_prices'));

        return back();

//przykładowa tablica do synca

        // $customer->products()->sync([
        //     1 => ['selling_customer_price' => 5.87,
        //           'purchase_customer_price' => 4.66,
        //           'consumed_customer_price' => 5.66,
        //             ],
        //     2 = >
        // ]);
        //sync dodaje zapisuje i usuwa - wartosci przekazanej tablicy

//product pobiera relację
        //zrob listę produktów klita i update
        //bez () zwróci collection
        //update existing pivot check version laravel
        //seedy

    }

    public function search(Request $request)
    {
        $needle = $request->input('q');
        $searchValues = preg_split('/\s+/', $needle, -1, PREG_SPLIT_NO_EMPTY);
        $searchValues2 = array_reverse($searchValues);

        $glue = implode("%", $searchValues);
        $glue2 = implode("%", $searchValues2);

        if ($needle === null) {
            $customers = collect();
        } else {

            $customers = Customer::where('name', 'like', "%{$glue}%")->orWhere('name', 'like', "%{$glue2}%")->orWhere('nip', 'like', "%{$glue}%")->orderBy('name')->get();

        }
        return view('search', [
            'needle' => $needle,
            'customers' => $customers,
        ]);
    }

    public function searchCustomer(Request $request)
    {

        $needle = $request->input('q');
        $needleCity = $request->input('q-city');
        $needleNip = $request->input('q-nip');
        $needlePerson = $request->input('q-person');
        $needleStreet = $request->input('q-street');
        $needleEmail = $request->input('q-email');
        $needleDelivery = $request->input('q-delivery');
        $needlePurchaser = $request->input('q-purchaser');

        $customers = Customer::when($needle, function ($query, $needle) {
            return $query->where('name', 'like', '%' . $needle . '%');
        })->when($needleCity, function ($query, $needleCity) {
            return $query->where('city', 'like', '%' . $needleCity . '%');
        })->when($needleNip, function ($query, $needleNip) {
            return $query->where('nip', 'like', '%' . $needleNip . '%');
        })->when($needlePerson, function ($query, $needlePerson) {
            return $query->whereHas('contact_people', function ($query) use ($needlePerson) {$query->where('surname', 'like', '%' . $needlePerson . '%');});
        })->when($needleStreet, function ($query, $needleStreet) {
            return $query->where('street', 'like', '%' . $needleStreet . '%');
        })
            ->when($needleEmail, function ($query, $needleEmail) {
                return $query->where('email', 'like', '%' . $needleEmail . '%');
            })
            ->when($needleDelivery, function ($query, $needleDelivery) {
                return $query->where('address_delivery', 'like', '%' . $needleDelivery . '%');
            })
            ->when($needlePurchaser, function ($query, $needlePurchaser) {
                return $query->where('purchaser', 'like', '%' . $needlePurchaser . '%');
            })
            ->orderBy('name')->get();

        return view('customers.search', [
            'needle' => $needle,
            'needleDelivery' => $needleDelivery,
            'needleEmail' => $needleEmail,
            'needleCity' => $needleCity,
            'needlePerson' => $needlePerson,
            'needleStreet' => $needleStreet,
            'needleNip' => $needleNip,
            'customers' => $customers,
        ]);

    }

    public function contactPersonlist(Customer $customer)
    {
        $contact_person = $customer->contact_person;

        return view('contact_people.list',
            ['customer' => $customer,
                'menu_item' => 'contact-person-list']
        );
    }

    function list() {

        return view('customers.list',
            ['sidebar_item' => 'customers-list']
        );
    }

    public function deleteContactPerson($id)
    {
        $contactPerson = ContactPerson::findOrFail($id);
        $customerID = $contactPerson->customer_id;
        $contactPerson->delete();

        return redirect('contact-person/list/' . $customerID);

    }

    public function insertIncomingForm()
    {
        $user = Auth::user();

        return view('customers.create_incoimig', [
            'user' => $user,
        ]);

    }

    public function insertIncomingAction(Request $request)
    {

        $user = Auth::user();

        //dd($request->all());
        $newCustomer = new Customer;
        $newCustomer->user_id = $user->id;
        $newCustomer->name = $request->input('name');
        $newCustomer->city = $request->input('city');
        $newCustomer->postal_code = $request->input('postal_code');
        $newCustomer->street = $request->input('street');
        $newCustomer->regon = $request->input('regon');
        $newCustomer->nip = $request->input('nip');
        $newCustomer->telephone1 = $request->input('telephone1');
        $newCustomer->telephone2 = $request->input('telephone2');
        $newCustomer->fax = $request->input('fax');
        $newCustomer->email = $request->input('email');
        $newCustomer->www = $request->input('www');
        $newCustomer->comments = $request->input('comments');
        $newCustomer->type = $request->input('type');
        $newCustomer->address_delivery = $request->input('address_delivery');
        $newCustomer->purchaser = 'Brak';
        $newCustomer->bank_account_number = $request->input('bankc_account_number');
        $newCustomer->save();

        return back();

    }

    public function resetAcquired($id)
    {
        $user = Auth::User();
        $customer = Customer::findOrFail($id);
        $customer->acquired_at = null;
        $customer->update();

        return redirect('customer/update/' . $customer->id);

    }

}
