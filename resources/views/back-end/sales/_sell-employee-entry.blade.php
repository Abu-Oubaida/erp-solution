<table class="table table-hover table-sm dataTable" style="font-size:12px">
    <thead>
        <tr>
            <th class="mobile-none">Select</th>
            <th>SL</th>
            <th>Company</th>
            <th>Employee</th>
            <th>E_ID</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($fetchedSaleEmployeeEntryData) && count($fetchedSaleEmployeeEntryData) > 0)
            @foreach ($fetchedSaleEmployeeEntryData as $key => $row)
                <tr>
                    <td class="mobile-none"><input class="check-box-type" type="checkbox"
                            name="selected[]" id="select_{!! $row->id !!}"
                            value="{!! $row->id !!}"></td>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->company->company_name ?? '-' }}</td>
                    <td>{{ $row->user->name ?? '-' }}</td>
                    <td>{{ $row->employee_id ?? '-' }}</td>
                    <td>{{ $row->createdByUser->name ?? '-' }}</td>
                    <td>{{ $row->updated_by ?? '-' }}</td>
                    <td>
                        <button class="btn btn-sm text-success" title="Edit"
                            onclick="return SalesSetting.salesSubTableEdit('sales_lead_apartment_type','',{{ $row->id }})"><i
                                class='fas fa-edit'></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>