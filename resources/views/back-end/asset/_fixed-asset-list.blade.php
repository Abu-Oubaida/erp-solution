<div class="row">
    <h3 class="text-capitalize">Fixed Asset Materials List</h3>
</div>
<table class="table table-sm" id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Recourse Code</th>
        <th>Materials</th>
        <th>Status</th>
        <th>Rate</th>
        <th>Unit</th>
        <th>Depreciation Rate</th>
        <th>Remarks</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($fixed_assets) && count($fixed_assets))
        @php
            $no= 1;
        @endphp
        @foreach($fixed_assets as $fa)
            <tr class="{!! (isset($fixed_asset) && $fa->id == $fixed_asset->id)?'text-primary':'' !!}">
                <td>{!! $no++ !!}</td>
                <td>{!! $fa->recourse_code !!}</td>
                <td>{!! $fa->materials_name !!}</td>
                <td>@if($fa->status==1) <span class='badge bg-success'> Active</span> @else <span class='badge bg-danger'>Inactive </span>@endif</td>
                <td>{!! $fa->rate !!}</td>
                <td>{!! $fa->unit !!}</td>
                <td>{!! $fa->depreciation !!}</td>
                <td>{!! $fa->remarks !!}</td>
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
    @endif
    </tbody>
</table>

