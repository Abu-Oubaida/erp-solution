<div class="row">
    <div class="col-md-6 mb-2">
        <input type="checkbox" name="" id="select_all_leader">
        <label for="select_all">Select All</label>
        <button class="btn btn-outline-danger btn-sm" name="submit_selected" type="submit" value="delete" onclick="return Sales.deleteSalesLeaderEntryMultiple()"> <i class="fas fa-trash"></i> Delete Selected</button>
    </div>
</div>
<table class="table table-hover table-sm dataTable" style="font-size:12px">
    <thead>
        <tr>
            <th class="mobile-none">Select</th>
            <th>SL</th>
            <th>Company</th>
            <th>Leader</th>
            <th>E_ID</th>
            <th>Created</th>
            <th>Updated</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($fetchedSaleLeaderEntryData) && count($fetchedSaleLeaderEntryData) > 0)
            @foreach ($fetchedSaleLeaderEntryData as $key => $row)
                <tr>
                    <td class="mobile-none"><input class="check-box-leader" type="checkbox" name="selected[]" id="select_{!! $row->id !!}" value="{!! $row->id !!}"></td>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->company->company_name ?? '-' }}</td>
                    <td>{{ $row->user->name ?? '-' }}</td>
                    <td>{{ $row->employee_id ?? '-' }}</td>
                    <td>{{ $row->createdByUser->name ?? '-' }}</td>
                    <td>{{ $row->updated_by ?? '-' }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    (function ($) {
        $("#select_all_leader").change(function () {
            $(".check-box-leader").prop("checked", this.checked);
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