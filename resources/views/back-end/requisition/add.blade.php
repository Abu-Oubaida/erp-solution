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
                                <h3 class="text-capitalize"> <i class="fa-solid fa-file-circle-plus"></i> {{str_replace('add','create',str_replace('.', ' ', \Route::currentRouteName()))}}</h3>
                            </div>
                            <div class="col">
                                <a class="btn btn-primary btn-sm float-end mt-1 mb-1" style="margin-left: 10px" href=""><i class="fa-solid fa-paper-plane"></i>  Sent List</a>
                                <a class="btn btn-success btn-sm float-end mt-1 mb-1 mr-1" href=""><i class="fa-solid fa-inbox"></i>  Received List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-2">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company" onchange="return Requsition.companyWiseUsersForReq(this,'user')">
                                            <option value="">--select a option--</option>
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="projects">Projects<span class="text-danger">*</span></label>
                                    <select id="projects" name="projects[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <label for="data_types">Data Types<span class="text-danger">*</span></label>
                                    <select id="data_types" name="data_types[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <label for="res_dept">Responsible Dept.<span class="text-danger">*</span></label>
                                            <select id="res_dept" name="res_dept[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                                <option value="">Pick options...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-outline-secondary btn-sm mt-4 float-end" onclick="return Obj.searchCompanyDepartmentUsers('company','res_dept','res_users')"><i class="fas fa-search"></i> Search</button>
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
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="subject">Subject (If any message to email)</label>
                                        <input class="form-control" name="subject" id="subject" type="text" value="{!! old('subject') !!}" required>
                                    </div>
                                </div>
{{--                                <div class="col-md-2">--}}
{{--                                    <div class="mb-3">--}}
{{--                                        <label for="d_count">Number of Need Document</label>--}}
{{--                                        <input class="form-control" value="{!! old('d_count') !!}" name="d_count" id="d_count" type="number">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-1">--}}
{{--                                    @if(old('d_count'))--}}
{{--                                        <button class="btn btn-outline-danger float-end mt-4" onclick="Obj.document_field_operation(this,'Phone')"><i class='fa-solid fa-clock-rotate-left'></i> Reset</button>--}}
{{--                                    @else--}}
{{--                                        <a class="btn btn-outline-primary float-end mt-4" onclick="Obj.document_field_operation(this,'Phone')">Next <i class="fa-solid fa-arrow-right"></i></a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
                            </div>
{{--                            <div class="row" id="document_field">--}}
{{--                                @if($d_count=old('d_count'))--}}
{{--                                    @php($counter = 1)--}}
{{--                                    @while($d_count >= $counter)--}}
{{--                                        <div class="col">--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="d_title_{!! $counter !!}">Document {!! $counter !!} Title</label>--}}
{{--                                                <input class="form-control" name="d_title_{!! $counter !!}" id="d_title_{!! $counter !!}" type="text" value="{!! old("d_title_".$counter) !!}">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        @php($counter++)--}}
{{--                                    @endwhile--}}

{{--                                @endif--}}
{{--                            </div>--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="details" id="details" cols="30" rows="10">{{old('details')}}</textarea>
                                        <label for="details">Details (If any message to email)</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-chl-outline float-end"><i class="fas fa-save"></i> Submit</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3> <i class="fas fa-list"></i> My Requisition List</h3>
                    </div>
                    @include("back-end.requisition._list")
                </div>
            </div>

        </div>

    </div>
@stop

