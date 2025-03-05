@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
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
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h3 class="text-capitalize"><i class="fas fa-file-circle-plus"></i> {{str_replace('info','document',str_replace('.', ' ', \Route::currentRouteName()))}}</h3>
                    </div>
                    <div class="col">
                        <div class="float-end">
                            @if(auth()->user()->hasPermission('list_voucher_document'))
                                <a class="btn btn-success btn-sm" href="{{route("uploaded.voucher.list")}}"><i class="fas fa-list-check"></i> Uploaded List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('add.voucher.info') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-2 mb-1">
                            <div class="">
                                <label for="company">Company Name <span class="text-danger">*</span></label>
                                <select class="text-capitalize select-search" id="company" name="company">
                                    @if(isset($companies) || (count($companies) > 0))
                                        @foreach($companies as $c)
                                            <option value="{{$c->id}}" @if(old('company') == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="form-floating">
                                <input class="form-control" id="voucher_number" name="voucher_number" type="text" placeholder="Enter Voucher Number" value="{{old('voucher_number')}}"/>
                                <label for="voucher_number">Reference Number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="form-floating">
                                <input class="form-control" id="voucher_date" name="voucher_date" type="date" placeholder="Enter Voucher Date" value="{{old('voucher_date')}}"/>
                                <label for="voucher_date">Date <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="form-floating">
                                <select class="form-control" name="voucher_type" id="voucher_type">
                                    <option value="">--Select a Type--</option>
                                    @foreach($voucherTypes as $date)
                                        <option value="{!! $date->id !!}">{!! $date->voucher_type_title !!}</option>
                                    @endforeach
                                </select>
                                <label for="voucher_type">Type<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="form-floating">
                                <input type="file" class="form-control" name="voucher_file[]" multiple id="voucher_file" >
                                <label for="voucher_file">Voucher Document</label>
                                <small>jpeg,png,pdf/ Maximum size 500 MB</small>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="form-floating">
                                <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10"></textarea>
                                <label for="remarks">Remarks</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <small>Link previously uploaded file here</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-1">
                            <div class="input-group">
                                <input type="text" id="input" class="form-control" placeholder="Reference number">
                                <a class="btn btn-outline-secondary" id="search-icon" onclick="return Obj.searchPreviousDocumentReference(this,'previous-reference')"><i class="fas fa-search"></i> Search</a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-1">
                            <div class="">
                                <select class="text-capitalize select-search" id="previous-reference" name="previous-reference" onchange="return Obj.searchPreviousDocuments(this,'previous-file')" multiple>
                                    <option>--choose a option--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-1">
                            <div class="">
                                <select class="text-capitalize select-search" id="previous-file" name="previous_files[]" multiple onchange="return Obj.selectAllOption(this)">
                                    <option>--choose a file--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">
                            <div class="float-end">
                                <button type="submit" value="" class="btn btn-chl-outline" name="submit" ><i class="fas fa-save"></i> Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <h3 class="text-capitalize"><i class="fas fa-file-pdf"></i> Your Added Documents Latest 10 Records</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('back-end.account-voucher._voucher_list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

