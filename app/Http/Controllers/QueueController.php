<?php

namespace App\Http\Controllers;

use App\Jobs\MakeImportuser;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class QueueController extends Controller
{
    public function queueImport()
    {
        $userId = (int) explode('/',
                                Storage::disk('public')
                                    ->allDirectories('pending-files')[0])[1];
        $files = Storage::disk('public')->files("/pending-files/{$userId}");
        foreach ($files as $file){
            MakeImportuser::dispatch($files, User::find($userId));
        }
    }
}
