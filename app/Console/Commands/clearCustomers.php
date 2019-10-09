<?php

namespace App\Console\Commands;

use App\Customer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class clearCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear customers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $customers = Customer::whereNotNull('acquired_at')->whereDoesntHave('invoices')
            ->update([
                'acquired_at' => null,
            ]);

        $customers = Customer::whereNotNull('acquired_at')->WhereDoesntHave('invoices', function ($query) {
            $query->where('issued_at', '>', Carbon::now()->subMonths(6));
        })
            ->whereDoesntHave('customer_events', function ($query) {
                $query->where('planned', '>', Carbon::now()->subMonths(3));
            })
            ->update([
                'acquired_at' => null,
                'user_id' => 1,
            ]);

    }
}
