<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersSalaryCertificateDataExport implements FromCollection
{
    public function collection()
    {
        $data = [
            [
                'Employee ID*',
                'Name',
                'Department',
                'Financial Year From*',
                'Financial Year To*',
                'Basic*',
                'House Rent*',
                'Conveyance*',
                'Medical Allowance*',
                'Festival Bonus*',
                'Others',
                'Total',
                'Remarks'
            ]
        ];

        $users = User::with(['getDepartment', 'getBranch'])->where('status', 1)->get();

        foreach ($users as $user) {
            $data[] = [
                $user->employee_id,
                $user->name,
                $user->getDepartment->dept_name,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'NULL',
            ];
        }

        return collect($data);
    }
}
