@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <h1 class="mt- text-capitalize">{{str_replace('-', ' ', config('app.name'))}}
            | {{str_replace('.', ' ', \Route::currentRouteName())}} Page</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
                <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('company.setup')}}" class="text-capitalize text-chl text-decoration-none">Company
                    Setup</a>
            </li>
            <li class="breadcrumb-item">
                <a style="text-decoration: none;" href="#"
                   class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
            </li>
        </ol>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5># Edit Company Type</h5>
                            </div>
                            <div class="col-md-4">
                                <a href="{!! route('company.setup') !!}" class="btn btn-sm btn-outline-secondary float-end"><i class="fas fa-solid fa-plus"></i> Add New Company</a>
                            </div>
                        </div>
                        <hr>
                        <form action="{!! route('edit.company.type',["companyTypeID"=>\Illuminate\Support\Facades\Crypt::encryptString($editID->id)]) !!}"
                              method="POST">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="company-type-title"
                                               name="company_type_title" required="required" placeholder="Company Name"
                                               value="{!! $editID->company_type_title !!}">
                                        <label for="company-type-title">Company Type Title<span
                                                    class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select name="company_type_status" id="company_type_status"
                                                class="form-control">
                                            <option value="1" @if($editID->status == 1) selected @endif>Active</option>
                                            <option value="0" @if($editID->status == 0) selected @endif>Inactive
                                            </option>
                                        </select>
                                        <label for="company_type_status">Company Type Status<span
                                                    class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="description"
                                                  name="description"> {!! $editID->remarks !!}</textarea>
                                        <label for="remarks">Description</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <input type="submit" value="Update" class="btn btn-chl-outline" name="submit">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5># Company Type list</h5>
                            </div>
                            <div class="col-md-4">
                                <a href="{!! route('company.setup') !!}" class="btn btn-sm btn-outline-secondary float-end"><i class="fas fa-solid fa-plus"></i> Add New Company</a>
                            </div>
                        </div>
                        <hr>
                        @include("back-end.programmer._company-type-list")
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

