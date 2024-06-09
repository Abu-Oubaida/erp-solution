<table id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Company Type</th>
        <th>Status</th>
        <th>Remarks</th>
        <th>Created By</th>
        <th>Created Time</th>
        <th>Updated By</th>
        <th>Updated Time</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(count($companyTypes))
        @php($i=1)
        @foreach($companyTypes as $companyType)
            <tr class="{!! (isset($editID) && $companyType->id == $editID->id)?'text-primary':'' !!}">
                <td>{!! $i++ !!}</td>
                <td>{!! $companyType->company_type_title !!}</td>
                <td>@if($companyType->status == 1) {!! '<span class="text-success">Active</span>' !!} @elseif($companyType->status == 3) {!! '<span class="text-warning">Deleted</span>' !!}  @else {!! '<span class="text-danger">Inactive</span>' !!} @endif</td>
                <td>{!! $companyType->remarks !!}</td>
                <td>{!! ($companyType->createdBY)?$companyType->createdBY->name:'-' !!}</td>
                <td>{!! $companyType->created_at !!}</td>
                <td>{!! ($companyType->updatedBY)?$companyType->updatedBY->name:"-" !!}</td>
                <td>{!! $companyType->updated_at !!}</td>
                <td>
                    <a href="{{route('edit.company.type',["companyTypeID"=>\Illuminate\Support\Facades\Crypt::encryptString($companyType->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                    <form action="{{route('delete.company.type')}}" class="display-inline" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($companyType->id) !!}">
                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the Company Type?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" class="text-center text-danger">Not found!</td>
        </tr>
    @endif

    </tbody>
</table>
