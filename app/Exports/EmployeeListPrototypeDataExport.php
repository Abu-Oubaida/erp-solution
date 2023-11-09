<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeeListPrototypeDataExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = [
            [
                'Employee Name*',
                'Department',
                'Department Code*',
                'Designation*',
                'Branch',
                'Joining Date*',
                'Phone',
                'Email',
                'Status',
                'Blood Group',
            ]
        ];
        return collect($data);
    }
}
