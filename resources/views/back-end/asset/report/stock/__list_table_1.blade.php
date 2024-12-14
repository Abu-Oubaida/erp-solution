<table @if(isset($report) && count($report)) id="simpleDataTableCustom" @endif class="table display" style="font-size: 14px">
    <thead>
    <tr>
        <th>SL.</th>
        <th>Company</th>
        <th title="Materials Name">Materials</th>
        <th title="Materials Code">Code</th>
        <th title="Unit">Unit</th>
        <th title="Project count all transaction">Project count</th>
        <th title="Received balance using reference count">With Reference</th>
        <th title="Received balance using reference quantity total">W.R.Qty</th>
        <th title="Received balance using reference amount total">W.R.Amount</th>
        <th title="Transfer on self company count">GP Count</th>
        <th title="Transfer on self company quantity total">GP Qty</th>
        <th title="Transfer on self company amount total">GP Amount</th>
        <th title="Transfer in from other company count">T. In Company</th>
        <th title="Transfer in from other company quantity total">In Qty</th>
        <th title="Transfer in from other company amount total">In Amount</th>
        <th title="Transfer out from this company count">T. Out Company</th>
        <th title="Transfer out from this company quantity total">Out Qty</th>
        <th title="Transfer out from this company amount total">Out Amount</th>
        <th>Total Qty</th>
        <th>Total Amount</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>SL.</th>
        <th>Company</th>
        <th title="Materials Name">Materials</th>
        <th title="Materials Code">Code</th>
        <th title="Unit">Unit</th>
        <th title="Project count all transaction">Project count</th>
        <th title="Received balance using reference count">With Reference</th>
        <th title="Received balance using reference quantity total">W.R.Qty</th>
        <th title="Received balance using reference amount total">W.R.Amount</th>
        <th title="Transfer on self company count">GP Count</th>
        <th title="Transfer on self company quantity total">GP Qty</th>
        <th title="Transfer on self company amount total">GP Amount</th>
        <th title="Transfer in from other company count">T. In Company</th>
        <th title="Transfer in from other company quantity total">In Qty</th>
        <th title="Transfer in from other company amount total">In Amount</th>
        <th title="Transfer out from this company count">T. Out Company</th>
        <th title="Transfer out from this company quantity total">Out Qty</th>
        <th title="Transfer out from this company amount total">Out Amount</th>
        <th>Total Qty</th>
        <th>Total Amount</th>
    </tr>
    </tfoot>
    <tbody>
    @if(isset($report) && count($report))
        @php($n=1)
        @foreach($report as $item)
            <tr>
                <td>{!! $n++ !!}</td>
                <td>{!! @$company->company_name !!}</td>
                <td>{!! $item->materials_name !!}</td>
                <td>{!! $item->recourse_code !!}</td>
                <td>{!! $item->unit !!}</td>
                <td>
                    <a class="badge bg-success" title="Using Reference" data-bs-toggle="modal" data-bs-target="#dataModal">With Ref.: {!! $item->opening_project_count !!}</a>
                    <a href="#" class="badge bg-secondary" title="Transfer">Transfer: {!! $item->transfer_project_count !!}</a>
                </td>
                <td><a href="#">{!! $item->opening_count !!}</a></td>
                <td>{!! $item->withRef_total_qty !!}</td>
                <td>{!! $item->withRef_total_price !!}</td>
                <td><a href="#">{!! $item->self_transfer_count !!}</a></td>
                <td><a href="#">{!! $item->self_transfer_qty !!}</a></td>
                <td><a href="#">{!! $item->self_transfer_amount !!}</a></td>
                <td><a href="#">{!! $item->transfer_in_company_count !!}</a></td>
                <td>{!! $item->transfer_in_qty !!}</td>
                <td>{!! $item->transfer_in_total_price !!}</td>
                <td><a href="#">{!! $item->transfer_out_company_count !!}</a></td>
                <td>{!! $item->transfer_out_qty !!}</td>
                <td>{!! $item->transfer_out_total_price !!}</td>
                <td>{!! (($item->withRef_total_qty + $item->transfer_in_qty + $item->transfer_in_qty)-$item->transfer_out_qty) !!}</td>
                <td>{!! ($item->withRef_total_price + (($item->transfer_in_total_price - $item->transfer_out_total_price))) !!}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td class="text-center" colspan="15">Not Found!</td>
        </tr>
    @endif
    </tbody>
</table>
<script>
    (function ($){
        $(document).ready(function(){
            $('#simpleDataTableCustom').DataTable({
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
                            columns: ':visible' ,// Export only visible columns
                            format: {
                                header: function (data, columnIdx) {
                                    return $('#simpleDataTableCustom tfoot th').eq(columnIdx).text();
                                }
                            }
                        }
                    },
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                        exportOptions: {
                            columns: ':visible' ,// Export only visible columns
                            format: {
                                header: function (data, columnIdx) {
                                    return $('#simpleDataTableCustom tfoot th').eq(columnIdx).text();
                                }
                            }
                        },
                    },
                    {
                        extend:'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        exportOptions: {
                            columns: ':visible' ,// Export only visible columns
                            format: {
                                header: function (data, columnIdx) {
                                    return $('#simpleDataTableCustom tfoot th').eq(columnIdx).text();
                                }
                            }
                        },
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        exportOptions: {
                            columns: ':visible' ,// Export only visible columns
                            format: {
                                header: function (data, columnIdx) {
                                    return $('#simpleDataTableCustom tfoot th').eq(columnIdx).text();
                                }
                            }
                        },
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        exportOptions: {
                            columns: ':visible' ,// Export only visible columns
                            format: {
                                header: function (data, columnIdx) {
                                    return $('#simpleDataTableCustom tfoot th').eq(columnIdx).text();
                                }
                            }
                        },
                    },
                ],
                initComplete: function () {
                    // Add search inputs
                    $('#simpleDataTableCustom thead th').each(function() {
                        var title = $('#simpleDataTableCustom tfoot th').eq($(this).index()).text();
                        $(this).html('<input type="text" class="form-control" placeholder="' + title + '..." />');
                    });

                    // Apply the search
                    var table = $('#simpleDataTableCustom').DataTable();
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
