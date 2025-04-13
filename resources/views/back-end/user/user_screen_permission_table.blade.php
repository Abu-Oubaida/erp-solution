<table @if (count($userPermissions)) id="simpleDataTable2" @endif class="table table-sm">
    <thead>
        <tr>
            <th rowspan="1" colspan="1">Select</th>
            <th>No</th>
            <th>Company</th>
            <th>Parent Permission Name</th>
            <th>Child Permission Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($userPermissions))
            @php
                $i = 1;
            @endphp
            @foreach ($userPermissions as $p)
                <tr>
                    <th><input class="check-box" type="checkbox" name="selected[]" id="select_{!! $p->id !!}"
                            value="{!! $p->id !!}"></th>
                    <td>{!! $i++ !!}</td>
                    <td>{!! $p->company->company_name !!}</td>
                    <td>
                        <span class="text-capitalize"> {!! @$p->permissionParent->display_name !!}</span>
                    </td>
                    <td>
                        <span class="text-capitalize">
                            @if ($p->is_parent == null)
                                {!! /*str_replace('_',' ',$p->permission_name)*/ $p->permission_name !!}
                            @endif
                        </span>
                    </td>

                    <td class="">
                        @if (auth()->user()->hasPermission('delete_user_screen_permission'))
                            <form action="{!! route('remove.user.permission') !!}" class="display-inline" method="post">
                                @method('delete')
                                @csrf
                                <input type="hidden" name="user_id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($userID) !!}">
                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($p->id) !!}">
                                <button class="text-danger border-0 inline-block bg-none"
                                    onclick="return confirm('Are you sure delete this permission?')"><i
                                        class="fas fa-trash"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center text-danger">Not found!</td>
            </tr>
        @endif
    </tbody>

</table>
<script>
    (function($) {
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#simpleDataTable2')) {
                $('#simpleDataTable2').DataTable({
                    dom: 'lfrtip',
                    lengthMenu: [
                        [25, 50, 100, -1],
                        [25, 50, 100, "ALL"]
                    ],
                    pageLength: 25,
                })
            }

        })
    }(jQuery))
</script>
