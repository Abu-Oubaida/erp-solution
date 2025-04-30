@php
    $rows = $getSaleSubTableData ?? ($salesLeadApartmentType ?? []);
@endphp
<div class="row">
    <div class="col-md-6 mb-2">
        <input type="checkbox" name="" id="select_all">
        <label for="select_all">Select All</label>
        <button class="btn btn-outline-danger btn-sm" name="submit_selected" type="submit" value="delete" onclick="return SalesSetting.deleteSalesSettingMultiple('/delete-type-multiple')"> <i class="fas fa-trash"></i> Delete Selected</button>
    </div>
</div>
<table class="table table-hover table-sm dataTable" style="font-size:12px">
    <thead>
        <tr>
            <th class="mobile-none">Select</th>
            <th>SL</th>
            <th>Company</th>
            <th>Title</th>
            <th>Status</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($rows) && count($rows) > 0)
            @foreach ($rows as $key => $row)
                <tr>
                    <td class="mobile-none"><input class="check-box" type="checkbox" name="selected[]" id="select_{!! $row->id !!}" value="{!! $row->id !!}"></td>
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
                        <button class="btn btn-sm text-success" title="Edit" onclick="return SalesSetting.salesSubTableEdit('sales_lead_apartment_type','',{{ $row->id}})"><i class='fas fa-edit'></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    (function($) {
        $("#select_all").change(function() {
            $(".check-box").prop("checked", this.checked);
        });
        $(document).ready(function() {
            $('.dataTable').each(function() {
                $(this).DataTable({
                    destroy: true, // Allow reinitialization
                    dom: 'lfrtip',
                    lengthMenu: [
                        [15, 25, 50, 100, -1],
                        [15, 25, 50, 100, "ALL"]
                    ],
                    pageLength: 15,
                });
            });
        });
    }(jQuery))
</script>
