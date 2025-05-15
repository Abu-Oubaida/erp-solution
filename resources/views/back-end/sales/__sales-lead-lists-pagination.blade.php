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
<div class="row mb-1 mobile-none" id="fixedDiv">
    <div class="col-md-12">
        <input type="checkbox" name="" id="select_all">
        <label for="select_all">Select All</label>
        @if(auth()->user()->hasPermission('multiple_archive_data_delete'))
            <button class="btn btn-outline-danger btn-sm" name="submit_selected" type="submit" value="delete" onclick="return Obj.deleteArchiveMultiple(this,false)"> <i class="fas fa-trash"></i> Delete Selected</button>
        @endif
        @if(auth()->user()->hasPermission('share_archive_data_multiple'))
        <button class="btn btn-outline-primary btn-sm" onclick="return Obj.shareArchiveMultiple(this)"> <i class="fas fa-share"></i> Send by Email</button>
        @endif
{{--            <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-file-zipper"></i> Download Zip</button>--}}
    </div>

</div>
<style>
    #fixedDiv2 tr th input{
        font-size: 12px!important;
    }
</style>
<table class="table table-sm table-hover" id="DataTableSales" style="font-size: 12px;">
    <thead class="">
        <tr>
            <th>SL</th>
            <th>Lead Id</th>
            <th>Creation</th>
            <th>Name</th>
            <th>Mobiles</th>
            <th>Associate</th>
            <th>Notes</th>
            <th>Lead Status</th>
            <th>Status</th>
            <th>Sell Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tfoot id="fixedDiv2">
        <tr>
            {{-- <th class="mobile-none" rowspan="1" colspan="1">Select</th> --}}
            <th>SL</th>
            <th>Lead Id</th>
            <th>Creation</th>
            <th>Name</th>
            <th>Mobiles</th>
            <th>Associate</th>
            <th>Notes</th>
            <th>Lead Status</th>
            <th>Status</th>
            <th>Sell Status</th>
            <th>Action</th>
        </tr>
    </tfoot>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($formattedLeads as $data)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data['lead_id'] ?? '-' }}</td>
                <td>{{ $data['creation'] ?? '-' }}</td>
                <td>{{ $data['full_name'] ?? '-' }}</td>
                <td>
                    {{ $data['primary_mobile'] }} <br>
                    @if (isset($data['mobiles']) && is_array($data['mobiles']) && count($data['mobiles']) > 0)
                        @foreach ($data['mobiles'] as $mobile)
                            {{ $mobile }} <br>
                        @endforeach
                    @endif
                </td>
                <td>{{ $data['associate_name'] ?? '-' }}</td>
                <td>{{ $data['notes'] ?? '-' }}</td>
                <td>{{ $data['lead_status_id'] ?? '-' }}</td>
                <td>{{ $data['status'] === 5 || $data['status'] === 6 || $data['status'] === 7 ? 'Incomplete' : 'Complete' }}
                </td>
                <td>{{ $data['sell_status'] === 0 ? 'Unsold' : 'Sold' }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
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

    (function($) {
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#DataTableSales')) {
                $('#DataTableSales').DataTable({
                    dom: 'Bfrtip', // 'l' includes the "length changing" input
                    lengthMenu: [
                        [-1],
                        ["ALL"]
                    ],
                    pageLength: -1,
                    fixedHeader: {
                        header: true
                    },
                    buttons: [{
                            extend: 'pdfHtml5',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            orientation: 'landscape', // Landscape orientation
                            pageSize: 'A4', // A4 page size
                            title: 'My Table Export', // Optional: Custom title
                            exportOptions: {
                                columns: ':visible', // Export only visible columns
                                format: {
                                    header: function(data, columnIdx) {
                                        // Extract the header text from the <th> element, ignoring the input field
                                        return $('#DataTableSales tfoot th').eq(columnIdx)
                                            .text();
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
                                    header: function(data, columnIdx) {
                                        return $('#DataTableSales tfoot th').eq(columnIdx)
                                            .text();
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
                                    header: function(data, columnIdx) {
                                        return $('#DataTableSales tfoot th').eq(columnIdx)
                                            .text();
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
                                    header: function(data, columnIdx) {
                                        return $('#DataTableSales tfoot th').eq(columnIdx)
                                            .text();
                                    }
                                }
                            }
                        }
                    ],
                    initComplete: function() {
                        // Add search inputs to header
                        $('#DataTableSales thead th').each(function() {
                            var title = $(this)
                        .text(); // Use the text content of the header cells
                            $(this).html(
                                '<input type="text" class="form-control" placeholder="' +
                                title + '..." />');
                        });

                        // Apply the search
                        var table = this.api(); // Use the DataTables API instance
                        table.columns().eq(0).each(function(colIdx) {
                            $('input', table.column(colIdx).header()).on('keyup change',
                                function() {
                                    table.column(colIdx).search(this.value).draw();
                                });
                        });
                    }
                });
            }
        })
    }(jQuery))
</script>
