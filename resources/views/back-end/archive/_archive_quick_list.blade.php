<script>
    (function ($) {
        $("#select_all").change(function () {
            $(".check-box").prop("checked", this.checked);
        });
        $(document).ready(function () {

            if (!$.fn.DataTable.isDataTable('#DataTable2')) {
                $('#DataTable2').DataTable({
                    dom: 'lBfrtip', // 'l' includes the "length changing" input
                    lengthMenu: [[5, 10, 15, 25, 50, 100, -1], [5, 10, 15, 25, 50, 100, "ALL"]],
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
                        $('#DataTable2 thead th').each(function () {
                            var title = $(this).text(); // Use the text content of the header cells
                            $(this).html('<input type="text" class="form-control" placeholder="' + title + '..." />');
                        });

                        // Apply the search
                        var table = this.api(); // Use the DataTables API instance
                        table.columns().eq(0).each(function (colIdx) {
                            $('input', table.column(colIdx).header()).on('keyup change', function () {
                                table.column(colIdx).search(this.value).draw();
                            });
                        });
                    }
                });
            }

        })
    }(jQuery))
</script>
@if(\Illuminate\Support\Facades\Auth::user()->roles()->first()->display_name == "Systemsuperadmin")
    <div class="row" id="fixedDiv">
        <div class="col-md-10">
            <input type="checkbox" name="" id="select_all">
            <label for="select_all">Select All</label>
            <button class="btn btn-outline-danger btn-sm" name="submit_selected" type="submit" value="delete"> <i class="fas fa-trash"></i> Delete</button>
            <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-file-zipper"></i> Download Zip</button>
        </div>

    </div>
