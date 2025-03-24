<div class="modal-header">
    <h1 class="modal-title fs-5" id="heading"><strong>Data Type Name: </strong>{!! @$usersWithPermission->voucher_type_title !!}</h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-sm" id="datatablesSimple">
        <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Employee ID</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Company</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($usersWithPermission))
            @php($n = 1)
            @foreach($usersWithPermission->voucherWithUsers as $d)
                <tr>
                    <td>{!! $n++ !!}</td>
                    <td>{!! @$d->name !!}</td>
                    <td>{!! @$d->employee_id !!}</td>
                    <td>{!! @$d->designation->title !!}</td>
                    <td>{!! @$d->department->dept_name !!}</td>
                    <td>{!! @$d->getCompany->company_name !!}</td>
                    {{-- <td>{{$voucherId}}</td> --}}
                    <td>
                        @if(auth()->user()->hasPermission('delete_archive_data_type_user_permission'))
                                <button type="button" class="text-danger border-0 inline-block bg-none" onclick="Obj.deleteTypePermissionFromUser(this)" data-user-id = "{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}" data-data-type-id="{!! \Illuminate\Support\Facades\Crypt::encryptString($usersWithPermission->id) !!}"  data-voucher-id="{!! \Illuminate\Support\Facades\Crypt::encryptString($voucherId) !!}"><i class="fas fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
</div>
