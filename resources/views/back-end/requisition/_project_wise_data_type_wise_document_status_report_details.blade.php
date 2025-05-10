<div class="modal-header">
    <h5 class="modal-title" id="dataTypesDetailsLabel"><i class="fas fa-file-lines"></i> {!! $result->project_name !!} Required Data Type Details Report</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @if(auth()->user()->hasPermission('project_document_requisition_edit'))
        @if(isset($result->data_types) && count($result->data_types))
            <label>Selected Oprations</label>
            <button class="btn btn-sm btn-outline-success mb-2" onclick="return DataTypeNecessityChange(this,1,{!! $result->pdri_id !!},{!! $result->project_id !!},{!! $result->company_id !!},)"><i class="fa-solid fa-star"></i> Make Required</button>
            <button class="btn btn-sm btn-outline-info mb-2" onclick="return DataTypeNecessityChange(this,0,{!! $result->pdri_id !!},{!! $result->project_id !!},{!! $result->company_id !!},)"><i class="fa-regular fa-star"></i> Make Optional</button>
            <button class="btn btn-sm btn-outline-danger mb-2" onclick="return DeleteProjectWiseNecessaryDataType({!! $result->pdri_id !!},{!! $result->project_id !!},{!! $result->company_id !!},)"><i class="fas fa-trash"></i> Delete</button>
        @endif
        <button class="btn btn-sm btn-outline-primary mb-2 @if(isset($result->data_types) && count($result->data_types))float-end @endif"  onclick="return ProjectWiseNewDataTypeAdd({!! $result->pdri_id !!},{!! $result->project_id !!},{!! $result->company_id !!},)"><i class="fas fa-plus"></i> Add Data Type</button>
        <hr>
    @endif
    <table @if($result) id="DataTypeTable" @endif class="table table-hover table-striped table-sm" style="width:100%">
        <thead>
        <tr>
            <th><input type="checkbox" name="" id="select_all"></th>
            <th>SL</th>
            <th>Company</th>
            <th>Project</th>
            <th>Assign Date</th>
            <th>Data Type</th>
            <th>Necessity</th>
            <th>Deadline</th>
            <th title="Document Status">Status</th>
            <th title="Document Count">Document's</th>
            <th title="Responsible Departments">Res. Depts.</th>
            <th title="Responsible Users">Res. Users</th>
        </tr>
        </thead>
        <tbody>
        @php $i=1; @endphp
        @foreach($result->data_types as $row)
            <tr>
                <td><input class="check-box" type="checkbox" name="selected[]" id="select_{!! $row->req_data_type_id !!}" value="{!! $row->req_data_type_id !!}"></td>
                <td>{!! $i++ !!}</td>
                <td>{!! $result->company_name !!}</td>
                <td>{!! $result->project_name !!}</td>
                <td>{!! $row->created_at !!}</td>
                <td>{!! $row->data_type_name !!}</td>
                <td >{!! $row->necessity? "<span class='badge bg-success'><i class='fa-solid fa-star'></i> Required</span>": "<span class='badge bg-info'><i class='fa-regular fa-star'></i> Optional</span>"!!}</td>
                <td>{!! $row->deadline !!}</td>
                <td>{!! $row->documents?'<span class="badge bg-success"><i class="fa-solid fa-circle-check"></i> Ok</span':'<span class="badge bg-danger"> <i class="fa-solid fa-circle-xmark"></i> Missing</span>' !!}</td>
                <td>
                    @if(auth()->user()->hasPermission("archive_data_list_quick"))
                        <a href="{!! route('uploaded.archive.list.pagination', ['c' => $result->company_id, 't' => $row->data_type_id,'p' => $result->project_id]) !!}" target="_blank">{!! $row->documents??'0' !!}</a>
                    @else
                        {!! $row->documents??'0' !!}
                    @endif
                </td>
                <td>
                    @if($row->departments)
                        @foreach($row->departments as $dept)
                            <span class="badge bg-secondary"> {!! $dept->dept_name !!}</span>
                        @endforeach
                    @endif
                </td>
                <td>
                    <a href="#" onclick="return DataTypeWiseResponsibleUserAdd(this,{{$row->req_data_type_id}},{!! $result->company_id !!})">{!! $row->responsible_by_count !!}</a>
{{--                    @foreach($row->responsible_by as $usr)--}}
{{--                        <span class="badge bg-secondary"> {!! $usr->name !!} ({!! $usr->employee_id !!})</span>--}}
{{--                    @endforeach--}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script>
    (function ($) {
        $("#select_all").change(function () {
            $(".check-box").prop("checked", this.checked);
        });
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
        })
    }(jQuery))
</script>
