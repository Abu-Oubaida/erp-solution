<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            @if(isset($fixed_asset_with_ref_report_list))
            <div class="row">
                <div class="col-md-12">
                    <table @if(count($fixed_asset_with_ref_report_list))id="DataTable2" class="display" @else class="table" @endif style="width: 100%">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Date</th>
                            <th>Reference Type</th>
                            <th>Reference</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Resource Count</th>
                            <th>Documents</th>
                            <th>Narration</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Updated By</th>
                            <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>SL.</th>
                            <th>Date</th>
                            <th>Reference Type</th>
                            <th>Reference</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Resource Count</th>
                            <th>Documents</th>
                            <th>Narration</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Updated By</th>
                            <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @if(count($fixed_asset_with_ref_report_list))
                            @php($n=1)
                            @foreach($fixed_asset_with_ref_report_list as $pwr)
                                <tr class="text-center text-capitalize">
                                    <td>{!! $n++ !!}</td>
                                    <td>{!! date('d-M-Y', strtotime($pwr->date)) !!}</td>
                                    <td>{!! $pwr->refType->name !!}</td>
                                    <td>{!! $pwr->references !!}</td>
                                    <td>{!! $pwr->branch->branch_name !!}</td>
                                    <td>
                                        @if($pwr->status == 1) <span class="badge bg-success">Active</span>
                                        @elseif($pwr->status == 2) <span class="badge bg-info">Approved</span>
                                        @elseif($pwr->status == 3) <span class="badge bg-warning">Pending</span>
                                        @elseif($pwr->status == 4) <span class="badge bg-dark">Declined</span>
                                        @elseif($pwr->status == 5) <span class="badge bg-primary">Processing</span>
                                        @else <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{!! count($pwr->withSpecifications) !!}</td>
                                    <td>
                                        @if(isset($pwr->attestedDocuments) && count($pwr->attestedDocuments))
                                            @php($i = 1)
                                            <ol>
                                            @foreach($pwr->attestedDocuments as $d)
                                                <li><a href="{!! url($d->document_url.$d->document_name) !!}" target="_blank">Document {!! $i++ !!}</a></li>
                                            @endforeach
                                            </ol>
                                        @endif
                                    </td>
                                    <td>{!! $pwr->narration !!}</td>
                                    <td>{!! (isset($pwr->createdBy->name))?$pwr->createdBy->name:'-' !!}</td>
                                    <td>{!! date('d-M-Y', strtotime($pwr->created_at)) !!}</td>
                                    <td>{!! (isset($pwr->updatedBy->name))?$pwr->updatedBy->name:'-' !!}</td>
                                    <td>{!! date('d-M-Y', strtotime($pwr->updated_at)) !!}</td>
                                    <td class="text-center">
                                        @if($pwr->status == 5)
                                            <a href="{!! url(route('fixed.asset.distribution.opening.input',['ref'=>$pwr->references,'project'=>$pwr->branch->id,'rt'=>$pwr->refType->id])) !!}" target="_blank"><i class="fas fa-edit"></i></a>
                                            <button onclick="return Obj.deleteFixedAssetOpening(this)" class="text-danger border-0 inline-block bg-none" ref="{!! $pwr->id !!}" ><i class="fas fa-trash"></i></button>
                                        @else
                                            <a href="{!! route('fixed.asset.with.reference.print',['assetID'=>\Illuminate\Support\Facades\Crypt::encryptString($pwr->id)]) !!}" class="text-success border-0 inline-block bg-none" target="_blank"><i class="fas fa-print"></i></a>
                                            @if(auth()->user()->hasPermission('edit_fixed_asset_distribution_with_reference'))
                                                <a href="{!! route('edit.fixed.asset.distribution.with.reference.balance',['faobid'=>\Illuminate\Support\Facades\Crypt::encryptString($pwr->id)]) !!}" class="text-info"><i class="fas fa-edit"></i></a>
                                            @endif
                                            @if(auth()->user()->hasPermission('delete_fixed_asset_opening_balance'))
                                                <button onclick="return Obj.deleteFixedAssetOpening(this)" class="text-danger border-0 inline-block bg-none" ref="{!! $pwr->id !!}" ><i class="fas fa-trash"></i></button>
                                            @endif
                                        @endif
{{--                                        <button class="text-success border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.fixedAssetOpeningSpecEdit(this)"><i class="fas fa-edit"></i></button>--}}
{{--                                        <button class="text-danger border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.deleteFixedAssetOpeningSpec(this)"><i class="fas fa-trash"></i></button>--}}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="14" class="text-center">Not Found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
{{--                    <button id="export-csv" class="btn btn-sm btn-outline-success"> <i class="fas fa-download"></i> Export CSV</button>--}}
{{--                    <button id="export-sql" class="btn btn-sm btn-outline-success">Export SQL</button>--}}
{{--                    <button id="export-txt" class="btn btn-sm btn-outline-success"> <i class="fas fa-download"></i> Export TXT</button>--}}
{{--                    <button id="export-json" class="btn btn-sm btn-outline-success">Export JSON</button>--}}
{{--                    <button id="export-custom" class="btn btn-sm btn-outline-success"> <i class="fas fa-download"></i> Export Custom</button>--}}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
{{--<script type="module">--}}
{{--    import {--}}
{{--        DataTable,--}}
{{--        exportJSON,--}}
{{--        exportCSV,--}}
{{--        exportTXT,--}}
{{--        exportSQL--}}
{{--    } from "https://fiduswriter.github.io/simple-datatables/demos/dist/module.js"--}}


