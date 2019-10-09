<?php

namespace App\Providers;

use App\CustomerEvent;
use App\InvoiceProduct;
use Auth;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Support\ServiceProvider;
use Route;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('blocks.header', function ($view) {
            $userId = Auth::id() ? Auth::id() : redirect()->intended(route('login'));

            $now = Carbon::today();

            // $allEventsToDo = CustomerEvent::selectRaw('date(`planned`) = curdate() as `is_today`')
            //     ->selectRaw('count(*) as `total`')
            //     ->where('user_id', $userId)
            //     ->whereDate('planned', '<=', $now)
            //     ->whereIsCompleted(false)
            //     ->groupBy('is_today')
            //     ->pluck('total', 'is_today');

            $oldEventsToDo = CustomerEvent::where('user_id', '=', $userId)->where('planned', '<', $now)->where('is_completed', '=', 0)->count();
            $todayEventsToDo = CustomerEvent::where('user_id', '=', $userId)->where('planned', '=', $now)->where('is_completed', '=', 0)->count();

            // $todayEventsToDo = $allEventsToDo->get(1,0);
            // $oldEventsToDo = $allEventsToDo->get(0,0);

            $view->with('todayEventsToDo', $todayEventsToDo);
            $view->with('oldEventsToDo', $oldEventsToDo);
        });

        View::composer('blocks.footer', function ($view) {
            $userId = Auth::id() ?: redirect()->intended(route('login'));
            $daysLiterally = ['niedziela', 'poniedziałek', 'wtorek', 'środa', 'czwartek', 'piątek', 'sobota'];
            $now = Carbon::today();
            $nowNumber = $now->format('w');

            $today = $daysLiterally[$nowNumber] . ", " . Carbon::today()->format('d.m.Y');
            $apiWeather = @file_get_contents('https://api.openweathermap.org/data/2.5/weather?q=Krak%C3%B3w,PL&units=metric&appid=0a1cc94adda4d07dbdef25852ea64042');
            $apiWeather ? $json = json_decode($apiWeather, true) : $json = false;

            // $sellMonth = Invoice::where('user_id', '=', $userId)
            //     ->whereNull('parent_id')
            //     ->whereIncoming(false)
            //     ->whereNull('is_proforma')
            //     ->whereYear('sell_date', $now->year)
            //     ->whereMonth('sell_date', $now->month)
            //     ->sum('net_value');

            $sellMonthGross = InvoiceProduct::whereHas('invoice', function ($query) use ($userId, $now) {
                $query->whereIncoming(false)->whereNull('parent_id')
                    ->whereYear('sell_date', $now->year)
                    ->whereMonth('sell_date', $now->month)
                    ->whereNull('is_proforma')->where('user_id', '=', $userId);
            })->sum(DB::raw('net_unit_price * quantity'));

            $sellMonthPurchase = InvoiceProduct::whereHas('invoice', function ($query) use ($userId, $now) {
                $query->whereIncoming(false)->whereNull('parent_id')
                    ->whereYear('sell_date', $now->year)
                    ->whereMonth('sell_date', $now->month)
                    ->whereNull('is_proforma')->where('user_id', '=', $userId);
            })->sum(DB::raw('purchase_price * quantity'));

            $sellMonth = $sellMonthGross - $sellMonthPurchase;

            $view->with('sellMonth', $sellMonth);
            $view->with('today', $today);
            $view->with('json', $json);

        });

        View::composer('blocks.menu', function ($view) {
            $view->with('items', array_reduce(array_keys($view->items), function (array $items, string $route) use ($view) {
                $middleware = array_reduce(
                    Route::getRoutes()->getRoutesByName()[$route]->middleware(),
                    function (array $permissions, string $middleware) {
                        return array_merge($permissions, preg_match('~^can:([^,]+)~', $middleware, $matches) ? [$matches[1]] : []);
                    },
                    []
                );

                if (!Gate::check($middleware)) {
                    return $items;
                }

                $item = $view->items[$route];

                return $items + [$route => [
                    'label' => is_array($item) ? $item['label'] : $item,
                    'args' => $item['args'] ?? [],
                ]];
            }, []));
        });
        setlocale(LC_MONETARY, 'pl_PL.UTF-8');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
