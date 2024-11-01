<?php

namespace App\Console;

use App\Models\User;
use App\Notifications\Generic;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call( function() {

            $twoWeeks = Carbon::now()->subWeeks(2);

            $users  = DB::table('users')
                ->join('user_activities', 'users.id', '=', 'user_activities.user_id')
                ->where('email', 'mark@the-larsons.org')
//                        ->whereNull('user_activities.training_done')
//                        ->where('user_activities.updated_at', '<=', $twoWeeks)
                ->select('users.*', 'user_activities.*')
                ->get();

            foreach($users as $user) {
                // extract user object for notifications
                $u = User::find($user->id);

                $u->notify(new Generic([
                    "subject" => 'Certification Reminder',
                    'greeting' => 'Hello ' . $user->first_name . '.',
                    'message'  => "<p>It's been over two weeks since you completed a step on your <strong>Lion Energy certification training</strong>.</p><p>Please return and finish your training so you can be certified to install the Sanctuary system for your clients.</p>",
                    'url_display' => 'Continue Training',
                    'url' => secure_url('https://certification.lionenergy.com/login'),
                ]));
            }
        })->cron('* * * * * *');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
