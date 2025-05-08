<table @if(count($data)) id="DataTable2" @endif class="table table-hover table-striped table-sm" style="width:100%">
    <thead>
        <tr>
            <th>SL</th>
            <th>Company</th>
            <th>Project</th>
            <th>Address</th>
            <th>Required Data Type</th>
            <th>Created By</th>
            <th>Updated By</th>
        </tr>
    </thead>
    <tbody>
    @php $i=1; @endphp
    @foreach($data as $row)
        <tr onclick="ProjectWiseDataTypesReportDetails(this,{!! $row->pdri_id !!}, {!! $row->project_id !!}, {!! $row->company_id !!})" style="cursor:pointer;">
            <td>{!! $i++ !!}</td>
            <td>{!! $row->company_name !!}</td>
            <td>{!! $row->project_name !!}</td>
            <td>{!! $row->project_address !!}</td>
            <td>{!! $row->data_type_required_count !!}</td>
            <td>{!! $row->created_by??'-' !!}</td>
            <td>{!! $row->updated_by??'-' !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    (function ($) {
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#DataTable2')) {
                $('#DataTable2').DataTable({
                    dom: 'lBfrtip', // 'l' includes the "length changing" input
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "ALL"]],
                    pageLength: 25,
                    fixedHeader: {
                        header: true
                    },
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
                                        return $('#DataTable2 thead th').eq(columnIdx).text();
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
                                        return $('#DataTable2 thead th').eq(columnIdx).text();
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
                                        return $('#DataTable2 thead th').eq(columnIdx).text();
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
                                        return $('#DataTable2 thead th').eq(columnIdx).text();
                                    }
                                }
                            }
                        }
                    ],

                });
            }
        })
    }(jQuery))
</script>
