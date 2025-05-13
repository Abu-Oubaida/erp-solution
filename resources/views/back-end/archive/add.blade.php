@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('data.archive.dashboard.interface')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">Data upload</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h3 class="text-capitalize"><i class="fas fa-file-circle-plus"></i> Upload documents</h3>
                    </div>
                    <div class="col">
                        <div class="float-end">
                            @if(auth()->user()->hasPermission('archive_data_list_quick'))
                                <a class="btn btn-outline-success btn-sm mt-1" href="{{route("uploaded.archive.list.quick")}}"><i class="fa-solid fa-bolt"></i> Quick Report</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('add.archive.info') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-2 mb-1">
                            <div class="">
                                <label for="company">Company Name <span class="text-danger">*</span></label>
                                <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseProjectsAndDataTypeArchive(this,'project','data_types',null)">
                                    <option value="">Pick options...</option>
                                    @if(isset($companies) || (count($companies) > 0))
                                        @foreach($companies as $c)
                                            <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <label for="project">Enter Project Name</label>
                            <select id="project" name="project" class="select-search cursor-pointer">
                                <option value="">Pick options...</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="form-floating">
                                <input class="form-control" id="reference_number" name="reference_number" type="text" placeholder="Enter Voucher Number" value="{{old('reference_number')}}"/>
                                <label for="reference_number">Reference Number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="form-floating">
                                <input class="form-control" id="voucher_date" name="voucher_date" type="date" placeholder="Enter Voucher Date" value="{{old('voucher_date')}}"/>
                                <label for="voucher_date">Date <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="">
                                <label for="data_type">Type<span class="text-danger">*</span></label>
                                <select class="form-control text-capitalize select-search" name="data_type" id="data_types">
                                    <option value="">--Select a Type--</option>
{{--                                    @foreach($voucherTypes as $date)--}}
{{--                                        <option value="{!! $date->id !!}">{!! $date->voucher_type_title !!}</option>--}}
{{--                                    @endforeach--}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="form-floating">
                                <input type="file" class="form-control" name="voucher_file[]" multiple id="voucher_file" >
                                <label for="voucher_file">Archive Document</label>
                                <small>jpeg,png,pdf/ Maximum size 500 MB</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-1">
                            <div class="form-floating">
                                <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10"></textarea>
                                <label for="remarks">Remarks</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <small>Link previously uploaded file here</small>
                                    <div class="input-group">
                                        <input type="text" id="input" class="form-control" placeholder="Reference number">
                                        <a class="btn btn-outline-secondary" id="search-icon" onclick="return Obj.searchPreviousDocumentReference(this,'company','previous-references')"><i class="fas fa-search"></i> Search</a>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-8 mb-1">
                                    <div class=" mt-1">
                                        <small>Choose options</small>
                                        <select class="text-capitalize select-search form-control" id="previous-references" name="previous-reference" onchange="return Obj.selectAllOption(this)" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-3 mb-1">
                                    <div class="row mt-1">
                                        <a class="btn btn-sm btn-outline-secondary mt-4" id="previous-reference-search" onclick="return Obj.searchPreviousDocuments('previous-references','previous-files')"><i class="fas fa-search"></i> Search</a>
                                    </div>

                                </div>
                                <div class="col-md-4 mb-1">
                                    <div class="">
                                        <small>Choose options</small>
                                        <select class="text-capitalize select-search" id="previous-files" name="previous_files[]" multiple onchange="return Obj.selectAllOption(this)">
                                            <option>--choose a file--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 mb-1">
                            <div class="float-end">
                                <button type="submit" value="" class="btn btn-chl-outline mt-4" name="submit" ><i class="fas fa-save"></i> Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
{{--        <div class="card mb-4">--}}
{{--            <div class="card-header">--}}
{{--                <div class="row">--}}
{{--                    <h3 class="text-capitalize"><i class="fas fa-file-pdf"></i> Your Added Documents Latest 10 Records</h3>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="card-body">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        @include('back-end.archive._archive_list')--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@stop

