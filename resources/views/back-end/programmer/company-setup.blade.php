@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <h1 class="mt- text-capitalize">{{str_replace('-', ' ', config('app.name'))}} | {{str_replace('.', ' ', \Route::currentRouteName())}} Page</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
        </li>
    </ol>
    <div class="row">
        <div class="col-md-12" id="company_section">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h5># Add New Company</h5>
                        </div>
                        <div class="col-md-4">
                            <a href="#company_type_setion" class="btn btn-sm btn-outline-secondary float-end"><i class="fas fa-solid fa-plus"></i> Add Company Type</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{!! route('add.company') !!}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="company-name" name="company_name" required="required" placeholder="Company Name" value="{!! old('company_name') !!}">
                                    <label for="company-name">Company Name<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating ">
                                    <input type="text" class="form-control" id="company-short-name" name="company_short_name" required="required" placeholder="Company Short Name" value="{!! old("company_short_name") !!}">
                                    <label for="company-short-name">Company Short Name<span class="text-danger">*</span></label>
                                </div>
                                <small><b>Note:</b> Characters are allowed only (A to Z, 0-9)</small>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <select class="form-control" id="company_type_id" name="company_type_id" required="required">
                                        <option value="">--Select--</option>
                                        @if(count($companyTypes))
                                            @foreach($companyTypes as $c_type)
                                                <option value="{!! $c_type->id !!}" @if(old("company_type_id") == $c_type->id) selected @endif>{!! $c_type->company_type_title !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="company_type_id">Company Type<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="contract_person" name="contract_person" required="required" placeholder="Contract person name" value="{!! old('contract_person') !!}">
                                    <label for="contract_person">Contract Person Name<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email"  placeholder="email" required value="{!! old('email') !!}">
                                    <label for="email">Email<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="contract_person_phone" name="contract_person_phone" required="required" placeholder="Contract person mobile" value="{!! old('contract_person_phone') !!}">
                                    <label for="contract_person_phone">Contract Person Mobile<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="company_phone" name="company_phone"  placeholder="Company mobile" required value="{!! old('company_phone') !!}">
                                    <label for="company_phone">Company Mobile<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="file" class="form-control" id="logo" name="logo"  placeholder="logo">
                                    <label for="logo">Logo</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="file" class="form-control" id="logo_sm" name="logo_sm"  placeholder="logo_sm">
                                    <label for="logo_sm">Logo Small</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="file" class="form-control" id="logo_icon" name="logo_icon"  placeholder="logo_icon">
                                    <label for="logo_icon">Logo Icon</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="file" class="form-control" id="cover" name="cover"  placeholder="cover">
                                    <label for="cover">Cover Image</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="location" name="location"  placeholder="Company Location" value="{!! old('location') !!}">
                                    <label for="location">Company Location</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="remarks" name="remarks"  placeholder="Remarks">{!! old('remarks') !!}</textarea>
                                    <label for="remarks">Remarks</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3 float-end">
                                    <input type="submit" value="Save" class="btn btn-chl-outline" name="submit" >
                                </div>
                            </div>
                        </div>
                    </form>
                    <h5># Companies List</h5>
                    <hr>
                    @include("back-end.programmer._company-list")
                </div>
            </div>
        </div>
        <div class="col-md-12" id="company_type_setion">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h5># Add New Company Type</h5>
                        </div>
                        <div class="col-md-4">
                            <a href="#company_section" class="btn btn-sm btn-outline-secondary float-end"><i class="fas fa-solid fa-plus"></i> Add New Company</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{!! route('add.company.type') !!}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="company-type-title" name="company_type_title" required="required" placeholder="Company Name" value="{!! old('company_type_title') !!}">
                                    <label for="company-type-title">Company Type Title<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select name="company_type_status" id="company_type_status" class="form-control">
                                        <option value="1" @if(old('company_type_status') == 1) selected @endif>Active</option>
                                        <option value="0" @if(old('company_type_status') == 0) selected @endif>Inactive</option>
                                    </select>
                                    <label for="company_type_status">Company Type Status<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="description" name="description"> {!! old('description') !!}</textarea>
                                    <label for="remarks">Description</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating mb-3 float-end">
                                    <input type="submit" value="Save" class="btn btn-chl-outline" name="submit" >
                                </div>
                            </div>
                        </div>
                    </form>
                    <h5># Company Type list</h5>
                    <hr>
                    @include("back-end.programmer._company-type-list")
                </div>
            </div>
        </div>
    </div>

</div>
@stop

