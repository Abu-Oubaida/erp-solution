<table class="table table-sm" id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Display Name</th>
        <th>Company</th>
        <th>Created By</th>
        <th>Created At</th>
        <th>Updated By</th>
        <th>Updated At</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($roles) && count($roles))
        @php
            $no= 1;
        @endphp
        @foreach($roles as $r)
            <tr class=" @if(isset($role) && $r->id == $role->id) text-active @else '' @endif ">
                <td>{!! $no++ !!}</td>
                <td>{!! $r->name !!}</td>
                <td>{!! $r->display_name !!}</td>
                <td>{!! $r->company->company_name !!}</td>
                <td>@isset($r->created_by){!! $r->createdBy->name !!}@endisset</td>
                <td>@isset($r->created_at){!! date('d-M-Y',strtotime($r->created_at)) !!}@endisset</td>
                <td>@isset($r->updated_by){!! $r->updatedBy->name !!}@endisset</td>
                <td>@isset($r->updated_by){!! date('d-M-Y',strtotime(@$r->updated_at)) !!}@endisset</td>
                <td>{!! $r->description !!}</td>
                <td>
                    @if(auth()->user()->hasPermission('edit_role'))
                        <a href="{!! route('edit.role',['roleID'=>\Illuminate\Support\Facades\Crypt::encryptString($r->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                    @endif
                    @if(auth()->user()->hasPermission('delete_role'))
                        <form action="{{route('delete.role')}}" class="display-inline" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{!! $r->id !!}">
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

