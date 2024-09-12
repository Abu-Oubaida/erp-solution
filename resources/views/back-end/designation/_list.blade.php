<table class="table table-sm" id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Company Name</th>
        <th>Name</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Created by</th>
        <th>Created at</th>
        <th>Updated by</th>
        <th>Updated at</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($designations) && count($designations))
        @php
            $no= 1;
        @endphp
        @foreach($designations as $d)
            <tr class=" @if(isset($designation) && $d->id == $designation->id) text-active @else '' @endif ">
                <td>{!! $no++ !!}</td>
                <td>{!! $d->company->company_name !!}</td>
                <td>{!! $d->title !!}</td>
                <td>{!! $d->priority !!}</td>
                <td>@if($d->status==1) <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif</td>
                <td>@isset($d->createdBy->name) {{$d->createdBy->name}} @endisset</td>
                <td>@isset($d->created_at) {{date('d-M-Y',strtotime($d->created_at))}} @endisset</td>
                <td>@isset($d->updatedBy->name) {{$d->updatedBy->name}} @endisset</td>
                <td>@isset($d->updated_at) {{date('d-M-Y',strtotime($d->updated_at))}} @endisset</td>
                <td>
                    @if(auth()->user()->hasPermission('edit_designation'))
                        <a href="{!! route('edit.designation',['designationID'=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                    @endif
                    @if(auth()->user()->hasPermission('delete_designation'))
                        <form action="{{route('delete.designation')}}" class="display-inline" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{!! $d->id !!}">
                            <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="10" class="text-danger text-center">Not Found!</td>
        </tr>
    @endif
    </tbody>
</table>
