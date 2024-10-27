<table class="table table-sm" id="datatablesSimple">
    <thead>
        <tr>
            <th>No</th>
            <th>Company</th>
            <th>Materials</th>
            <th>Recourse Code</th>
            <th title="Count of Specifications" class="text-center">Specifications Count</th>
            <th>Status</th>
            <th>Unit</th>
            <th>Rate</th>
            <th>Depreciation(%)</th>
            <th>Uses Count</th>
            <th>Remarks</th>
            <th>Created By</th>
            <th>Created At</th>
            <th>Updated By</th>
            <th>Updated At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tfoot></tfoot>
    <tbody>
    @if(count($fixed_assets))
        @php
            $no= 1;
        @endphp
        @foreach($fixed_assets as $fa)
            <tr class="{!! (isset($fixed_asset) && $fa->id == $fixed_asset->id)?'text-primary':'' !!} text-center">
                <td>{!! $no++ !!}</td>
                <td>{!! $fa->company->company_name !!}</td>
                <td>{!! $fa->materials_name !!}</td>
                <td>{!! $fa->recourse_code !!}</td>
                <td class="text-center"><a href="{!! route('fixed.asset.specification',['c'=>$fa->company_id,'fid'=>$fa->id,'code'=>$fa->recourse_code,'name'=>$fa->materials_name]) !!}" target="_blank">{!! count(@$fa->specifications) !!}</a></td>
                <td>@if($fa->status==1) <span class='badge bg-success'> Active</span> @else <span class='badge bg-danger'>Inactive </span>@endif</td>
                <td>{!! $fa->unit !!}</td>
                <td>{{ $fa->rate }}</td>
                <td>{{ $fa->depreciation }}</td>
                <td><span class="badge bg-secondary">With Ref. ({{ count($fa->withRefUses) }})</span></td>
                <td>{{ $fa->remarks }}</td>
                <td>{{ @$fa->createdBy->name }}</td>
                <td>{!! date('d-M-y',strtotime(@$fa->created_at)) !!}</td>
                <td>{{ @$fa->updatedBy->name }}</td>
                <td>{!! isset($fa->updatedBy->name)?date('d-M-y',strtotime(@$fa->updated_at)):'' !!}</td>
                <td>
                    <div class="text-center">
                        @if(auth()->user()->hasPermission('fixed_asset_edit'))
                            <a href="{!! route('fixed.asset.edit',['fixedAssetID'=>\Illuminate\Support\Facades\Crypt::encryptString($fa->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                        @endif
                        @if(auth()->user()->hasPermission('fixed_asset_delete'))
                            <form action="{{route('fixed.asset.delete')}}" class="display-inline" method="post">
                                @method('delete')
                                @csrf
                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($fa->id) !!}">
                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="14" class="text-center text-danger">Not found!</td>
        </tr>
    @endif
    </tbody>
</table>

