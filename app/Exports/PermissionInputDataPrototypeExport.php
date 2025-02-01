<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class PermissionInputDataPrototypeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = [
            [
                'Parent*','Type*','Name*','Display Name*','Details',
            ]
        ];
        return collect($data);
    }
}
