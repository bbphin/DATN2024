<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;

class DeleteExpiredCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now()->format('m/d/Y');
        $expiredCoupons = Coupon::where('end_date', '<', $now)->get();
        $expiredCoupons->collect()->each(function ($coupon) use ($now) {
            $coupon->delete();
        });
    }
}
