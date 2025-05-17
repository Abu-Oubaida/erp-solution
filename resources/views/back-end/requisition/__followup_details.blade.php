<div class="modal-header">
    <h5 class="modal-title text-capitalize" id="dataTypesDetailsLabel2"><i class="fa-solid fa-business-time"></i> <b>{!! $followups->first()->requiredDataType->projectDocumentReq->project->branch_name !!}</b> Data Type ({!! $followups->first()->requiredDataType->archiveDataType->voucher_type_title !!}) Followup Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <style>
        /*div.dataTables_wrapper {*/
        /*    font-size: 10px;*/
        /*}*/
        #followup_details_table_div .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0!important;
        }
    </style>
    <script>
        if (!$.fn.DataTable.isDataTable('#followup_details_table')) {
            $('#followup_details_table').DataTable({
                dom: 'lfrtip', // 'l' includes the "length changing" input
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ALL"]],
                pageLength: 10,
                fixedHeader: {
                    header: true
                },
            });
        }
    </script>
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
    <div class="row" id="followup_details_table_div" style="font-size: 12px;">
        <div @if(@$followup_single)class="col-md-5" @else class="col-md-12" @endif>
            <table class="table table-hover table-sm" id="followup_details_table" style="width: 100%; font-size: 11px;">
                <thead>
                    <tr>
                        <th style="width:3%!important;">SL</th>
                        <th style="width: 20%!important;">Date</th>
                        <th style="width: 77%">Subject</th>
                    </tr>
                </thead>
                <tbody>
                @if(@$followups)
                    @php($n=1)
                    @foreach($followups as $followup)
                        <tr @if(isset($followup_single) && $followup_single->id == $followup->id) class="bg-secondary" @endif onclick="return FollowupsDetailsSingle({{$followup->data_type_id}},{{$followup->id}})" style="cursor:pointer;">
                            <td style="width:3%!important;">{{$n++}}</td>
                            <td style="width: 20%!important;">{{date('d-M-y h:i:s A', strtotime($followup->created_at))}}</td>
                            <td style="width: 77%"> {{ \Illuminate\Support\Str::limit($followup->followupMessage->subject, 50) }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        @if(@$followup_single)
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <label >Data Type<span class="text-danger">*</span> <sup class="text-info">Read Only</sup></label>
                        <select class="select-search cursor-pointer" multiple readonly="readonly" disabled="disabled">
                            @if(isset($followup_single) && $followup_single->requiredDataType->archiveDataType)
                                <option selected> {{$followup_single->requiredDataType->archiveDataType->voucher_type_title}}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-12 mb-1">
                        <label>Responsible Users<span class="text-danger">*</span> <sup class="text-info">Read Only</sup></label>
                        <select class="select-search cursor-pointer" multiple readonly="readonly" disabled="disabled">
                            @if(isset($followup_single) && count($followup_single->responsibleUsers))
                                @foreach($followup_single->responsibleUsers as $responsibleUser)
                                    <option selected>{{ $responsibleUser->name }} ({{$responsibleUser->department->dept_name}})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label >Subject <sup class="text-danger">*</sup> <sup class="text-info">Read Only</sup></label>
                        <input type="text" class="form-control"  placeholder="Subject" value="{{optional($followup_single->followupMessage)->subject}}" required readonly="readonly" disabled="disabled">
                    </div>
                    <div class="col-md-12">
                        <label for="message">Message <sup class="text-danger">*</sup> <sup class="text-info">Read Only</sup></label>
                        <div class="border-1 p-3" style="font-size: 12px; height: 200px; overflow-y: scroll; border-radius: 5px; border: 1px solid #000000;">
                            {!!  optional($followup_single->followupMessage)->description!!}
                        </div>
                    </div>
                    <div class="col-md-12" >
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" @if(optional($followup_single->followupMessage)->notification) checked @endif disabled="disabled">
                            <label class="form-check-label" >Send by Notification</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" @if(optional($followup_single->followupMessage)->email) checked @endif disabled="disabled">
                            <label class="form-check-label" >Send by Email</label>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
