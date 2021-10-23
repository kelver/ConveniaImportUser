<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeValidate;
use App\Imports\EmployeeImport;
use App\Jobs\MakeImportuser;
use App\Models\Employee;
use http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;

class EmployeeController extends Controller
{
    public function get(): JsonResponse
    {
        return response()->json(
            Employee::where('user_id', auth()->user()->id)->get()
        );
    }

    public function store(EmployeeValidate $request): JsonResponse
    {
        $data = $request->validated();
        $extension = $data['file']->getClientOriginalExtension();
        $target = "pending-files\\" .
                    Auth::id() . "\\" .
                    Str::uuid() . '.' .
                    $extension;
        $disk = Storage::disk('public');

        $disk->put($target, fopen($data['file'], 'r+'));

//        call queue route to proccess
        Http::get(route('queue'));

        return response()->json([
            'Success' => 'Uploaded.'
        ], 200);
    }

    public function show(Employee $employee): JsonResponse
    {
        $this->authorize('employee', $employee);

        return response()->json(
            $employee
        );
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $this->authorize('employee', $employee);

        return response()->json([
            'Success' => $employee->delete()
        ]);
    }
}
