<?php

namespace App\Http\Controllers;

use App\Charts\SampleChart;
use App\Customer;
use App\Invoice;
use App\InvoiceProduct;
use App\Offer;
use App\OrderProduct;
use App\WorkHours;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();
        $now = new Carbon();

        $sellMonth = $user->invoices()
            ->whereNull('parent_id')
            ->whereIncoming(false)
            ->whereNull('is_proforma')
            ->whereYear('sell_date', $now->year)
            ->whereMonth('sell_date', $now->month)
            ->sum('net_value');

        $sellMonthReplace = InvoiceProduct::whereHas('invoice', function ($query) use ($user, $now) {
            $query->whereIncoming(false)->whereNull('parent_id')
                ->whereYear('sell_date', $now->year)
                ->whereMonth('sell_date', $now->month)
                ->whereNull('is_proforma')->where('user_id', '=', $user->id);
        })->where('purchase_price', '=', '0')->sum(DB::raw('net_unit_price * quantity'));

        $customers = Customer::where('user_id', '=', $user->id)->where('acquired_at', '>', $now->format('Y-m-00'))->get();

        $sellMonthGross = InvoiceProduct::whereHas('invoice', function ($query) use ($user, $now) {
            $query->whereIncoming(false)->whereNull('parent_id')
                ->whereYear('sell_date', $now->year)
                ->whereMonth('sell_date', $now->month)
                ->whereNull('is_proforma')->where('user_id', '=', $user->id);
        })->sum(DB::raw('net_unit_price * quantity'));

        $sellMonthPurchase = InvoiceProduct::whereHas('invoice', function ($query) use ($user, $now) {
            $query->whereIncoming(false)->whereNull('parent_id')
                ->whereYear('sell_date', $now->year)
                ->whereMonth('sell_date', $now->month)
                ->whereNull('is_proforma')->where('user_id', '=', $user->id);
        })->sum(DB::raw('purchase_price * quantity'));

        $customers = Customer::where('user_id', '=', $user->id)->where('acquired_at', '>', $now->format('Y-m-00'))->get();

        $offers = Offer::where('user_id', '=', $user->id)->where('issued_at', '=', $now->format('Y-m-d'))->count();

        $users = WorkHours::with('user')->where('name_of_hours', '=', '2')->where('date', '=', $now->today())->get();
        $chart = new SampleChart;
        $chart2 = new SampleChart;
        $chart3 = new SampleChart;

        $from = \Carbon\CarbonImmutable::now()->startOfMonth();
        $to = $from->endOfMonth();

        $from_old = \Carbon\CarbonImmutable::now()->startOfMonth()->subMonth();
        $to_old = $from_old->endOfMonth();

        $from_old_year = \Carbon\CarbonImmutable::now()->startOfMonth()->subYear();
        $to_old_year = $from_old_year->endOfMonth();

        $fromTime = strtotime($from);
        $toTime = strtotime($to);

        $fromOldTime = strtotime($from_old);
        $toOldTime = strtotime($to_old);

        $dates = Collection::times(
            round(($toTime - $fromTime) / 86400) + 1,
            function (int $dayIndex) use ($fromTime): string {
                return date('Y-m-d', $fromTime + ($dayIndex - 1) * 86400);
            }
        );

        $chartData = $dates->combine(collect()->pad($dates->count(), 0.0));

        $chart->labels($chartData->keys());
        $chart2->labels($chartData->keys());
        $chart3->labels($chartData->keys());

        $newUserInvoices = Invoice::whereUserId($user->id)->where('incoming', '=', '0')->where('is_proforma', '=', null)->chartStats('net_value', 'issued_at', $from, $to);
        $oldUserInvoices = Invoice::whereUserId($user->id)->where('incoming', '=', '0')->where('is_proforma', '=', null)->chartStats('net_value', 'issued_at', $from_old, $to_old);
        $chart2->dataset('Obecny Obrót', 'line', $newUserInvoices->values())->backgroundcolor('rgba(0,191,255, 0.7)');
        $chart2->dataset('Obrót w poprzednim miesiącu', 'line', $oldUserInvoices->values())->backgroundcolor('rgba(255, 0, 0, 0.5)');

        $newInvoices = Invoice::where('incoming', '=', '0')->where('is_proforma', '=', null)->chartStats('net_value', 'issued_at', $from, $to);
        $newOldInvoices = Invoice::where('incoming', '=', '0')->where('is_proforma', '=', null)->chartStats('net_value', 'issued_at', $from_old, $to_old);

        $newInvoicesYear = Invoice::where('incoming', '=', '0')->where('is_proforma', '=', null)->chartStats('net_value', 'issued_at', $from, $to);

        $newOldInvoicesYear = Invoice::where('incoming', '=', '0')->where('is_proforma', '=', null)->chartStats('net_value', 'issued_at', $from_old_year, $to_old_year);
        $chart->dataset('Obecny Obrót', 'line', $newInvoices->values())->backgroundcolor('rgba(0,191,255, 0.7)');
        $chart->dataset('Obrót w poprzednim roku', 'line',
            $newOldInvoicesYear->values())->backgroundcolor('rgba(255, 0, 0, 0.5)');

        $products_order_all = OrderProduct::whereHas('order', function ($query) use ($now) {
            $query->where('is_paid', '=', 0)->where('incoming', '=', 0);
        })->where('purchase_price', '=', '0')->sum('quantity');

        $products_all = InvoiceProduct::whereHas('invoice', function ($query) use ($now) {
            $query->whereIncoming(false)->whereNull('parent_id')
                ->whereYear('sell_date', $now->year)
                ->whereMonth('sell_date', $now->month)
                ->whereDay('sell_date', $now->day)
                ->whereNull('is_proforma');
        })->where('purchase_price', '=', '0')->sum('quantity');

        return view('dashboard', [
            'products_all' => $products_all,
            'products_order_all' => $products_order_all,
            'chart2' => $chart2,
            'chart' => $chart,
            'users' => $users,
            'sellMonthGross' => $sellMonthGross,
            'sellMonthPurchase' => $sellMonthPurchase,
            'offers' => $offers,
            'customers' => $customers,
            'sellMonthReplace' => $sellMonthReplace,
            'sellMonth' => $sellMonth,
            'user' => $user,
            'now' => $now,
        ]);

    }

}
