@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
{{--        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="mb-2">
                    <label for="company">Company Name <span class="text-danger">*</span></label>
                    <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseProjects(this,'projects',true)">
                        <option value="">--select a option--</option>
                        @if(isset($companies) || (count($companies) > 0))
                            @foreach($companies as $c)
                                <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-8 mb-1">
                <label for="projects">Projects<span class="text-danger">*</span></label>
                <select id="projects" name="projects[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                    <option value="">Pick options...</option>
                </select>
            </div>
            <div class="col-md-1">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-outline-secondary float-end mt-4 btn-sm" onclick="return ProjectWiseRequiredDataTypeReport(this)"><i class="fas fa-search"></i> Search</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize d-inline-block"> <i class="fa-solid fa-file-lines"></i> {{str_replace('add','create',str_replace('.', ' ', \Route::currentRouteName()))}}</h3>
                                <a href="{!! route('project.document.requisition.entry') !!}" class="btn btn-sm btn-outline-success float-end mt-1"><i class="fas fa-plus"></i> Create Requisition</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="report-info">
                        <h6 class="text-center text-danger">Nothing for show!</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal 1 for details -->
    <div class="modal fade modal-xl" id="dataTypesDetailsModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="dataTypesDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-custom">
            <div class="modal-content">
                <div id="dataTypesDetailsContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
                </div>
            </div>
        </div>
        <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
            <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
        </div>
    </div>
    <!-- Modal 2 for details -->
    <div class="modal fade modal-xl" id="dataTypesDetailsModal2" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="dataTypesDetailsModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="dataTypesDetailsModalContent2"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
                </div>
            </div>
        </div>
        <div id='ajax_loader3' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
            <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
        </div>
    </div>
    <script>
        function ProjectWiseRequiredDataTypeReport(e)
        {
            let company = $("#company").val()
            let projects = $("#projects").val()
            if(company.length === 0 || projects.length === 0)
            {
                return false
            }
            const url = window.location.origin + sourceDir +"/requisition/project-document-requisition-report";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    company: company,
                    projects: projects,
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        // alert("Success: "+response.message)
                        $("#report-info").html(response.data.view)
                    }
                },
            })
        }

        function ProjectWiseDataTypesReportDetails(e, id, project_id, company_id)
        {
            if(id.length === 0 || project_id.length === 0 || company_id.length === 0)
            {
                return false
            }
            const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-report-details";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    id: id,
                    project_id: project_id,
                    company_id: company_id,
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        // alert("Success: "+response.message)
                        $("#dataTypesDetailsContent").html(response.data.view)
                        // $("#dataTypesDetailsContent").html(id)
                        $("#dataTypesDetailsModal").modal('show')
                        return true
                    }
                },
            })
        }

        function DataTypeNecessityChange(e, inst,pdri_id,project_id,company_id)
        {
            let selected = [];
            let value = inst??0
            $(".check-box:checked").each(function () {
                selected.push($(this).val());
            });
            if (selected.length === 0) {
                alert("Please select at least one record to delete.");
                return false
            }
            if(!confirm("Are you sure?"))
                return false
            const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-necessity-change";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    ids: selected,
                    value: value,
                    pdri_id:pdri_id,
                    project_id:project_id,
                    company_id:company_id
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        alert("Success: "+response.message)
                        $("#dataTypesDetailsContent").html(response.data.view)
                        return true
                    }
                },
            })
        }

        function DeleteProjectWiseNecessaryDataType(e,pdri_id,project_id,company_id)
        {
            let selected = [];
            $(".check-box:checked").each(function () {
                selected.push($(this).val());
            });
            if (selected.length === 0) {
                alert("Please select at least one record to delete.");
                return false
            }
            if(!confirm("Are you sure?"))
                return false
            const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-delete";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    ids: selected,
                    pdri_id:pdri_id,
                    project_id:project_id,
                    company_id:company_id
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        alert("Success: "+response.message)
                        $("#dataTypesDetailsContent").html(response.data.view)
                        ProjectWiseRequiredDataTypeReport(e)
                        return true
                    }
                },
            })
        }
        function ProjectWiseNewDataTypeAdd(pdri_id,project_id,company_id)
        {
            const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-add";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    pdri_id:pdri_id,
                    project_id:project_id,
                    company_id:company_id
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        $("#dataTypesDetailsModalContent2").html(response.data.view)
                        $("#dataTypesDetailsModal2").modal('show')
                        return true
                    }
                },
            })
        }
        function ProjectWiseNewDataTypeUpdate(e,pdri_id,project_id,company_id)
        {
            let data_types = $("#data_types").val()
            let res_users = $("#res_users").val()
            let deadline = $("#deadline").val()
            if(data_types.length === 0 || res_users.length === 0 || deadline.length === 0)
            {
                return false
            }
            const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-update";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    data_types: data_types,
                    res_users: res_users,
                    deadline: deadline,
                    pdri_id:pdri_id,
                    project_id:project_id,
                    company_id:company_id
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        alert("Success: "+response.message)
                        $("#dataTypesDetailsModal2").modal('hide')
                        $("#dataTypesDetailsContent").html(response.data.view)
                        ProjectWiseRequiredDataTypeReport(e)
                        return true
                    }
                },
            })
        }
        function DataTypeWiseResponsibleUserAdd(e,pwdtr_id,pdri_id,project_id,company_id)
        {
            const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-responsible-user-add";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    pwdtr_id: pwdtr_id,
                    pdri_id: pdri_id,
                    project_id: project_id,
                    company_id: company_id,
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        $("#dataTypesDetailsModalContent2").html(response.data.view)
                        $("#dataTypesDetailsModal2").modal('show')
                        return true
                    }
                },
            })
        }

        function DataTypeWiseResponsibleUserSubmit(e,pwdtr_id,pdri_id,project_id,company_id)
        {
            let res_users = $("#res_users").val()
            const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-responsible-user-submit";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    users: res_users,
                    pwdtr_id: pwdtr_id,
                    pdri_id: pdri_id,
                    project_id: project_id,
                    company_id: company_id,
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        alert("Success: "+response.message)
                        $("#responsible_user_table").html(response.data.view)
                        $("#dataTypesDetailsContent").html(response.data.view2)
                        return true
                    }
                },
            })
        }

        function DataTypeWiseResponsibleUserDelete(e,pdri_id,project_id,company_id)
        {
            let selected = [];
            $(".check-box-2:checked").each(function () {
                selected.push($(this).val());
            });
            if (selected.length === 0) {
                alert("Please select at least one record to delete.");
                return false
            }
            if(!confirm("Are you sure?"))
                return false
            const url = window.location.origin + sourceDir +"/requisition/project-wise-data-type-responsible-user-delete";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: {
                    res_users: selected,
                    pdri_id: pdri_id,
                    project_id: project_id,
                    company_id: company_id,
                },
                success: function (response) {
                    if(response.status === 'error')
                    {
                        alert("Error: "+response.message)
                        return false
                    }
                    else {
                        alert("Success: "+response.message)
                        $("#responsible_user_table").html(response.data.view)
                        $("#dataTypesDetailsContent").html(response.data.view2)
                        return true
                    }
                },
            })
        }

    </script>
@stop

