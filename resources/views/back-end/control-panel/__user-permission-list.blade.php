<div class="card-header text-capitalize">
    <svg class="svg-inline--fa fa-table me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="table" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M64 256V160H224v96H64zm0 64H224v96H64V320zm224 96V320H448v96H288zM448 256H288V160H448v96zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"></path></svg><!-- <i class="fas fa-table me-1"></i> Font Awesome fontawesome.com -->
    user project permission list
</div>
<div class="card-body">
    <table id="datatablesSimple" class="table">
        <thead>
        <tr>
            <th>SL.</th>
{{--            <th>User Name</th>--}}
            <th>Project Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(count($userProjectPermissions))
            @php($n=1)
            @foreach($userProjectPermissions as $upp)
                <tr>
                    <td>{!! $n++ !!}</td>
{{--                    <td>{!! $upp->user->name !!}</td>--}}
                    <td>{!! $upp->projects->branch_name !!}</td>
                    <td>
                        @if(auth()->user()->hasPermission('user_project_permission_delete'))
{{--                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($upp->id) !!}">--}}
                                <button class="text-danger border-0 inline-block bg-none" ref="{!! $upp->id !!}" onclick="return Obj.userProjectPermissionDelete(this)"><i class="fas fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">Not Found!</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
