@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
{{--        <h1 class="mt- text-capitalize">{{str_replace('-', ' ', config('app.name'))}} | {{str_replace('.', ' ', \Route::currentRouteName())}} Page</h1>--}}
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5><i class="fas fa-edit"></i> Edit Company</h5>
                            </div>
                            <div class="col-md-4">
                                <a href="{!! route('add.company') !!}" class="btn btn-sm btn-outline-info float-end"><i class="fas fa-solid fa-plus"></i> Add Company</a>
                            </div>
                        </div>
                        <hr>
                        <form action="{!! route('edit.company',['companyID'=>\Illuminate\Support\Facades\Crypt::encryptString($edit_company->id)]) !!}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="company-name" name="company_name" required="required" placeholder="Company Name" value="{!! $edit_company->company_name !!}">
                                        <label for="company-name">Company Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating ">
                                        <input type="text" class="form-control" id="company-short-name" name="company_short_name" required="required" placeholder="Company Short Name" value="{!! $edit_company->company_code !!}">
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
                                                    <option value="{!! $c_type->id !!}" @if($edit_company->company_type_id == $c_type->id) selected @endif>{!! $c_type->company_type_title !!}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="company_type_id">Company Type<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="contract_person" name="contract_person" required="required" placeholder="Contract person name" value="{!! $edit_company->contract_person_name !!}">
                                        <label for="contract_person">Contract Person Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email"  placeholder="email" required value="{!! $edit_company->email !!}">
                                        <label for="email">Email<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="contract_person_phone" name="contract_person_phone" required="required" placeholder="Contract person mobile" value="{!! $edit_company->contract_person_phone !!}">
                                        <label for="contract_person_phone">Contract Person Mobile<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="company_phone" name="company_phone"  placeholder="Company mobile" required value="{!! $edit_company->phone !!}">
                                        <label for="company_phone">Company Mobile<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="file" class="form-control" id="logo" name="logo"  placeholder="logo">
                                        <label for="logo">Logo</label>
                                    </div>
                                    <img src="{!! isset($edit_company->logo)?url($edit_company->logo):'' !!}" alt="{!! $edit_company->logo !!}" class="img-thumbnail" width="50%">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="file" class="form-control" id="logo_sm" name="logo_sm"  placeholder="logo_sm">
                                        <label for="logo_sm">Logo Small</label>
                                    </div>
                                    <img src="{!! isset($edit_company->logo_sm)?url($edit_company->logo_sm):'' !!}" alt="{!! $edit_company->logo_sm !!}" class="img-thumbnail" width="50%">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="file" class="form-control" id="logo_icon" name="logo_icon"  placeholder="logo_icon">
                                        <label for="logo_icon">Logo Icon</label>
                                    </div>
                                    <img src="{!! isset($edit_company->logo_icon)?url($edit_company->logo_icon):'' !!}" alt="{!! $edit_company->logo_icon !!}" class="img-thumbnail" width="50%">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="file" class="form-control" id="cover" name="cover"  placeholder="cover">
                                        <label for="cover">Cover Image</label>
                                    </div>
                                    <img src="{!! isset($edit_company->cover)?url($edit_company->cover):'' !!}" alt="{!! $edit_company->cover !!}" class="img-thumbnail" width="50%">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="location" name="location"  placeholder="Company Location" value="{!! $edit_company->location !!}">
                                        <label for="location">Company Location</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mt-3">
                                        <textarea class="form-control" id="remarks" name="remarks"  placeholder="Remarks">{!! $edit_company->remarks !!}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <input type="submit" value="Update" class="btn btn-chl-outline" name="submit" >
                                    </div>
                                </div>
                            </div>
                        </form>
                        <h5><i class="fas fa-list"></i> Companies List</h5>
                        <hr>
                        @include("back-end.programmer._company-list")
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

