<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\ExplosiveChecklist;
use App\Notifications\ChecklistApprovalNotification;

class ProcessNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $checklist;

    public function __construct(User $user, ExplosiveChecklist $checklist)
    {
        $this->user = $user;
        $this->checklist = $checklist;
    }

    public function handle()
    {
        $this->user->notify(new ChecklistApprovalNotification($this->checklist));
    }
}