{{--    const exportCustomCSV = function(dataTable, userOptions = {}) {--}}
{{--        // A modified CSV export that includes a row of minuses at the start and end.--}}
{{--        const clonedUserOptions = {--}}
{{--            ...userOptions--}}
{{--        }--}}
{{--        clonedUserOptions.download = false--}}
{{--        const csv = exportCSV(dataTable, clonedUserOptions)--}}
{{--        // If CSV didn't work, exit.--}}
{{--        if (!csv) {--}}
{{--            return false--}}
{{--        }--}}
{{--        const defaults = {--}}
{{--            download: true,--}}
{{--            lineDelimiter: "\n",--}}
{{--            columnDelimiter: ";"--}}
{{--        }--}}
{{--        const options = {--}}
{{--            ...defaults,--}}
{{--            ...clonedUserOptions--}}
{{--        }--}}
{{--        const separatorRow = Array(dataTable.data.headings.filter((_heading, index) => !dataTable.columns.settings[index]?.hidden).length)--}}
{{--            .fill("-")--}}
{{--            .join(options.columnDelimiter)--}}
{{--        const str = `${separatorRow}${options.lineDelimiter}${csv}${options.lineDelimiter}${separatorRow}`--}}
{{--        if (userOptions.download) {--}}
{{--            // Create a link to trigger the download--}}
{{--            const link = document.createElement("a")--}}
{{--            link.href = encodeURI(`data:text/csv;charset=utf-8,${str}`)--}}
{{--            link.download = `${options.filename || "datatable_export"}.txt`--}}
{{--            // Append the link--}}
{{--            document.body.appendChild(link)--}}
{{--            // Trigger the download--}}
{{--            link.click()--}}
{{--            // Remove the link--}}
{{--            document.body.removeChild(link)--}}
{{--        }--}}
{{--        return str--}}
{{--    }--}}

{{--    const table = new DataTable("#datatablesSimple")--}}

{{--    // Using jQuery to handle button clicks--}}
{{--    $("#export-csv").click(() => {--}}
{{--        exportCSV(table, {--}}
{{--            download: true,--}}
{{--            lineDelimiter: "\n",--}}
{{--            columnDelimiter: ","--}}
{{--        })--}}
{{--    })--}}

{{--    $("#export-sql").click(() => {--}}
{{--        exportSQL(table, {--}}
{{--            download: true,--}}
{{--            tableName: "export_table"--}}
{{--        })--}}
{{--    })--}}

{{--    $("#export-txt").click(() => {--}}
{{--        exportTXT(table, {--}}
{{--            download: true--}}
{{--        })--}}
{{--    })--}}

{{--    $("#export-json").click(() => {--}}
{{--        exportJSON(table, {--}}
{{--            download: true,--}}
{{--            space: 3--}}
{{--        })--}}
{{--    })--}}

{{--    $("#export-custom").click(() => {--}}
{{--        exportCustomCSV(table, {--}}
{{--            download: true--}}
{{--        })--}}
{{--    })--}}
{{--</script>--}}
@if(count($fixed_asset_with_ref_report_list))
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
                        columns: ':visible' // Export only visible columns
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                },
                {
                    extend:'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                },
            ],
            initComplete: function () {
                // Add search inputs
                $('#DataTable2 thead th').each(function() {
                    var title = $('#DataTable2 tfoot th').eq($(this).index()).text();
                    $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
                });

                // Apply the search
                var table = $('#DataTable2').DataTable();
                table.columns().eq(0).each(function(colIdx) {
                    $('input', table.column(colIdx).header()).on('keyup change', function() {
                        table.column(colIdx).search(this.value).draw();
                    });
                });
            }
        })
    })
}(jQuery))
</script>
@endif
