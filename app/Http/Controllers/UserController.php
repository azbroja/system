<?php
namespace App\Http\Controllers;

use App\CustomerEvent;
use App\Http\Controllers\Input;
use App\InvoiceProduct;
use App\OrderProduct;
use App\Permission;
use App\Product;
use App\Role;
use App\User;
use App\WorkHours;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function view()
    {

        $user = Auth::user();

        return view('users.view', [
            'user' => Auth::user(),
        ]);

    }

    public function insertForm()
    {
        $user = Auth::user();

        // if ($user === null || !$user->can('create_users')) { return redirect()->intended(route('login'));};

        return view('users.create', [
            'user' => $user,
            'permissions' => Permission::all(),
            'roles' => Role::all(),
        ]
        );

    }

    public function insertAction(Request $request)
    {

        $newUser = new User;
        $newUser->name = $request->input('name');
        $newUser->surname = $request->input('surname');
        $newUser->email = $request->input('email');
        $newUser->password = Hash::make($request->input('password'));
        $newUser->save();

        $newUser->permissions()->attach($request->input('permissions', []));

    }

    public function updateForm(Request $request, $id)
    {
        $user = Auth::user();
        $user2 = User::find($id);
        $users = User::all();

        // if ($user === null || !$user->can('update_users') ) { return redirect()->intended(route('login'));};

        return view('users.update', [
            'users' => $users,
            'user' => $user2,
            'permissions' => Permission::all(),
            'roles' => Role::all(),
            'userPermissionsIDs' => $user2->permissions()->pluck('id'),
        ]);

        // $test = $user->can('update_users');

        //                dd($test);

        // $test1 = $user->permissions()->whereName('create_users')->exists();
        //dd($test);

    }

    public function updateAction(Request $request, $id)
    {

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->period_tests = $request->input('period_tests');

        $user->email = $request->input('email');
        // $user->password = Hash::make($request->input('password'));
        $user->save();

        //$user->permissions()->sync($request->input('permissions', []));

        $currentUserPermissionsIDs = $user->permissions()->pluck('id');

        $newPermissionsIDs = $request->input('newPermissionsIDs');
        $allPermissionsIDs = $request->input('allPermissionsIDs');

        $permissionsIDsToAdd = collect($newPermissionsIDs)->diff($currentUserPermissionsIDs);

        $user->permissions()->attach($permissionsIDsToAdd);

        $permissionsIDsToRemove = collect($allPermissionsIDs)->diff($newPermissionsIDs);

        $user->permissions()->detach($permissionsIDsToRemove);

        //trzy najlepiej radio, zaznacz na no,
        // 1 najpierw w momencie zapisu pobieram te zapisane uprawnienia, pluck id
        // 2 niektóre wstawiamy niektóre usuwamy
        // przykład 2 i 5 a my wysyłamy 2 i 4 i 6
        // 4 i 6 wstaw, 5 usuń
        // wstawiamy te które są różnicą tablicy nowej - tablica wcześniejsza array_diff na collection_diff
        // różnica stare minus nowe to usuwanie i z tego wyjdzie 5
        // musimy użyć hiddeny, by usunąć 7(7 uprawnień) hiddenów musimy, 3 pule, user permission te które mają być teraz, te które były w formularzu i stare które były wcześniej.

        return back();
    }

    function list() {

        return view('users.list', [
            'users' => User::orderby('surname')->get(),
            'sidebar_item' => 'users-list',
        ]);

    }

    public function dayOffListForm()
    {

        return view('users.dayoff_list', [
            'users' => User::orderby('surname')->get(),
            'sidebar_item' => 'users-dayoff-list',
        ]);

    }

    public function dayOffListAction(Request $request)
    {

        // dd($request->input());
        $users = $request->input('users');

        foreach ($users as $user) {
            $dayoffsUpdate = User::findOrFail($user['id']);
            $dayoffsUpdate->dayoff_counter = $user['dayoff_counter'];
            $dayoffsUpdate->update();
        };
        return back();

    }

    public function updatePasswordForm(Request $request, $id)
    {
        $user = Auth::user();
        $user2 = User::find($id);

        return view('users.change_password', [
            'user' => $user2,
        ]);
    }

    public function updatePasswordAction(Request $request, $id)
    {
        $user = User::find($id);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back();
    }

    public function addPermission()
    {

        return view('users.list', [
            'users' => User::orderby('surname')->get(),
        ]);

    }

    public function userEventsToDolist()
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $now = Carbon::today();
        $userToDoEvents = CustomerEvent::where('user_id', '=', $userID)->where('is_completed', '=', '0')->latest('planned')->orderBy('planned')->get();

        return view('users.events_to_do', [
            'userID' => $userID,
            'userToDoEvents' => $userToDoEvents,
            'now' => $now,
            'sidebar_item' => 'to-do-events-list',

        ]);
    }

    public function searchUserEventsToDolist(Request $request)
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $now = Carbon::today();
        // $userToDoEvents = CustomerEvent::where('user_id', '=', $userID)->where('is_completed', '=', '0')->latest('planned')->orderBy('planned')->get();

        $needle1 = $request->input('q1');
        $needle2 = $request->input('q2');

        // $sign = $request->input('sign');

        if ($needle1 === null || $needle2 === null) {
            $userToDoEvents = null;
        } else {
            $userToDoEvents = CustomerEvent::with('customer')->where('user_id', '=', $userID)
                ->where('is_completed', '=', '0')
                ->where('planned', '>=', $needle1)
                ->where('planned', '<=', $needle2)
                ->orderBy('planned')
                ->get();
        }

        return view('users.search_events_to_do', [
            'userID' => $userID,
            'userToDoEvents' => $userToDoEvents,
            'now' => $now,
            'sidebar_item' => 'search-to-do-events-list',

        ]);
    }

    public function todayUserEventsToDolist()
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $now = Carbon::today();
        $userToDoEvents = CustomerEvent::with('customer')->where('user_id', '=', $userID)->where('is_completed', '=', '0')->where('planned', '=', $now)->latest('priority')->orderBy('planned', 'desc')->get();

        return view('users.events_to_do', [
            'userID' => $userID,
            'userToDoEvents' => $userToDoEvents,
            'now' => $now,
            'sidebar_item' => 'to-do-events-list',

        ]);
    }

    public function oldUserEventsToDolist()
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $now = Carbon::today();
        $userToDoEvents = CustomerEvent::with('customer')->where('user_id', '=', $userID)->where('is_completed', '=', '0')->where('planned', '<', $now)->latest('priority')->orderBy('planned', 'desc')->get();

        return view('users.events_to_do', [
            'userID' => $userID,
            'userToDoEvents' => $userToDoEvents,
            'now' => $now,
            'sidebar_item' => 'to-do-events-list',

        ]);
    }

    public function admin()
    {
        $now = Carbon::today();

        $products_all = InvoiceProduct::whereHas('invoice', function ($query) use ($now) {
            $query->whereIncoming(false)->whereNull('parent_id')
                ->whereYear('sell_date', $now->year)
                ->whereMonth('sell_date', $now->month)
                ->whereDay('sell_date', $now->day)
                ->whereNull('is_proforma');
        })->where('purchase_price', '=', '0')->sum('quantity');

        $oryginal_products_all = InvoiceProduct::whereHas('invoice', function ($query) use ($now) {
            $query->whereIncoming(false)->whereNull('parent_id')
                ->whereYear('sell_date', $now->year)
                ->whereMonth('sell_date', $now->month)
                ->whereDay('sell_date', $now->day)
                ->whereNull('is_proforma');
        })->where('purchase_price', '!=', '0')->sum('quantity');

        $products = Product::whereHas('invoices', function ($query) use ($now) {
            $query->where('issued_at', '=', $now);
        })
            ->withCount(['invoices' => function ($query) use ($now) {
                $query->select(DB::raw('sum(quantity)'))->where('issued_at', '=', $now)->where('purchase_price', '=', 0);
            }])->get();

        $oryginal_products = Product::whereHas('invoices', function ($query) use ($now) {
            $query->where('issued_at', '=', $now);
        })
            ->withCount(['invoices' => function ($query) use ($now) {
                $query->select(DB::raw('sum(quantity)'))->where('issued_at', '=', $now)->where('purchase_price', '>', 0);
            }])->get();

        $products_order_all = OrderProduct::whereHas('order', function ($query) use ($now) {
            $query->where('is_paid', '=', 0)->where('incoming', '=', 0);
        })->where('purchase_price', '=', '0')->sum('quantity');

        return view('admin.admin', [
            'products_order_all' => $products_order_all,
            'products_all' => $products_all,
            'products' => $products,
            'oryginal_products' => $oryginal_products,
            'oryginal_products_all' => $oryginal_products_all,

        ]);
    }

    public function workersAmount()
    {
        $now = new Carbon();
        $users = User::withCount(['customers' => function ($query) use ($now) {
            $query->where('acquired_at', '>', $now->format('Y-m-01'));
        }])->withCount(['invoices as invoices_net_value_sum' => function ($query) use ($now) {
            $query->where('incoming', '=', '0')
                ->whereNull('is_proforma')
                ->whereNull('parent_id')
                ->where('sell_date', '>', $now->format('Y-m-01'))
                ->select(DB::raw('SUM(net_value)'));
        }])->get();

        // $products = Product::with(['invoices' => function ($query) use ($now) {
        //     $query->where('sell_date', '=', $now->format('Y-m-d'));
        // }])
        //     ->where('made_by_us', '=', '1')
        //     ->get();

        // dd($products);

        return view('admin.workers_amount', [
            'users' => $users,
            'now' => $now,
            'sidebar_item' => 'to-do-events-list',

        ]);
    }

    public function insertHours()
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        $now = Carbon::today();

        return view('users.hours', [
            'users' => User::all(),
            'userID' => $userID,
            'now' => $now,
            'sidebar_item' => 'hours',

        ]);
    }

    public function insertHoursAction(Request $request)
    {
        $userID = $request->input('user') ? $request->input('user') : Auth::id();
        $now = Carbon::today();

        $validator = Validator::make($request->all(), [
            'date' => Rule::unique('work_hours')->where(function ($query) use ($userID) {
                return $query->where('user_id', $userID);
            }),
            'name_of_hours' => 'required',
        ]);
        // dd($validator);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {

            $workHours = new WorkHours;
            $workHours->user_id = $userID;
            $workHours->working_hours = '8';
            $workHours->date = $request->input('date');
            $workHours->name_of_hours = $request->input('name_of_hours');
            $telephpne_hours = $request->input('telephone_hours');
            [$hours, $minutes] = explode(':', $telephpne_hours);
            $time = isset($hours) ? ($hours * 60) + $minutes : $minutes;
            $workHours->telephone_hours = $time;
            $workHours->save();

            return redirect()->intended(route('all-working-hours', [$userID]));
        }
    }
    public function hoursList($id)
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        if ($userID != $id) {
            return redirect()->intended(route('login'));
        }
        $now = Carbon::today();

        $working_hours = WorkHours::where('user_id', '=', $userID)->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->get();

        return view('users.hours_list', [

            'userID' => $userID,
            'now' => $now,
            'working_hours' => $working_hours,

        ]);
    }

    public function allHoursList($id)
    {
        $userID = Auth::id() ? Auth::id() : redirect()->intended(route('login'));
        if ($userID != $id) {
            return redirect()->intended(route('login'));}
        $user = User::findOrFail($id);
        $now = Carbon::today();

        $working_hours = WorkHours::where('user_id', '=', $user->id)->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->get();
        $working_hours_8 = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '1')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        $working_hours_w = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '2')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        $working_hours_o = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '3')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        $working_hours_wnz = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '4')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        $working_hours_ch = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '5')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        $working_hours_n = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '6')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        $working_hours_m = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '7')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        $working_hours_k = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '8')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        $working_hours_p = WorkHours::where('user_id', '=', $user->id)->where('name_of_hours', '=', '9')->whereYear('date', '=', $now->year)->whereMonth('date', '=', $now->month)
            ->oldest('date')->count();

        return view('users.all_hours_list', [
            'userID' => $userID,
            'user' => $user,
            'users' => User::all(),
            'now' => $now,
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
        ]);
    }

}