@endif
<table class="table table-sm" @if(isset($voucherInfos) && count($voucherInfos)) id="DataTable2" @endif>
    <thead>
    <tr class="text-center">
    @if(\Illuminate\Support\Facades\Auth::user()->roles()->first()->display_name == "Systemsuperadmin")
        <th>Select</th>
    @endif
        <th>SL</th>
        <th>Date</th>
        <th>Company</th>
        <th>Project</th>
        <th title="Reference Number">Ref. Number</th>
        <th>Type</th>
        <th>Remarks</th>
        <th>Document</th>
        <th>Created By</th>
        <th>Upload Date</th>
        <th>Updated By</th>
        <th>Action</th>
    </tr>
    </thead>
{{--    <tfoot>--}}
{{--    <tr>--}}
{{--        <th>Select</th>--}}
{{--        <th>SL</th>--}}
{{--        <th>Date</th>--}}
{{--        <th>Voucher Number</th>--}}
{{--        <th>Voucher Type</th>--}}
{{--        <th>Remarks</th>--}}
{{--        <th>Document</th>--}}
{{--        <th>Created By</th>--}}
{{--        <th>Updated By</th>--}}
{{--        <th>Action</th>--}}
{{--    </tr>--}}
{{--    </tfoot>--}}
    <tbody>
    @if(isset($voucherInfos) && count($voucherInfos))
        @php
            $no= 1;
        @endphp
        @foreach($voucherInfos as $data)
            <tr class="text-center">
            @if(\Illuminate\Support\Facades\Auth::user()->roles()->first()->display_name == "Systemsuperadmin")
                <th><input class="check-box" type="checkbox" name="selected[]" id="select_{!! $data->id !!}" value="{!! $data->id !!}"></th>
            @endif
                <td>{!! $no++ !!}</td>
                <td>{!! date('d-M-y', strtotime($data->voucher_date)) !!}</td>
                <td>{!! $data->company->company_code !!}</td>
                <td>{!! @$data->project->branch_name !!}</td>
                <td>{!! $data->voucher_number !!}</td>
                <td>{!! $data->VoucherType->voucher_type_title !!}</td>
                <td>{!! $data->remarks !!}</td>
                <td class="text-start text-left">
                    @php $x = 1;@endphp
                    @foreach($data->voucherDocuments as $d)
                        <div>
                            <strong>{!! $x++ !!}.</strong> {!! preg_replace('/^([^_]+_[^_]+)_.*$/', '$1', pathinfo($d->document, PATHINFO_FILENAME)) . '.' . pathinfo($d->document, PATHINFO_EXTENSION) !!}  &nbsp; <a href="" title="Quick View" vtype="{!! $data->VoucherType->voucher_type_title !!}" vno="{!! $data->voucher_number !!}" path="{!! \Illuminate\Support\Facades\Crypt::encryptString(url($d->filepath.$d->document)) !!}" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}" onclick="return Obj.findDocument(this,'documentPreview','v_type','v_no')"> <i class="fa-solid fa-eye"></i></a>
                            &nbsp;
                            <a href="{!! route('view.archive.document',['vID'=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)]) !!}" title="View on new window" target="_blank"><i class="fa-solid fa-up-right-from-square"></i></a>
                            &nbsp
                            @if(auth()->user()->hasPermission('share_archive_data_individual'))
                            <a href="" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}" onclick="return Obj.fileSharingModal(this)" title="Share Document"><i class="fas fa-share"></i></a>
                            @endif
                            &nbsp
                            @if(auth()->user()->hasPermission('delete_archive_document_individual'))
                                <form action="{{route('delete.archive.document.individual')}}" class="display-inline" method="post">
                                    @method('delete')
                                    @csrf
                                    <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}">
                                    <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this?')" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                            <hr>
                        </div>
                    @endforeach
                @if(auth()->user()->hasPermission('add_archive_document_individual'))
                    <a href="" class="text-end float-end badge bg-success text-decoration-none" onclick="return Obj.addArchiveDocumentIndividual(this)" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}"><i class="fas fa-plus"></i> Add New</a>
                @endif
                </td>
                {{--                                        <td></td>--}}
                {{--                                        <td></td>--}}
                <td>{!! ($data->createdBY)? $data->createdBY->name:'-' !!}</td>
                <td>{!! date('d-M-y', strtotime($data->created_at)) !!}</td>
                <td>{!! ($data->updatedBY)? $data->updatedBY->name:'-' !!}</td>
                <td>
                    @if(auth()->user()->hasPermission('archive_document_edit'))
                    <a href="{{route('edit.archive.info',["archiveDocumentID"=>\Illuminate\Support\Facades\Crypt::encryptString($data->id)])}}" class="text-success" title="Edit" target="_blank"><i class='fas fa-edit'></i></a>
                    @endif
                    @if(auth()->user()->hasPermission('share_archive_data'))
                        <a href="" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}" onclick="return Obj.archiveShare(this)" title="Share Archive"><i class="fas fa-share"></i></a>
                    @endif
                    @if(auth()->user()->hasPermission('archive_document_delete'))
                    <form action="{{route('delete.archive.info')}}" class="display-inline" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}">
                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the archive type?')"><i class="fas fa-trash"></i></button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr>
        @if(\Illuminate\Support\Facades\Auth::user()->roles()->first()->display_name == "Systemsuperadmin")
            <td colspan="13" class="text-danger text-center">Not Found!</td>
        @else
            <td colspan="12" class="text-danger text-center">Not Found!</td>
        @endif
        </tr>
    @endif
    </tbody>
</table>
    @method('post')
{{--</form>--}}
<!-- Modal For Preview -->
<div class="modal modal-xl fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="v_document_name"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Reference No: <span id="v_no"></span></strong>
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Data Type: <span id="v_type"></span></strong>
                    </div>
                </div>
                <div id="documentPreview"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal-2 For Share -->
<div class="modal modal-xl fade" id="shareModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="shareModelLabel" aria-hidden="true">
    <div class="modal-dialog" id="model_dialog">

    </div>
    <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
        <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
    </div>
</div>
