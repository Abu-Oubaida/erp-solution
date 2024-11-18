<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            @if(isset($fixed_asset_with_ref_report_list))
            <div class="row">
                <div class="col-md-12" style="font-size: 13px">
                    <table @if(count($fixed_asset_with_ref_report_list))id="userTable" class="display" @else class="table" @endif style="width: 100%; margin: 0; padding: 0; ">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Company</th>
                            <th>Project</th>
                            <th>Date</th>
                            <th title="Reference Type">Type</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th title="Resource Count">Resource</th>
                            <th title="Total Amount">Total</th>
                            <th>Documents</th>
                            <th>Narration</th>
                            <th>Created By</th>
                            <th title="Created Date">Created</th>
                            <th>Updated By</th>
                            <th title="Updated Date">Updated</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        @if(count($fixed_asset_with_ref_report_list))
                        <tfoot>
                        <tr>
                            <th>SL.</th>
                            <th>Company</th>
                            <th>Project</th>
                            <th>Date</th>
                            <th title="Reference Type">Type</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th title="Resource Count">Resource</th>
                            <th title="Total Amount">Total</th>
                            <th>Documents</th>
                            <th>Narration</th>
                            <th>Created By</th>
                            <th title="Created Date">Created</th>
                            <th>Updated By</th>
                            <th title="Updated Date">Updated</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                        @endif
                        <tbody>
                        @if(count($fixed_asset_with_ref_report_list))
                            @php($n=1)
                            @foreach($fixed_asset_with_ref_report_list as $pwr)
                                <tr class="text-center text-capitalize">
                                    <td>{!! $n++ !!}</td>
                                    <td>{!! $pwr->company->company_name !!}</td>
                                    <td>{!! $pwr->branch->branch_name !!}</td>
                                    <td>{!! date('d-M-Y', strtotime($pwr->date)) !!}</td>
                                    <td>{!! $pwr->refType->name !!}</td>
                                    <td>{!! $pwr->references !!}</td>
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
                                        @php($total = 0)
                                        @if(count($pwr->withSpecifications))
                                            @foreach($pwr->withSpecifications as $fs)
                                                @php($total += ($fs->rate*$fs->qty))
                                            @endforeach
                                        @endif
                                        {!! $total !!}/=
                                    </td>
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
                                    <td>{!! (isset($pwr->updatedBy->name))?date('d-M-Y', strtotime($pwr->updated_at)):'-' !!}</td>
                                    <td class="text-center">
                                        @if($pwr->status == 5)
                                            <a href="{!! url(route('fixed.asset.distribution.opening.input',['ref'=>$pwr->references,'project'=>$pwr->branch->id,'rt'=>$pwr->refType->id,'c'=>$pwr->company_id])) !!}" target="_blank"><i class="fas fa-edit"></i></a>
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
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@if(count($fixed_asset_with_ref_report_list))
<script>
(function ($){
    $(document).ready(function(){
        $('#userTable').DataTable({
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
                                return $('#userTable tfoot th').eq(columnIdx).text();
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
                                return $('#userTable tfoot th').eq(columnIdx).text();
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
                                return $('#userTable tfoot th').eq(columnIdx).text();
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
                                return $('#userTable tfoot th').eq(columnIdx).text();
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
                                return $('#userTable tfoot th').eq(columnIdx).text();
                            }
                        }
                    },
                },
            ],
            initComplete: function () {
                // Add search inputs
                $('#userTable thead th').each(function() {
                    var title = $('#userTable tfoot th').eq($(this).index()).text();
                    $(this).html('<input type="text" class="form-control" placeholder="' + title + '..." />');
                });

                // Apply the search
                var table = $('#userTable').DataTable();
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
@endif
