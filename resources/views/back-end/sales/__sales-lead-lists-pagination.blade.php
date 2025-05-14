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
        background-color: rgba(255, 255, 255, 0.95);
        /* Change background color when fixed */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Add a shadow when fixed */
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
        background-color: rgba(99, 99, 99, 0.84);
        /* Change background color when fixed */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Add a shadow when fixed */
    }

    .table {
        font-size: 12px !important;
    }
</style>
{{-- @if (isset($voucherInfos) && count($voucherInfos))
    <div class="row mb-1 mobile-none" id="fixedDiv">
        <div class="col-md-12">
            <input type="checkbox" name="" id="select_all">
            <label for="select_all">Select All</label>
            @if (auth()->user()->hasPermission('multiple_archive_data_delete'))
                <button class="btn btn-outline-danger btn-sm" name="submit_selected" type="submit" value="delete" onclick="return Obj.deleteArchiveMultiple(this,false)"> <i class="fas fa-trash"></i> Delete Selected</button>
            @endif
            @if (auth()->user()->hasPermission('share_archive_data_multiple'))
            <button class="btn btn-outline-primary btn-sm" onclick="return Obj.shareArchiveMultiple(this)"> <i class="fas fa-share"></i> Send by Email</button>
            @endif

        </div>

    </div>
@endif --}}
<style>
    #fixedDiv2 tr th input {
        font-size: 12px !important;
    }
