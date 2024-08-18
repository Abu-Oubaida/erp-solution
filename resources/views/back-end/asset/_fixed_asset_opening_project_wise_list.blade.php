<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            @if(isset($fixed_asset_with_ref_report_list))
            <div class="row">
                <div class="col-md-12">
                    <table id="datatablesSimple" class="table table-hover table-bordered">
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
                                            <a class="text-success border-0 inline-block bg-none" ref="" onclick=""><i class="fas fa-print"></i></a>
                                            @if(auth()->user()->hasPermission('edit_fixed_asset_opening_balance'))
                                                <a href="{!! route('edit.fixed.asset.opening.balance',['faobid'=>\Illuminate\Support\Facades\Crypt::encryptString($pwr->id)]) !!}" class="text-info"><i class="fas fa-edit"></i></a>
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
                                <td colspan="13" class="text-center">Not Found</td>
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
