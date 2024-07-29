<div class="row">
    <h5 class="text-capitalize">Fixed Asset Specification List</h5>
</div>
<table class="table table-sm" id="permissionstable">
    <thead>
    <tr>
        <th>No</th>
        <th>Recourse Code</th>
        <th>Materials</th>
        <th>Specification</th>
        <th>Status</th>
        <th>Created By</th>
        <th>Updated By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tfoot></tfoot>
    <tbody>
    @if(count($fixed_asset_specifications))
        @php
            $no= 1;
        @endphp
        @foreach($fixed_asset_specifications as $fs)
            <tr class="{!! (isset($fas) && $fs->id == $fas->id)?'text-primary':'' !!}">
                <td>{!! $no++ !!}</td>
                <td>{!! $fs->fixed_asset->recourse_code !!}</td>
                <td>{!! $fs->fixed_asset->materials_name !!}</td>
                <td>{!! $fs->specification !!}</td>
                <td>{!! (isset($fs->createdBy->name))?$fs->createdBy->name:'-' !!}</td>
                <td>{!! (isset($fs->updatdBy->name))?$fs->updatdBy->name:'-' !!}</td>
                <td>@if($fs->status==1) <span class='badge bg-success'> Active</span> @else <span class='badge bg-danger'>Inactive </span>@endif</td>

                <td>
                    <div class="text-center">
                        @if(auth()->user()->hasPermission('edit_fixed_asset_specification'))
                            <a href="{!! route('edit.fixed.asset.specification',['fasid'=>\Illuminate\Support\Facades\Crypt::encryptString($fs->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                        @endif
                        @if(auth()->user()->hasPermission('fixed_asset_delete'))
                            <form action="{{route('fixed.asset.delete')}}" class="display-inline" method="post">
                                @method('delete')
                                @csrf
                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($fs->id) !!}">
                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="9" class="text-center text-danger">Not found!</td>
        </tr>
    @endif
    </tbody>
</table>
