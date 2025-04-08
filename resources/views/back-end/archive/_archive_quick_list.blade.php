<style id="fixedDivCss">
    #fixedDiv {
        margin-left: -10px;
        width: 83.8%;
        transition: background-color 0.3s ease-in-out;
    }

    #fixedDiv.fixed {
        z-index: 1000;
        position: fixed;
        top: 60px;
        padding: 5px 0;
        /*background-color: rgba(121, 121, 121, 0.64); !* Change background color when fixed *!*/
        background-color: rgba(255, 255, 255, 0.95); /* Change background color when fixed */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a shadow when fixed */
    }
    #fixedDiv2 {
        margin-left: -10px;
        width: 83.8%;
        transition: background-color 0.3s ease-in-out;
    }

    #fixedDiv2.fixed {
        z-index: 1001;
        position: fixed;
        top: 105px;
        padding: 0;
        background-color: rgba(99, 99, 99, 0.84); /* Change background color when fixed */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a shadow when fixed */
    }
    .table {
        font-size: 12px!important;
    }
</style>
@if(isset($voucherInfos) && count($voucherInfos))
    <div class="row mb-1" id="fixedDiv">
        <div class="col-md-10">
            <input type="checkbox" name="" id="select_all">
            <label for="select_all">Select All</label>
            @if(auth()->user()->hasPermission('multiple_archive_data_delete'))
                <button class="btn btn-outline-danger btn-sm" name="submit_selected" type="submit" value="delete" onclick="return Obj.deleteArchiveMultiple(this,true)"> <i class="fas fa-trash"></i> Delete Selected</button>
            @endif
            @if(auth()->user()->hasPermission('share_archive_data_multiple'))
            <button class="btn btn-outline-primary btn-sm" onclick="return Obj.shareArchiveMultiple(this)"> <i class="fas fa-share"></i> Send by Email</button>
            @endif
{{--            <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-file-zipper"></i> Download Zip</button>--}}
        </div>

    </div>
@endif
<table class="table table-sm table-hover" @if(isset($voucherInfos) && count($voucherInfos)) id="DataTable2" @endif>
    <thead>
    <tr class="">
    @if(isset($voucherInfos) && count($voucherInfos))
        <th>Select</th>
    @endif
        <th>SL</th>
        <th>Date</th>
        <th>Company</th>
        <th>Project</th>
        <th title="Reference Number">Ref. Number</th>
        <th>Type</th>
        <th style="width: 25%">Document</th>
        <th>Upload Date</th>
        <th>Uploaded By</th>
{{--        <th>Updated By</th>--}}
        <th>Action</th>
        <th>Remarks</th>
    </tr>
    </thead>
    @if(isset($voucherInfos) && count($voucherInfos))
    <tfoot id="fixedDiv2">
    <tr>
    @if(isset($voucherInfos) && count($voucherInfos))
        <th>Select</th>
    @endif
        <th>SL</th>
        <th>Date</th>
        <th>Company</th>
        <th>Project</th>
        <th title="Reference Number">Ref. Number</th>
        <th>Type</th>
        <th>Document</th>
        <th>Upload Date</th>
        <th>Uploaded By</th>
{{--        <th>Updated By</th>--}}
        <th>Action</th>
        <th>Remarks</th>
    </tr>
    </tfoot>
    @endif
    <tbody>
    @if(isset($voucherInfos) && count($voucherInfos))
        @php
            $no= 1;
        @endphp
        @foreach($voucherInfos as $data)
            <tr class="">
            @if(isset($voucherInfos) && count($voucherInfos))
                <th><input class="check-box" type="checkbox" name="selected[]" id="select_{!! $data->id !!}" value="{!! $data->id !!}"></th>
            @endif
                <td>{!! $no++ !!}</td>
                <td>{!! date('d-M-y', strtotime($data->voucher_date)) !!}</td>
                <td>{!! $data->company->company_code !!}</td>
                <td>{!! @$data->project->branch_name !!}</td>
                <td>{!! $data->voucher_number !!}</td>
                <td>{!! $data->VoucherType->voucher_type_title !!}</td>
                <td class="text-start text-left">
                    @php $x = 1;@endphp
                    <table class="table table-bordered table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Document Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data->voucherDocuments as $d)
                            <tr>
                                <td>
                                    <strong>{!! $x++ !!}.</strong>
                                    {!! preg_replace('/^.*_/', '', pathinfo($d->document, PATHINFO_FILENAME)) . '.' . pathinfo($d->document, PATHINFO_EXTENSION) !!}
                                <td>
                                    <a class="text-primary" href="" title="Quick View" vtype="{!! $data->VoucherType->voucher_type_title !!}" vno="{!! $data->voucher_number !!}" path="{!! \Illuminate\Support\Facades\Crypt::encryptString(asset('storage/archive_data/'.$d->filepath.$d->document)) !!}" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}" onclick="return Obj.findDocument(this,'documentPreview','v_type','v_no')"> <i class="fa-solid fa-eye"></i></a>
                                    &nbsp;
                                    <a class="text-success" href="{!! route('view.archive.document',['vID'=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)]) !!}" title="View on new window" target="_blank"><i class="fa-solid fa-up-right-from-square"></i></a>
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
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @if(auth()->user()->hasPermission('add_archive_document_individual_quick'))
                    <a href="" class="text-end float-end badge bg-success text-decoration-none" onclick="return Obj.addArchiveDocumentIndividual(this)" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}"><i class="fas fa-plus"></i> Add New</a>
                @endif
                </td>
                {{--                                        <td></td>--}}
                {{--                                        <td></td>--}}
                <td>{!! date('d-M-y', strtotime($data->created_at)) !!}</td>
                <td>{!! ($data->createdBY)? $data->createdBY->name:'-' !!}</td>
{{--                <td>{!! ($data->updatedBY)? $data->updatedBY->name:'-' !!}</td>--}}
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
                <td>@if($data->remarks)<a href="" data-bs-toggle="modal" data-bs-target="#remarksShowModal" data-remarks="{{ $data->remarks }}" onclick="remarksShowing(this)">{{ substr($data->remarks, 0, 20) }}...</a> @endif</td>
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

