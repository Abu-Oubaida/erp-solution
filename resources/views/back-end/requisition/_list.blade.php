<table class="table table-sm" id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Code</th>
        <th>Status</th>
        <th>Company</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($deplist) && count($deplist))
        @php
            $no= 1;
        @endphp
        @foreach($deplist as $d)
            <tr class=" @if(isset($department) && $d->id == $department->id) text-active @else '' @endif ">
                <td>{!! $no++ !!}</td>
                <td>{!! $d->dept_name !!}</td>
                <td>{!! $d->dept_code !!}</td>
                <td>@if($d->status==1) <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif</td>
                <td>{!! isset($d->company->company_name)?$d->company->company_name:'-' !!}</td>
                <td>
                    @if(auth()->user()->hasPermission('edit_department'))
                        <a href="{!! route('edit.department',['departmentID'=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                    @endif
                    @if(auth()->user()->hasPermission('delete_department'))
                        <form action="{{route('delete.department')}}" class="display-inline" method="post">
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
            <td colspan="6" class="text-danger text-center">Not Found!</td>
        </tr>
    @endif
    </tbody>
</table>

