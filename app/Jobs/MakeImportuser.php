<?php

namespace App\Jobs;

use App\Imports\EmployeeImport;
use App\Models\User;
use App\Notifications\NotifyImporToUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{
    InteractsWithQueue,
    SerializesModels
};

class MakeImportuser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, User $user)
    {
        $this->file = $file;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       (new EmployeeImport($this->user->id))->import($this->file[0], 'public');

       unlink(storage_path('app/public/' . $this->file[0]));
       $this->user->notifyNow(new NotifyImporToUser());
    }
}
