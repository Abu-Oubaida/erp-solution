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
                'Company Code*',
                'Employee Name*',
                'Employee Id*',
                'Department',
                'Department Code*',
                'Designation*',
                'Branch',
                'Joining Date*',
                'Phone',
                'Email',
                'Status',
                'Blood Group',
                'Password',
            ]
        ];
        return collect($data);
    }
}
