<div class="modal-header">
    <h5 class="modal-title" id="dataTypesDetailsLabel2"><i class="fas fa-plus-circle"></i> Add new data type (Project Wise)</h5>
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
    <div class="row">
        <div class="col-md-4 mb-1">
            <label for="data_types">Data Types<span class="text-danger">*</span></label>
            <select id="data_types" name="data_types[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                <option value="">Pick options...</option>
                <option value="0">@All</option>
                @foreach($dataTypes as $data_type)
                    <option value="{{ $data_type->id }}">{{ $data_type->voucher_type_title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-8 mb-1">
            <div class="row">
                <div class="col-md-10">
                    <label for="res_dept">Responsible Departments<span class="text-danger">*</span></label>
                    <select id="res_dept" name="res_dept[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                        <option value="">Pick options...</option>
                        <option value="0">@All</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->dept_name }} ({{ $department->dept_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="row">
                        <button class="btn btn-outline-secondary btn-sm mt-4 float-end" onclick="return Obj.searchCompanyDepartmentUsers('company','res_dept','res_users')"><i class="fas fa-search"></i> Search User</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 mb-1">
            <label for="res_users">Responsible Users<span class="text-danger">*</span></label>
            <select id="res_users" name="res_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                <option value="">Pick options...</option>
            </select>
        </div>

        <div class="col-md-3">
            <div class="mb-2">
                <label for="deadline">Deadline<span class="text-danger">*</span></label>
                <input class="form-control" name="deadline" id="deadline" type="date" value="{!! old("deadline") !!}" required>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-sm btn-chl-outline float-end" onclick="return ProjectWiseNewDataTypeUpdate(this,{!! $pdri_id??0 !!},{!! $project_id??0 !!},{!! $company_id??0 !!})"> <i class="fas fa-save"></i> Save</button>
        </div>
    </div>
</div>
