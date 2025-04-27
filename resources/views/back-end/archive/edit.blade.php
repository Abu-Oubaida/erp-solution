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
                        <a href="{{route('uploaded.archive.list')}}" class="text-capitalize text-chl">Archive Document List</a>
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
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="text-capitalize"><i class="fas fa-edit"></i> {{str_replace('info','document',str_replace('.', ' ', \Route::currentRouteName()))}} Info</h3>
                            </div>
                            <div class="col">
                                <div class="float-end mt-1">
                                    @if(auth()->user()->hasPermission('archive_data_list'))
                                        <a class="btn btn-success btn-sm" href="{{route("uploaded.archive.list")}}"><i class="fas fa-list-check"></i> Uploaded List</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edit.archive.info',["archiveDocumentID"=>\Illuminate\Support\Facades\Crypt::encryptString($voucherInfo->id)]) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseProjects(this,'project')">
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}" @if($voucherInfo->company_id == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="project">Enter Project Name</label>
                                    <select id="project" name="project" class="select-search cursor-pointer">
                                        <option value="">Pick options...</option>
                                        @if ($projects = $voucherInfo->company->projects)
                                            @foreach ( $projects as $project)
                                            <option value="{{$project->id}}" @if($voucherInfo->project_id == $project->id) selected @endif>{!! $project->branch_name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-2">
                                        <input class="form-control" id="reference_number" name="reference_number" type="text" placeholder="Enter Voucher Number" value="{{$voucherInfo->voucher_number}}"/>
                                        <label for="reference_number">Reference Number <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-2">
                                        <input class="form-control" id="voucher_date" name="voucher_date" type="date" placeholder="Enter Voucher Date" value="{{$voucherInfo->voucher_date}}"/>
                                        <label for="voucher_date">Date <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-2">
                                        <select class="form-control" name="data_type" id="data_type">
                                            <option value="">--Select a Type--</option>
                                            @foreach($voucherTypes as $type)
                                                <option value="{!! $type->id !!}" @if ($voucherInfo->voucher_type_id == $type->id) selected @endif>{!! $type->voucher_type_title !!}</option>
                                            @endforeach
                                        </select>
                                        <label for="data_type">Type<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10">{{$voucherInfo->remarks}}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mt-3 float-start">
                                        <button type="submit" value="" class="btn btn-chl-outline" name="submit" ><i class="fas fa-save"></i> Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="text-capitalize"><i class="fas fa-link"></i> Link previously uploaded file here</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{!! route('linked.uploaded.document') !!}" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label for="input">Reference No. or Remarks</label>
                                    <div class="input-group">
                                        <input type="hidden" value="{!! @$voucherInfo->company_id  !!}" id="company_id_link" name="company_id_link">
                                        <input type="hidden" value="{!! @$voucherInfo->id  !!}" id="update_document_info_id" name="update_archive_info_id">
                                        <input type="text" id="input" class="form-control" placeholder="Reference number or remarks">
                                        <a class="btn btn-outline-secondary" id="search-icon" onclick="return Obj.searchPreviousDocumentReference(this,'company_id_link','previous-reference')"><i class="fas fa-search"></i> Search</a>
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label for="input">Select Reference No. or Remarks</label>
                                    <select class="text-capitalize select-search form-control" id="previous-reference" name="previous-reference" onchange="return Obj.selectAllOption(this)" multiple>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a class="btn btn-sm btn-outline-secondary mt-4" id="previous-reference-search" onclick="return Obj.searchPreviousDocuments('previous-reference','previous-file')"><i class="fas fa-search"></i> Search</a>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="">
                                        <label for="input">Select files here</label>
                                        <select class="text-capitalize select-search" id="previous-file" name="previous_files[]" multiple onchange="return Obj.selectAllOption(this)">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-1">
                                    <div class="float-end">
                                        <button type="submit" value="submit" class="btn btn-chl-outline" name="submit" ><i class="fas fa-save"></i> Link Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="text-capitalize"><i class="fas fa-edit"></i> {{str_replace('info','document',str_replace('.', ' ', \Route::currentRouteName()))}} Document</h3>
                            </div>
                            <div class="col">
                                @if(auth()->user()->hasPermission('add_archive_document_individual'))
                                    <a href="" class="text-end float-end badge bg-success text-decoration-none" onclick="return Obj.addArchiveDocumentIndividual(this)" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($voucherInfo->id) !!}"><i class="fas fa-plus"></i> Add New</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Voucher Number</th>
                                <th>Document</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($voucherInfo))
                                @php $x = 1;@endphp
                                @foreach($voucherInfo->voucherDocuments as $d)
                                <tr>
                                    <td>{!! $x++ !!}</td>
                                    <td>{!! date('d-M-y', strtotime($d->created_at)) !!}</td>
                                    <td>{!! $voucherInfo->voucher_number !!}</td>
                                    <td>{!! $d->document !!}</td>
                                    <td>{!! $d->createdBy->name !!}</td>
                                    <td>
                                        <a href="" title="Quick View" vtype="{!! $voucherInfo->VoucherType->voucher_type_title !!}" vno="{!! $voucherInfo->voucher_number !!}" path="{!! \Illuminate\Support\Facades\Crypt::encryptString(asset('storage/archive_data/'.$d->filepath.$d->document)) !!}" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}" onclick="return Obj.findDocument(this,'documentPreview','{!! $voucherInfo->id !!}')"> <i class="fa-solid fa-eye"></i></a>
                                        &nbsp;
                                        <a href="{!! route('view.archive.document',['vID'=>\Illuminate\Support\Facades\Crypt::encryptString($d->id),'ref'=> $voucherInfo->voucher_number]) !!}" title="View on new window" target="_blank"><i class="fa-solid fa-up-right-from-square"></i></a>
                                        &nbsp
                                        @if(auth()->user()->hasPermission('share_archive_data_individual'))
                                            <a href="" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}" onclick="return Obj.fileSharingModal(this)" title="Share Document"><i class="fas fa-share"></i></a>
                                        @endif
                                        &nbsp
                                        @if(auth()->user()->hasPermission('delete_archive_document_individual'))
                                            <form action="{{route('delete.archive.document.individual')}}" class="display-inline" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}">
                                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this?')" title="Delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-danger text-center">Not Found!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        @method('post')
                        {{--</form>--}}
                        <!-- Modal For Preview -->
                        <div class="modal modal-xl fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="v_document_name"></h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Reference No: <span id="v_no"></span></strong>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <strong>Data Type: <span id="v_type"></span></strong>
                                            </div>
                                        </div>
                                        <div id="documentPreview"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal-2 For Share -->
                        <div class="modal modal-xl fade" id="shareModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="shareModelLabel" aria-hidden="true">
                            <div class="modal-dialog" id="model_dialog">

                            </div>
                            <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
                                <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

