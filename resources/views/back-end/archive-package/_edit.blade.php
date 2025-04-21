<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><i
            class="fas fa-file-edit"></i> Edit Data Archive Package</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"
            aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-4 mb-2">
            <div class="form-group">
                <label for="edit_package_name">Package Name</label>
                <input type="text" class="form-control" id="edit_package_name"
                       placeholder="Enter Package Name" value="{!! @$data->package_name !!}">
            </div>
        </div>
        <input type="hidden" id="edit_package_id" value="{!! @$data->id !!}">
        <div class="col-md-4 mb-2">
            <div class="form-group">
                <label for="edit_package_size">Package Storage Size (GB)</label>
                <input type="text" class="form-control" id="edit_package_size"
                       placeholder="Enter Package Storage Size" value="{!! @$data->package_size !!}">
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="form-group">
                <label for="edit_package_status">Status</label>
                <select class="form-select" id="edit_package_status">
                    <option value="1" @if($data->status) selected @endif>Active</option>
                    <option value="0" @if(!$data->status ) selected @endif>Inactive</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-chl-outline float-end"
                    onclick="return AppSetting.updateArchivePackage(this,'archive_package_list')">
                <i class="fas fa-save"></i> Update
            </button>
        </div>
    </div>
</div>