<!-- Modal for details -->
<div class="modal fade" id="remarksShowModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="remarksShowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remarksShowModalLabel">Remarks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="remarksContent">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
            </div>
        </div>
    </div>
</div>
<script id="fixedDivScript">
    var fixedDiv = $("#fixedDiv");
    var fixedDiv2 = $("#fixedDiv2");

    if (typeof fixedDiv.offset() !== "undefined") {
        let initialOffset = fixedDiv.offset().top;

        $(window).scroll(function () {
            const scrollPos = $(window).scrollTop();

            if (scrollPos > initialOffset) {
                fixedDiv.addClass("fixed");
            } else {
                fixedDiv.removeClass("fixed");
            }
        });
    }
    if (typeof fixedDiv2.offset() !== "undefined") {
        let initialOffset = fixedDiv2.offset().top;

        $(window).scroll(function () {
            const scrollPos = $(window).scrollTop();

            if (scrollPos > initialOffset) {
                fixedDiv2.addClass("fixed");
            } else {
                fixedDiv2.removeClass("fixed");
            }
        });
    }
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
                        const table = $('#DataTable2').DataTable();

                        // Add search inputs, skipping the first two columns
                        $('#DataTable2 tfoot th').each(function (index) {
                            if (index > 1) { // Skip first two columns
                                const title = $('#DataTable2 tfoot th').eq(index).text();
                                $(this).html('<input type="text" class="form-control" placeholder="' + title + '..." />');
                            }
                        });

                        // Apply search functionality only to columns after the first two
                        $('#DataTable2 tfoot input').on('keyup change', function () {
                            const columnIndex = $(this).parent().index(); // Get the column index
                            table.column(columnIndex).search(this.value).draw();
                        });
                    }
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
        window.remarksShowing = function (e){
            var remarks = $(e).data('remarks');

            // Set the content inside the modal
            $('#remarksContent').text(remarks);
        }
    }(jQuery))
</script>
