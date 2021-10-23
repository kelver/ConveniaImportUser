<?php

namespace App\Imports;

use App\Models\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToModel, WithHeadingRow
{
    use Importable;

    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employee = $this->validateDuplicate($row, $this->userId);

        if(!$employee) {
            return new Employee([
                'user_id' => $this->userId,
                'name' => $row['name'],
                'email' => $row['email'],
                'document' => $row['document'],
                'city' => $row['city'],
                'state' => $row['state'],
                'start_date' => Carbon::parse(Date::excelToDateTimeObject($row['start_date']))->format('Y-m-d H:i:s'),
            ]);
        }

        $employee->update([
            'name' => $row['name'],
            'city' => $row['city'],
            'state' => $row['state'],
            'start_date' => Carbon::parse(Date::excelToDateTimeObject($row['start_date']))->format('Y-m-d H:i:s'),
        ]);
        return $employee;
    }

    public function validateDuplicate($row, $userId)
    {
        $verify = Employee::where(['email' => $row['email'], 'user_id' => $userId])->first();

        return $verify;
    }
}
