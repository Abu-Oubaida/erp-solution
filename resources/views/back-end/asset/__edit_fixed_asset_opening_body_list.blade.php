<table id="DataTable2" class="table display">
    <thead>
    <tr>
        <th>SL.</th>
        <th>Date</th>
        <th>Materials (Code)</th>
        <th>Specifications</th>
        <th>Unit</th>
        <th>Rate</th>
        <th>Qty.</th>
        <th>Total</th>
        <th>Purpose</th>
        <th>Remarks</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($withRefData->withSpecifications) && count($withRefData->withSpecifications))
        @php($n=1)
        @foreach($withRefData->withSpecifications as $opm)
            <tr>
                <td>{!! $n++ !!}</td>
                <td>{!! date('d-M-Y', strtotime($opm->date)) !!}</td>
                <td>{!! $opm->asset->materials_name !!} ({!! $opm->asset->recourse_code !!})</td>
                <td>{!! ($opm->spec_id == '0')?'None':$opm->specification->specification !!}</td>
                <td>{!! $opm->asset->unit !!}</td>
                <td>{!! $opm->rate !!}</td>
                <td>{!! $opm->qty !!}</td>
                <td>{!! (float)($opm->qty * $opm->rate) !!}</td>
                <td>{!! (isset($opm->purpose))?$opm->purpose:'' !!}</td>
                <td>{!! (isset($opm->remarks))?$opm->remarks:'' !!}</td>
                <td>
                    <button class="text-success border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.fixedAssetOpeningSpecEdit(this)"><i class="fas fa-edit"></i></button>
                    <button class="text-danger border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.deleteFixedAssetOpeningSpec(this)"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td class="text-center" colspan="12">Not Found!</td>
        </tr>
    @endif
    </tbody>
</table>
<script>
    (function ($){
        $(document).ready(function(){
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

