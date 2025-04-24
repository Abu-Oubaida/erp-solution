@php
    $rows = $getSaleSubTableData ?? $salesLeadApartmentSize ?? [];
@endphp

<table class="table table-hover table-sm {{$rows?'dataTable':''}}">
    <thead>
        <tr>
            <th>SL</th>
            <th>Title</th>
            <th>Status</th>
            <th>Size</th>
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
                    <td>{{ $row->title ?? '-' }}</td>
                    <td>{{ $row->status ? 'Active' : 'Inactive' }}</td>
                    <td>{{ $row->size ?? '-' }}</td>
                    <td>{{ $row->created_by ?? '-' }}</td>
                    <td>{{ $row->updated_by ?? '-' }}</td>
                    <td>
                        {{-- {{route('edit.archive.type',["archiveTypeID"=>\Illuminate\Support\Facades\Crypt::encryptString($vt->id)])}} --}}
                        <a href="#" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                        <a href="#" class="text-danger" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @else
        <tr>
            <td colspan="7" class="text-center">No Data Found</td>
        </tr>
        @endif
    </tbody>
</table>
<script>
    (function ($) {
        $("#select_all").change(function () {
            $(".check-box").prop("checked", this.checked);
        });
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('.dataTable')) {
                $('.dataTable').DataTable({
                    dom: 'lfrtip',
                    lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "ALL"]],
                    pageLength: 15,
                })
            }
        })
    }(jQuery))
</script> 