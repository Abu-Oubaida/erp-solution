<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            @if(isset($transferDatas))
            <div class="row">
                <div class="col-md-12" style="font-size: 13px">
                    <table @if(count($transferDatas))id="userTable" class="display table-hover table-sm" @else class="table" @endif style="width: 100%; margin: 0; padding: 0; ">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>From Company</th>
                            <th>From Project</th>
                            <th>To Company</th>
                            <th>To Project</th>
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
                        @if(count($transferDatas))
                        <tfoot>
                        <tr>
                            <th>SL.</th>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>From Company</th>
                            <th>From Project</th>
                            <th>To Company</th>
                            <th>To Project</th>
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
                        @if(count($transferDatas))
                            @php($n=1)
                            @foreach($transferDatas as $td)
                                <tr class="text-center text-capitalize">
                                    <td>{!! $n++ !!}</td>
                                    <td>{!! date('d-M-y',strtotime($td->date)) !!}</td>
                                    <td>{!! $td->reference !!}</td>
                                    <td>{!! $td->companyFrom->company_name !!}</td>
                                    <td>{!! $td->branchFrom->branch_name !!}</td>
                                    <td>{!! $td->companyTo->company_name !!}</td>
                                    <td>{!! $td->branchTo->branch_name !!}</td>
                                    <td>
                                        @if($td->status == 0)
                                            <span class="badge bg-info">Processing</span>
                                        @else
                                            <span class="badge bg-success">Stage-{!! $td->status !!}</span>
                                        @endif
                                    </td>
                                    <td>{!! count($td->specifications) !!}</td>
                                    <td>
                                        @php($total = 0)
                                        @if(count($td->specifications))
                                            @foreach($td->specifications as $fs)
                                                @php($total += ($fs->rate*$fs->qty))
                                            @endforeach
                                        @endif
                                        {!! $total !!}/=
                                    </td>
                                    <td class="">
                                        @if(isset($td->documents) && count($td->documents))
                                            @php($i = 1)
                                            <ul class="m-0 p-0 d-block text-decoration-none" style="text-align: left">
                                            @foreach($td->documents as $d)
                                                <li class="d-block m-0 p-0"><a href="{!! url($d->document_url.$d->document_name) !!}" target="_blank" title="{!! $d->document_name !!}">Document-{!! $i++ !!}</a></li>
                                            @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>{!! $td->narration !!}</td>
                                    <td>{!! (isset($td->createdBy->name))?$td->createdBy->name:'-' !!}</td>
                                    <td>{!! date('d-M-Y', strtotime($td->created_at)) !!}</td>
                                    <td>{!! (isset($td->updatedBy->name))?$td->updatedBy->name:'-' !!}</td>
                                    <td>{!! (isset($td->updatedBy->name))?date('d-M-Y', strtotime($td->updated_at)):'-' !!}</td>
                                    <td class="text-center">
                                        @if($td->status <= 0)
                                            <a href="{!! url(route('fixed.asset.transfer',['ref'=>$td->reference,'from_p'=>$td->from_project_id,'to_p'=>$td->to_project_id,'d'=>$td->date,'from_c'=>$td->from_company_id,'to_c'=>$td->to_company_id])) !!}" target="_blank"><i class="fas fa-edit"></i></a>
                                            <button onclick="return Obj.deleteFixedAssetRunningTransfer(this)" class="text-danger border-0 inline-block bg-none" ref="{!! $td->id !!}" ><i class="fas fa-trash"></i></button>
                                        @else
{{--                                            <a href="{!! route('fixed.asset.with.reference.print',['assetID'=>\Illuminate\Support\Facades\Crypt::encryptString($td->id)]) !!}" class="text-success border-0 inline-block bg-none" target="_blank"><i class="fas fa-print"></i></a>--}}
{{--                                            @if(auth()->user()->hasPermission('edit_fixed_asset_distribution_with_reference'))--}}
{{--                                                <a href="{!! route('edit.fixed.asset.distribution.with.reference.balance',['faobid'=>\Illuminate\Support\Facades\Crypt::encryptString($td->id)]) !!}" class="text-info"><i class="fas fa-edit"></i></a>--}}
{{--                                            @endif--}}
{{--                                            @if(auth()->user()->hasPermission('delete_fixed_asset_opening_balance'))--}}
{{--                                                <button onclick="return Obj.deleteFixedAssetOpening(this)" class="text-danger border-0 inline-block bg-none" ref="{!! $td->id !!}" ><i class="fas fa-trash"></i></button>--}}
{{--                                            @endif--}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="17" class="text-center">Not Found</td>
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
@if(count($transferDatas))
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
