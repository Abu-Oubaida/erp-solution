 <table id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Company</th>
        <th>Name</th>
        <th>Code</th>
        <th>Status</th>
        <th>Details</th>
        <th>Created By</th>
        <th>Created At</th>
        <th>Updated By</th>
        <th>Updated At</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
@if(isset($op_ref_types) && count($op_ref_types))
@php($n=1)
@foreach($op_ref_types as $type)
    <tr>
        <td>{!! $n++ !!}</td>
        <td>{!! $type->company->company_name !!}</td>
        <td>{!! $type->name !!}</td>
        <td>{!! $type->code !!}</td>
        <td>@if(isset($type->status) && $type->status == 1) <span class="badge bg-success">Active</span>@else <span class="badge bg-danger"> Inactive</span> @endif</td>
        <td>{!! $type->description !!}</td>
        <td>{!! (isset($type->createdBy->name)?$type->createdBy->name:'-') !!}</td>
        <td>{!! date('d-M-y',strtotime(@$type->created_at)) !!}</td>
        <td>{!! (isset($type->updatedBy->name)?$type->createdBy->name:'-') !!}</td>
        <td>{!! isset($type->updatedBy->name)?date('d-M-y',strtotime(@$type->updated_at)):'-' !!}</td>
        <td>
            <a href="{{route('op.reference.type.edit',["typeID"=>\Illuminate\Support\Facades\Crypt::encryptString($type->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
            <form action="{{route('op.reference.type.delete')}}" class="display-inline" method="post">
                @method('delete')
                @csrf
                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($type->id) !!}">
                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
@endforeach
@endif
    </tbody>
</table>

