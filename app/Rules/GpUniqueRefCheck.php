<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Fixed_asset_transfer;

class GpUniqueRefCheck implements Rule
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function passes($attribute, $value)
    {
        $existingRecord = Fixed_asset_transfer::where('reference', $value)->first();

        if ($existingRecord) {
            if ($existingRecord->status !== 0) {
                // If status is not 0, the reference must be unique
                return false;
            } elseif (
                // If status is 0, check if the other fields match
                $existingRecord->from_company_id !== $this->request->post('from_company_id') ||
                $existingRecord->to_company_id !== $this->request->post('to_company_id') ||
                $existingRecord->from_project_id !== $this->request->post('from_branch_id') ||
                $existingRecord->to_project_id !== $this->request->post('to_branch_id')
            ) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The reference is already in use or does not match the required details.';
    }
}
