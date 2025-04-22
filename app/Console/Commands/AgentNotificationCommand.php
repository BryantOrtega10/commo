<?php

namespace App\Console\Commands;

use App\Models\Customers\ActivitiesModel;
use App\Models\User;
use App\Notifications\AgentTasksNotification;
use Illuminate\Console\Command;

class AgentNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:agent-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate automatic notifications for all agents when expiration date is less than 10 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $activities = ActivitiesModel::select("activities.*", "users.id as userID")
            ->join("customers","customers.id","=","activities.fk_customer")
            ->join("agents","agents.id","=","customers.fk_agent")
            ->join("users","users.id","=","agents.fk_user")
            ->whereRaw("TIMESTAMPDIFF(MINUTE, CURDATE(), expiration_date) <= 10")
            //->whereRaw("TIMESTAMPDIFF(MINUTE, CURDATE(), expiration_date) > 0")
            ->whereNotNull("expiration_date")
            ->where("isDone", "=", false)
            ->get();
        
        foreach ($activities as $activity) {
            $user = User::find($activity->userID);
            if (!$user->notifications()
                ->where('type', AgentTasksNotification::class)
                ->where('data->activity_id', $activity->id)
                ->whereNull('read_at')
                ->exists()) {

                $user->notify(new AgentTasksNotification($activity));
                
            }
        }
    }
}