</style>
<table class="table table-sm table-hover" @if (isset($formattedLeads) && count($formattedLeads)) id="DataTable2" @endif>
    <thead class="">
        <tr class="bg-secondary text-chl-white">
            <th>SL</th>
            <th>Lead Name</th>
            <th>Primary Mobile</th>
            <th>Primary Email</th>
            <th>Spouse</th>
            <th>Alternative Mobiles</th>
            <th>Alternative Emails</th>
            <th>Note</th>
            <th>Main Profession</th>
            <th>Sub Profession</th>
            <th>Lead Company</th>
            <th>Lead Designation</th>
            <th>Main Source</th>
            <th>Sub Source</th>
            <th>Reference</th>
            <th>Preference</th>
            <th>Apartment Type</th>
            <th>Apartment Size Name</th>
            <th>Apartment Size</th>
            <th>Apartment Floor</th>
            <th>Apartment Facing</th>
            <th>Apartment View</th>
            <th>Apartment View</th>
            <th>Apartment Budget</th>
            <th>Associate</th>
            <th>Lead Status</th>
            <th>Status</th>
            <th>Sell Status</th>
            <th>Created By</th>
            <th>Updated By</th>
            <th>Action</th>
           
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($formattedLeads as $data)
            <tr>
                <td>{!! $no++ !!}</td>
                <td>{!! $data['full_name'] !!}</td>
                <td>{!! $data['primary_mobile'] !!}</td>
                <td>{!! $data['primary_email'] !!}</td>
                <td>{!! $data['spouse'] !!}</td>
                <td>-</td>
                <td>-</td>
                <td>{!! $data['notes'] !!}</td>
                <td>{!! $data['main_profession'] !!}</td>
                <td>{!! $data['sub_profession'] !!}</td>
                <td>{!! $data['lead_company'] !!}</td>
                <td>{!! $data['lead_designation'] !!}</td>
                <td>{!! $data['main_source'] !!}</td>
                <td>{!! $data['sub_source'] !!}</td>
                <td>{!! $data['reference_name'] !!}</td>
                <td>{!! $data['preference_note'] !!}</td>
                <td>{!! $data['apartment_type_name'] !!}</td>
                <td>{!! $data['apartment_size_name'] !!}</td>
                <td>{!! $data['apartment_size'] !!}</td>
                <td>{!! $data['apartment_floor'] !!}</td>
                <td>{!! $data['apartment_facing'] !!}</td>
                <td>{!! $data['associate_id'] !!}</td>
                <td>{!! $data['lead_status_id'] !!}</td>
                <td>{!! $data['status'] !!}</td>
                <td>{!! $data['sell_status'] !!}</td>
                <td>{!! $data['created_by'] !!}</td>
                <td>{!! $data['updated_by'] !!}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- @method('post') --}}
{{-- </form> --}}
<!-- Modal For Preview -->
{{-- <div class="modal modal-xl fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="v_document_name"> <i class="fas fa-eye"></i> Document Quick View</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="documentPreview"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}
<!-- Modal-2 For Share -->

{{-- <div class="modal modal-xl fade" id="shareModel" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="shareModelLabel" aria-hidden="true">
    <div class="modal-dialog" id="model_dialog">

    </div>
    <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
        <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
    </div>
</div> --}}

<!-- Modal for details -->
{{-- <div class="modal fade" id="remarksShowModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="remarksShowModalLabel" aria-hidden="true">
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
</div> --}}
<style>
    .dt-buttons {
        float: left;
        margin-left: 0 !important;
    }

    .dataTables_info {
        display: none !important;
    }

    .dataTables_paginate {
        display: none !important;
    }
</style>
<script id="fixedDivScript">
    // var fixedDiv = $("#fixedDiv");
    // var fixedDiv2 = $("#fixedDiv2");

    // if (typeof fixedDiv.offset() !== "undefined") {
    //     let initialOffset = fixedDiv.offset().top;

    //     $(window).scroll(function () {
    //         const scrollPos = $(window).scrollTop();

    //         if (scrollPos > initialOffset) {
    //             fixedDiv.addClass("fixed");
    //         } else {
    //             fixedDiv.removeClass("fixed");
    //         }
    //     });
    // }
    // if (typeof fixedDiv2.offset() !== "undefined") {
    //     let initialOffset = fixedDiv2.offset().top;

    //     $(window).scroll(function () {
    //         const scrollPos = $(window).scrollTop();

    //         if (scrollPos > initialOffset) {
    //             fixedDiv2.addClass("fixed");
    //         } else {
    //             fixedDiv2.removeClass("fixed");
    //         }
    //     });
    // }
    (function($) {
        $("#select_all").change(function() {
            $(".check-box").prop("checked", this.checked);
        });
        // $(document).ready(function () {

        //     if (!$.fn.DataTable.isDataTable('#DataTable2')) {
        //         $('#DataTable2').DataTable({
        //             dom: 'Bfrtip', // 'l' includes the "length changing" input
        //             pageLength: -1,
        //             fixedHeader: {
        //                 header: true
        //             },
        //             buttons: [
        //                 {
        //                     extend: 'pdfHtml5',
        //                     text: '<i class="fas fa-file-pdf"></i> PDF',
        //                     orientation: 'landscape', // Landscape orientation
        //                     pageSize: 'A4', // A4 page size
        //                     title: 'My Table Export', // Optional: Custom title
        //                     exportOptions: {
        //                         columns: ':visible', // Export only visible columns
        //                         format: {
        //                             header: function (data, columnIdx) {
        //                                 // Extract the header text from the <th> element, ignoring the input field
        //                                 return $('#DataTable2 thead th').eq(columnIdx).text();
        //                             }
        //                         }
        //                     }
        //                 },
        //                 {
        //                     extend: 'excel',
        //                     text: '<i class="fas fa-file-excel"></i> Excel',
        //                     exportOptions: {
        //                         columns: ':visible',
        //                         format: {
        //                             header: function (data, columnIdx) {
        //                                 return $('#DataTable2 thead th').eq(columnIdx).text();
        //                             }
        //                         }
        //                     }
        //                 },
        //                 {
        //                     extend: 'print',
        //                     text: '<i class="fas fa-print"></i> Print',
        //                     exportOptions: {
        //                         columns: ':visible',
        //                         format: {
        //                             header: function (data, columnIdx) {
        //                                 return $('#DataTable2 thead th').eq(columnIdx).text();
        //                             }
        //                         }
        //                     }
        //                 }
        //             ],
        //             initComplete: function () {
        //                 const table = $('#DataTable2').DataTable();

        //                 // Add search inputs, skipping the first two columns
        //                 $('#DataTable2 tfoot th').each(function (index) {
        //                     if (index > 1) { // Skip first two columns
        //                         const title = $('#DataTable2 tfoot th').eq(index).text();
        //                         $(this).html('<input type="text" class="form-control" placeholder="' + title + '..." />');
        //                     }
        //                 });

        //                 // Apply search functionality only to columns after the first two
        //                 $('#DataTable2 tfoot input').on('keyup change', function () {
        //                     const columnIndex = $(this).parent().index(); // Get the column index
        //                     table.column(columnIndex).search(this.value).draw();
        //                 });
        //             }
        //         });
        //     }
        // })
        // window.remarksShowing = function (e){
        //     var remarks = $(e).data('remarks');

        //     // Set the content inside the modal
        //     $('#remarksContent').text(remarks);
        // }
    }(jQuery))
</script>
