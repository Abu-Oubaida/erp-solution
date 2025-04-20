<table class="table table-hover table-sm" @if($storage_packages)id="dataTable"@endif>
    <thead>
    <tr>
        <th>SL</th>
        <th>Package Name</th>
        <th>Package Size</th>
        <th>Package Status</th>
        <th>Package Uses Count</th>
        <th>Created By</th>
        <th>Created At</th>
        <th>Updated By</th>
        <th>Updated At</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
@if($storage_packages)
    @php($sl = 1)
    @foreach($storage_packages as $package)
        <tr>
            <td>{!! $sl++ !!}</td>
            <td>{!! @$package->package_name !!}</td>
            <td>{!! @$package->package_size !!} GB</td>
            <td>@if($package->status) <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif</td>
            <td>{!! @$package->usees_company_count !!}</td>
            <td>{!! @$package->createdBy->name !!}</td>
            <td>{!! ($package->created_at)?date('d-M-y',strtotime(@$package->created_at)):'-' !!}</td>
            <td>{!! @$package->updatedBy->name !!}</td>
            <td>{!! ($package->updated_at)?date('d-M-y',strtotime(@$package->updated_at)):'-' !!}</td>
            <td>
                <button class="btn btn-sm text-primary" ref="{!! $package->id !!}" onclick="return AppSetting.editArchivePackage(this,'edit_archive_package_modal')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm text-danger" ref="{!! $package->id !!}" onclick="return AppSetting.deleteArchivePackage(this,'archive_package_list')"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="10" class="text-center">No Data Found</td>
    </tr>
@endif
    </tbody>
</table>
<script>
    (function ($) {
        $("#select_all").change(function () {
            $(".check-box").prop("checked", this.checked);
        });
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('.dataTable')) {
                $('#dataTable').DataTable({
                    dom: 'lfrtip',
                    lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "ALL"]],
                    pageLength: 15,
                })
            }
        })
    }(jQuery))
</script>
