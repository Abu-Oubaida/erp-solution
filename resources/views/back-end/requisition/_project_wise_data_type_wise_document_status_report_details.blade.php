<div class="modal-header">
    <h5 class="modal-title" id="dataTypesDetailsLabel">{!! $result->project_name !!} Required Data Type Details Report</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" id="dataTypesDetailsContent">
    <table @if($result) id="DataTypeTable" @endif class="table table-hover table-striped table-sm" style="width:100%">
        <thead>
        <tr>
            <th>SL</th>
            <th>Company</th>
            <th>Project</th>
            <th>Data Type</th>
            <th>Necessity</th>
            <th>Document Status</th>
            <th>Document Count</th>
            <th>Responsible By</th>
        </tr>
        </thead>
        <tbody>
        @php $i=1; @endphp
        @foreach($result->data_types as $row)
            <tr>
                <td>{!! $i++ !!}</td>
                <td>{!! $result->company_name !!}</td>
                <td>{!! $result->project_name !!}</td>
                <td>{!! $row->data_type_name !!}</td>
                <td >{!! $row->necessity? "<span class='badge bg-success'>Required</span>": "<span class='badge bg-info'>Optional</span>"!!}</td>
                <td>{!! $row->documents?'✅ OK':'❌ Missing' !!}</td>
                <td>{!! $row->documents??'0' !!}</td>
                <td>
                    @foreach($row->responsible_by as $usr)
                        <span class="badge bg-secondary"> {!! $usr->name !!} ({!! $usr->employee_id !!})</span>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script>
    (function ($) {
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#DataTypeTable')) {
                $('#DataTypeTable').DataTable({
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
                                        return $('#DataTypeTable thead th').eq(columnIdx).text();
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
                                        return $('#DataTypeTable thead th').eq(columnIdx).text();
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
                                        return $('#DataTypeTable thead th').eq(columnIdx).text();
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
                                        return $('#DataTypeTable thead th').eq(columnIdx).text();
                                    }
                                }
                            }
                        }
                    ],

                });
            }

            if (!$.fn.DataTable.isDataTable('.dataTableSmall')) {
                $('.dataTableSmall').DataTable({
                    dom: 'lfrtip',
                    lengthMenu: [[2, 10, 15, 25, 50, 100, -1], [2, 10, 15, 25, 50, 100, "ALL"]],
                    pageLength: 2,
                })
            }
        })
    }(jQuery))
    function ProjectWiseDataTypesReportDetails(e,id)
    {
        if(id.length === 0)
        {
            return false
        }
        const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-report-details";
        $.ajax({
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            data: {
                id: id,
            },
            success: function (response) {
                if(response.status === 'error')
                {
                    alert("Error: "+response.message)
                    return false
                }
                else {
                    // alert("Success: "+response.message)
                    $("#report-info").html(response.data.view)
                }
            },
        })
    }
</script>
