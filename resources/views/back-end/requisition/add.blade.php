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
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize d-inline-block"> <i class="fa-solid fa-file-circle-plus"></i> {{str_replace('add','create',str_replace('.', ' ', \Route::currentRouteName()))}}</h3>
                                <a href="{!! route('project.document.requisition.report') !!}" class="btn btn-sm btn-outline-primary float-end mt-1"><i class="fas fa-file-lines"></i> View Report</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-2">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company" onchange="return Requsition.companyWiseRequiredData(this,'user')">
                                            <option value="">--select a option--</option>
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label for="projects">Projects<span class="text-danger">*</span></label>
                                    <select id="projects" name="projects[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label for="data_types">Data Types<span class="text-danger">*</span></label>
                                    <select id="data_types" name="data_types[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>
                                <div class="col-md-5 mb-1">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <label for="res_dept">Responsible Departments<span class="text-danger">*</span></label>
                                            <select id="res_dept" name="res_dept[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                                <option value="">Pick options...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                                <button class="btn btn-outline-secondary btn-sm mt-4 float-start" onclick="return Obj.searchCompanyDepartmentUsers('company','res_dept','res_users')"><i class="fas fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-1">
                                    <label for="res_users">Responsible Users<span class="text-danger">*</span></label>
                                    <select id="res_users" name="res_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-2">
                                        <label for="deadline">Deadline<span class="text-danger">*</span></label>
                                        <input class="form-control" name="deadline" id="deadline" type="date" value="{!! old("deadline") !!}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="subject">Subject (If any message to email)</label>
                                        <input class="form-control" name="subject" id="subject" type="text" value="{!! old('subject') !!}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="details" id="details" cols="30" rows="10">{{old('details')}}</textarea>
                                        <label for="details">Details (If any message to email)</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-chl-outline float-end" onclick="return ProjectDocumentRequisitionSubmit(this)"><i class="fas fa-save"></i> Submit</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function ProjectDocumentRequisitionSubmit(e)
        {
            let company = $("#company").val();
            let projects = $("#projects").val();
            let data_types = $("#data_types").val();
            let res_users = $("#res_users").val();
            let deadline = $("#deadline").val();
            let subject = $("#subject").val();
            let details = $("#details").val();
            if(company.length === 0 || projects.length === 0 || data_types.length === 0 || res_users.length === 0 || deadline.length === 0)
            {
                return false
            }
            const url = window.location.origin + sourceDir +"/requisition/project-document-requisition-entry";
            $.ajax({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                method: "POST",
                data: { company_id: company, projects: projects, data_types: data_types, users: res_users, deadline: deadline, subject: subject, details:details },
                success: function (response) {
                    alert(response.status + " " + response.message)
                },
                error: function (xhr, status, error) {
                    let message = "An error occurred. Please try again.";
                    // If server returns a specific error message
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        message = xhr.responseText;
                    }

                    alert("Error: " + message);
                }
            })
            // alert(url)
        }
    </script>
@stop

