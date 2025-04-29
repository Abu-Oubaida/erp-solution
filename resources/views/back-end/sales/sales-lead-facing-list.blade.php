@php
    $rows = $getSaleSubTableData ?? $salesLeadFacing ?? [];
@endphp

<table class="table table-hover table-sm dataTable">
    <thead>
        <tr>
            <th>SL</th>
            <th>Company Name</th>
            <th>Title</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Updated By</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($rows) && count($rows) > 0)
            @foreach($rows as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->getCompanyName->company_name ?? '-' }}</td>
                    <td>{{ $row->title ?? '-' }}</td>
                    <td>
                        <span
                            class="{{ $row->status ? 'badge bg-success' : 'badge bg-danger' }}">{{ $row->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $row->createdByUser->name ?? '-' }}</td>
                    <td>{{ $row->updated_by ?? '-' }}</td>
                    <td>
                        {{-- {{route('edit.archive.type',["archiveTypeID"=>\Illuminate\Support\Facades\Crypt::encryptString($vt->id)])}} --}}
                        <button href="#" class="text-success" title="Edit" onclick="return SalesSetting.salesSubTableEdit('sales_lead_facing','',{{ $row->id}})"><i class='fas fa-edit'></i></button>
                        <a href="#" class="text-danger" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach        
        @endif
    </tbody>
</table>
<script>
    (function ($) {
        $("#select_all").change(function () {
            $(".check-box").prop("checked", this.checked);
        });
        $(document).ready(function () {
        $('.dataTable').each(function () {
            $(this).DataTable({
                destroy: true,  // Allow reinitialization
                dom: 'lfrtip',
                lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "ALL"]],
                pageLength: 15,
            });
        });
    });
    }(jQuery))
</script> 
