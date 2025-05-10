<script>
$("#select_all_2").change(function () {
    $(".check-box-2").prop("checked", this.checked);
});
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#data_type_responsible_user_list_table')) {
        $('#data_type_responsible_user_list_table').DataTable({
            dom: 'lfrtip',
            lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "ALL"]],
            pageLength: 15,
        })
    }
})
</script>
@if(auth()->user()->hasPermission('project_document_requisition_delete'))
    <label>Selected Oprations</label>
    <button class="btn btn-sm btn-outline-danger mb-2" onclick="return DataTypeWiseResponsibleUserDelete(this)"><i class="fas fa-trash"></i> Delete</button>
@endif
<table class="table table-hover table-sm" id="data_type_responsible_user_list_table" style="width: 100%;">
    <thead>
        <tr>
            <th><input type="checkbox" name="" id="select_all_2"></th>
            <th>SL</th>
            <th>Department</th>
            <th>User</th>
            <th>Employee ID</th>
        </tr>
    </thead>
    <tbody id="data_type_responsible_user_list">
    @php
        $sl = 1;
    @endphp
    @foreach($existing_users as $user)
        <tr>
            <td><input class="check-box-2" type="checkbox" name="selected[]" id="select_{!! $user->id !!}" value="{!! $user->id !!}"></td>
            <td>{!! $sl++ !!}</td>
            <td>{!! $user->user->department->dept_name !!}</td>
            <td>{!! $user->user->name !!}</td>
            <td>{!! $user->user->employee_id !!}</td>
        </tr>
    @endforeach

    </tbody>
</table>
