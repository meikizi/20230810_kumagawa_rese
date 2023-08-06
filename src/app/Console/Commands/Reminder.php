<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailReminder;

class Reminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send an email to the person who made the reservation in the morning of the reservation';

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
     * @return int
     */
    public function handle()
    {
        $shops = Shop::all();
        $tomorrow = Carbon::tomorrow()->toDateString();
        $today = Carbon::today()->format('m月d日');
        for ($i = 0; $i < Shop::count(); $i++) {
            $shop = $shops[$i];
            $reservation = $shop->users()->wherePivot('date', $tomorrow)->first();
            if (!empty($reservation)) {
                $users_id[] = $reservation->pivot->user_id;
                $shops_id[] = $reservation->pivot->shop_id;
                $times[] = $reservation->pivot->time;
            }
        }

        if (!empty($users_id)) {
            foreach ($users_id as $user_id) {
                $users[] = User::find($user_id);
            }
        }

        if (!empty($users)) {
            foreach ($users as $i => $user) {
                $shop_name = Shop::find($shops_id[$i])->name;
                $time = $times[$i];
                return Mail::to($user->email)->send(new EmailReminder($user, $shop_name, $today, $time));
            }
        }
    }
}
