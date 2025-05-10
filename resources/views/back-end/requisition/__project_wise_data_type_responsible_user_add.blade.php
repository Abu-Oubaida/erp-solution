<div class="modal-header">
    <h5 class="modal-title text-capitalize" id="dataTypesDetailsLabel2"><i class="fa-solid fa-users"></i> <b>{!! $data_type->archiveDataType->voucher_type_title !!}</b> Data Type Responsible User List</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <script>
        $("select.select-search").each(function () {
            const isMultiple = $(this).prop("multiple");

            const selectizeInstance = $(this).selectize({
                plugins: isMultiple ? ['remove_button'] : [],
                create: false,
                closeAfterSelect: false,
                sortField: 'text',
                render: {
                    option: function(item, escape) {
                        if (item.value === '__select_all__') {
                            return '<div class="selectize-dropdown-header"><strong>Select all matching: "' + escape(item.text) + '"</strong></div>';
                        }
                        return '<div>' + escape(item.text) + '</div>';
                    }
                },
                onType: function(search) {
                    const selectize = this;

                    // Skip if not multiple
                    if (!isMultiple) return;

                    selectize.lastQueryTyped = search;
                    selectize.removeOption('__select_all__');

                    const results = selectize.search(search).items;

                    if (search.length && results.length > 1) {
                        selectize.addOption({
                            value: '__select_all__',
                            text: search
                        });
                        selectize.refreshOptions(false);
                    }
                },
                onItemAdd: function(value) {
                    const selectize = this;

                    if (value === '__select_all__') {
                        // Skip if not multiple
                        if (!isMultiple) return;

                        selectize.removeItem('__select_all__');
                        selectize.removeOption('__select_all__');

                        const matches = selectize.search(selectize.lastQueryTyped).items;
                        matches.forEach(item => {
                            if (!selectize.items.includes(item.id)) {
                                selectize.addItem(item.id);
                            }
                        });

                        setTimeout(() => {
                            selectize.$control_input.val(selectize.lastQueryTyped).trigger('keyup');
                            selectize.focus();
                        }, 0);
                    } else {
                        setTimeout(() => {
                            selectize.$control_input.val(selectize.lastQueryTyped).trigger('keyup');
                            selectize.focus();
                        }, 0);
                    }
                }
            });
        });

    </script>
    @if(auth()->user()->hasPermission('project_document_requisition_edit'))
    <div class="row">
        <div class="col-md-5 mb-1">
            <div class="row">
                <div class="col-md-9">
                    <label for="res_dept">Responsible Departments<span class="text-danger">*</span></label>
                    <select id="res_dept" name="res_dept[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                        <option value="">Pick options...</option>
                        <option value="0">@All</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->dept_name }} ({{ $department->dept_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <button class="btn btn-outline-secondary btn-sm mt-4 float-end" onclick="return Obj.searchCompanyDepartmentUsers('company','res_dept','res_users')"><i class="fas fa-search"></i> Search User</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 mb-1">
            <label for="res_users">Responsible Users<span class="text-danger">*</span></label>
            <select id="res_users" name="res_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                <option value="">Pick options...</option>
            </select>
        </div>
        <div class="col-md-12">
            <button class="btn btn-sm btn-chl-outline float-end" onclick="return DataTypeWiseResponsibleUserSubmit(this,{!! $pwdtr_id??0 !!},{!! $company_id??0 !!})"> <i class="fas fa-user-plus"></i> Add New</button>
        </div>
    </div>
    @endif
    <div class="row">
        <h5 class="text-capitalize"><i class="fas fa-list"></i> Responsible user list for <b>{!! $data_type->archiveDataType->voucher_type_title !!}</b> Data Type</h5>
        <hr>
        <div id="responsible_user_table" class="table-responsive">
            @include("back-end.requisition.___responsible_user_table")
        </div>
    </div>
</div>
