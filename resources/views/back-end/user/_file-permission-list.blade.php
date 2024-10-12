@php
    $no = 1;
@endphp
@if(count($filPermission))
    @foreach($filPermission as $fp)
        <tr>
            <td>{!! $no++ !!}</td>
            <td>{!! @$fp->company->company_name !!}</td>
            <td>{!! $fp->dir_name !!}</td>
            <td>@if($fp->permission_type == 1) Read Only @elseif($fp->permission_type == 2) Read/Write @elseif($fp->permission_type == 3) Read/Write/Delete @else Undefine @endif</td>
            <td><a style="cursor: pointer" title="Delete" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($fp->id) !!}" class="text-danger per-delete" id="per-delete" onclick="return Obj.companyDirectoryPermissionDelete(this)"><i class='fas fa-trash'></i></a></td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center text-danger">Not Found!</td>
    </tr>
@endif

