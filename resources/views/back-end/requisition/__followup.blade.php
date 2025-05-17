<div class="modal-header">
    <h5 class="modal-title text-capitalize" id="dataTypesDetailsLabel2"><i class="fa-solid fa-business-time"></i> <b>{!! $data->project->branch_name !!}</b> Data Type Wise Followup</h5>
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
        <div class="col-md-5 mb-1">
            <label for="followup_data_types">Data Types<span class="text-danger">*</span> <sup class="text-info">Read Only</sup></label>
            <select id="followup_data_types" name="data_types[]" class="select-search cursor-pointer" multiple readonly="readonly" disabled="disabled">
                <option value="">Pick options...</option>
                <option value="0">@All</option>
                @foreach($data->dataTypeRequired as $dataType)
                    <option value="{{ $dataType->id }}" @if(in_array($dataType->id,$pwdtr_ids)) selected @endif>{{ $dataType->archiveDataType->voucher_type_title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-7 mb-1">
            <label for="followup_res_users">Responsible Users<span class="text-danger">*</span></label>
            <select id="followup_res_users" name="res_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                <option value="">Pick options...</option>
                <option value="all">@All</option>
                @foreach($responsibleUsers as $responsibleUser)
                    <option value="{{ $responsibleUser->user->id }}" selected>{{ $responsibleUser->user->name }} ({{$responsibleUser->user->department->dept_name}})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <label for="subject">Subject <sup class="text-danger">*</sup></label>
            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" value="Document Upload Reminder Based on Selected Data Type for {{ $data->project->branch_name }}" required>
        </div>
        <div class="col-md-12">
            <label for="message">Message <sup class="text-danger">*</sup></label>
            <textarea class="form-control" id="message" name="message" placeholder="Message" required cols="30" rows="10">This is a gentle reminder regarding the document uploading for the <b>{{ $data->project->branch_name }}</b> project. Kindly ensure all required documents are uploaded based on the selected data type(s) to proceed smoothly.
                @foreach($data->dataTypeRequired as $dataType)
                    <li>{{ucfirst($dataType->archiveDataType->voucher_type_title)}} ( Deadline: {{ date('d-M-Y h:i:s A',strtotime($dataType->deadline)) }} )</li>
                @endforeach
            </textarea>
        </div>
        <div class="col-md-12" >
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="notification" value="notification" checked>
                <label class="form-check-label" for="notification">Send by Notification</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="email" value="email" checked>
                <label class="form-check-label" for="email">Send by Email</label>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-sm btn-chl-outline float-end" onclick="return ProjectWiseNewDataTypeFollowupSubmit(this,{!! $pdri_id??0 !!},{!! $project_id??0 !!},{!! $company_id??0 !!})"> <i class="fa-solid fa-paper-plane"></i> Send</button>
        </div>
    </div>
</div>
