
<table id="DataTable2" class="display compact">
    <thead>
    <tr>
        <th>No</th>
        <th>Parent</th>
        <th>Type</th>
        <th>Name</th>
        <th>Display Name</th>
        <th>Details</th>
        <th>Created By</th>
        <th>Updated By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>No</th>
        <th>Parent</th>
        <th>Type</th>
        <th>Name</th>
        <th>Display Name</th>
        <th>Details</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Action</th>
    </tr>
    </tfoot>
    <tbody>
    @if(isset($permissions) && count($permissions))
        @php
            //                            $no= count($permissions);
                                        $no= 1;
        @endphp
        @foreach($permissions as $data)
            <tr>
                <td><strong>{!! $no !!}</strong></td>
                <td><strong>{!! ($data->parentName != null)?$data->parentName->display_name:"NULL"  !!}</strong></td>
                <td><strong>{!! $data->is_parent == null ?"Child":"Parent"   !!}</strong></td>
                <td><strong>{!! $data->name   !!}</strong></td>
                <td><strong>{!! $data->display_name   !!}</strong></td>
                <td><strong>{!! $data->description   !!}</strong></td>
                <td><strong>{!! date('d-M-y',strtotime($data->created_at))   !!}</strong></td>
                <td><strong>{!! date('d-M-y',strtotime($data->updated_at))   !!}</strong></td>
                <td>
                    <form action="{{route('permission.input.delete')}}" class="display-inline" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}">
                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the parent permission?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @php($a=1)
            @foreach($data->childPermission as $child)
                <tr>
                    <td><div class="text-end">{!! $no !!}.{!! $a++ !!}</div></td>
                    <td>{!! $data->display_name!!}</td>
                    <td>{!! ($child->is_parent == null) ?"Child":"<strong>Parent</strong>"   !!}</td>
                    <td>{!! $child->name   !!}</td>
                    <td>{!! $child->display_name   !!}</td>
                    <td>{!! $child->description   !!}</td>
                    <td>{!! date('d-M-y',strtotime($child->created_at))   !!}</td>
                    <td>{!! date('d-M-y',strtotime($child->updated_at))   !!}</td>
                    <td>
                        <form action="{{route('permission.input.delete')}}" class="display-inline" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($child->id) !!}">
                            <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the child permission?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @php($no++)
        @endforeach
    @else
        <tr>
            <td colspan="8" class="text-danger text-center">Not Found!</td>
        </tr>
    @endif
    </tbody>
</table>
<script>
    (function ($) {
        $(document).ready(function () {
            $('#DataTable2').DataTable({
                dom: 'lBfrtip', // 'l' includes the "length changing" input
                lengthMenu: [[5, 10, 15, 25, 50, 100, -1],[5, 10, 15, 25, 50, 100, "ALL"]],
                pageLength: 15,
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        orientation: 'landscape', // Landscape orientation
                        pageSize: 'A4', // A4 page size
                        title: 'My Table Export', // Optional: Custom title
                        exportOptions: {
                            columns: ':visible', // Export only visible columns
                            format: {
                                header: function (data, columnIdx) {
                                    // Extract the header text from the <th> element, ignoring the input field
                                    return $('#DataTable2 tfoot th').eq(columnIdx).text();
                                }
                            }
                        }
                    },
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                header: function (data, columnIdx) {
                                    return $('#DataTable2 tfoot th').eq(columnIdx).text();
                                }
                            }
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                header: function (data, columnIdx) {
                                    return $('#DataTable2 tfoot th').eq(columnIdx).text();
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                header: function (data, columnIdx) {
                                    return $('#DataTable2 tfoot th').eq(columnIdx).text();
                                }
                            }
                        }
                    }
                ],
                initComplete: function () {
                    // Add search inputs to header
                    $('#DataTable2 thead th').each(function() {
                        var title = $(this).text(); // Use the text content of the header cells
                        $(this).html('<input type="text" class="form-control" placeholder="' + title + '..." />');
                    });

                    // Apply the search
                    var table = this.api(); // Use the DataTables API instance
                    table.columns().eq(0).each(function(colIdx) {
                        $('input', table.column(colIdx).header()).on('keyup change', function() {
                            table.column(colIdx).search(this.value).draw();
                        });
                    });
                }
            });
        })
    }(jQuery))
</script>

