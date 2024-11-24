@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('fixed.asset.show')}}" class="text-capitalize text-chl">Fixed Asset</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-capitalize"><i class="fas fa-edit"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h5>
                            </div>
                            <div class="col">
                                <a href="#documents" class="float-end btn-sm btn btn-outline-info"><i class="fas fa-file-edit"></i> Edit Documents ({!! count($item->documents) !!})</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{!! route('edit.fixed.asset.transfer',['ftid'=>\Illuminate\Support\Facades\Crypt::encryptString($item->id)]) !!}" enctype="multipart/form-data" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col">
                                    <label for="from_company">From Company<span class="text-danger">*</span></label>
                                    <select id="from_company" name="from_company" class="select-search cursor-pointer" onchange="return Obj.userWiseCompanyProjectPermissions(this,{!! Auth::user()->id !!},'from_project')">
                                        <option value="">Pick options...</option>
                                        @if(count(@$companies))
                                            @foreach($companies as $c)
                                                <option @if(isset($item) && $item->from_company_id == $c->id)selected @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="project">From Project Name<span class="text-danger">*</span></label>
                                    <select id="from_project" name="from_project" class="select-search cursor-pointer">
                                        <option value="">Pick options...</option>
                                        @if(@$projects !== null)
                                            @foreach($projects as $p)
                                                <option @if(isset($item) && $item->from_project_id == $p->id)selected @endif value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="company">To Company<span class="text-danger">*</span></label>
                                    <select id="to_company" name="to_company" class="select-search cursor-pointer" onchange="return Obj.userWiseCompanyProjectPermissions(this,{!! Auth::user()->id !!},'to_project')">
                                        <option value="">Pick options...</option>
                                        @if(count(@$companies))
                                            @foreach($companies as $c)
                                                <option @if(isset($item) && $item->to_company_id == $c->id)selected @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="project">To Project Name<span class="text-danger">*</span></label>
                                    <select id="to_project" name="to_project" class="select-search cursor-pointer">
                                        <option value="">Pick options...</option>
                                        @if(@$projects !== null)
                                            @foreach($projects as $p)
                                                <option @if(isset($item) && $item->to_project_id == $p->id)selected @endif value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="ref">GP Reference No<span class="text-danger">*</span></label>
                                    <input class="form-control" value="{!! @$item->reference !!}" type="text" placeholder="GP Reference number..." id="gp_ref">
                                </div>
                                <div class="col">
                                    <label for="gp_date">GP Date<span class="text-danger">*</span></label>
                                    <input class="form-control" value="{!! Request::get('d') !== null ?date('Y-m-d',strtotime(@$item->date)):'' !!}" name="gp_date" type="date"  id="gp_date">
                                </div>

                                <div class="col-md-7">
                                    <div>
                                        <label for="remarks">Narration:</label>
                                        <textarea class="form-control form-control-sm"  id="narration" name="narration">{!! (@$item->narration)?$item->narration:'' !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="remarks">Attachments: ({!! count(@$item->documents) !!})</label>
                                    <input class="form-control" type="file" name="attachment[]" id="attachment" multiple>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-chl-outline float-end mt-4"><i class="fas fa-save"></i> Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-1">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Add More Item on Reference No. ({!! isset($item->reference)?$item->reference:'' !!})</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="card-body">
                                    <div class="row mt-0">
                                        <div class="col">
                                            @isset($item->from_company_id)
                                                <span class="float-start"><strong>From Company: </strong><span class="badge bg-success">{!! $item->companyFrom->company_name !!}</span></span>
                                                <input type="hidden" id="from_company_id_hide" value="{!! $item->from_company_id !!}">
                                            @endisset
                                        </div>
                                        <div class="col text-center">
                                            @isset($item->from_project_id)
                                                <span class=""><strong>From Project: </strong><span class="badge bg-success">{!! $item->branchFrom->branch_name !!}</span></span>
                                                <input type="hidden" id="from_project_id_hide" value="{!! $item->from_project_id !!}">
                                            @endisset
                                        </div>
                                        <div class="col text-center">
                                            @isset($item->to_company_id)
                                                <span class=""><strong>To Company: </strong><span class="badge bg-info">{!! $item->companyTo->company_name !!}</span></span>
                                                <input type="hidden" id="to_company_id_hide" value="{!! $item->to_company_id !!}">
                                            @endisset
                                        </div>
                                        <div class="col text-center">
                                            @isset($item->to_project_id)
                                                <span><strong>To Project: </strong><span class="badge bg-info">{!! $item->branchTo->branch_name !!}</span></span>
                                                <input type="hidden" id="to_project_id_hide" value="{!! $item->to_project_id !!}">
                                            @endisset
                                        </div>
                                        <div class="col text-center">
                                            @isset($item->reference)
                                                <span><strong>Reference: </strong>GP-{!! $item->reference !!}</span>
                                                <input type="hidden" id="gp_ref_hide" value="{!! $item->reference !!}">
                                            @endisset
                                        </div>
                                        <div class="col">
                                            @isset($item->date)
                                                <span class="float-end"><strong>Date: </strong>{!! date('d-M-y',strtotime($item->date)) !!}</span>
                                                <input type="hidden" id="gp_date_hidden" value="{!! date('d-M-y',strtotime($item->date)) !!}">
                                            @endisset
                                        </div>
                                        <div class="col-md-12">
                                            <hr class="text-secondary">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-1">
                                                <label for="recourse_code">Materials<span class="text-danger">*</span></label>
                                                <select class="select-search" id="materials_id" onchange="Obj.gpMaterialsSpecificationSearch(this,'specification')">
                                                    <option value="">Pick options...</option>
                                                    @if(count($fixed_assets))
                                                        @foreach($fixed_assets as $fx)
                                                            <option value="{!! $fx->id !!}">{!! $fx->materials_name !!} ({{$fx->recourse_code}})</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-1">
                                                <label for="specification">Specification<span class="text-danger">*</span></label>
                                                <select class="select-search" id="specification" onchange="return Obj.gpMaterialsSpecificationWiseStockAndRateSearch(this,'rate')">
                                                    <option value="">Pick options...</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mb-1">
                                                <label for="rate">Rate<span class="text-danger">*</span></label>
                                                <input class="form-control" id="rate" onfocusout="return Obj.priceTotalForTransfer(this,'total','qty','stock','rate')" type="text" placeholder="Rate" value="" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mb-1">
                                                <label for="unite">Unite<span class="text-danger">*</span></label>
                                                <input class="form-control bg-secondary text-white" id="unite" type="text" placeholder="Unite" value="" required readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="mb-1">
                                                        <label for="stock">Stock Balance<span class="text-danger">*</span></label>
                                                        <input class="form-control bg-secondary text-white" id="stock" type="text" placeholder="Stock Balance" value="" required readonly/>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="mb-1">
                                                        <label for="qty">Qty.<span class="text-danger">*</span></label>
                                                        <input class="form-control" onfocusout="return Obj.priceTotalForTransfer(this,'total','qty','stock','rate')" id="qty" type="text" placeholder="Qty" value="" required/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mb-1">
                                                <label for="total">Total<span class="text-danger">*</span></label>
                                                <input class="form-control bg-secondary text-white" value="" id="total" type="text" placeholder="Total" required readonly step="100"/>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="">
                                                <label for="purpose">Purpose of use</label>
                                                <input class="form-control" value="" id="purpose" placeholder="Purpose use" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="">
                                                <label for="remarks">Remarks</label>
                                                <input class="form-control" value="" id="remarks" placeholder="Remarks" type="text">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-3 mt-4 float-end">
                                                <button class="btn btn-chl-outline btn-sm" type="button" onclick="return Obj.fixedAssetOpeningEditList(this)"> <i class="fas fa-plus"></i> Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                        @include('back-end.asset.__edit_fixed_asset_opening_body_list')--}}
                        <div class="col-md-12">
                            <div class="row">
                                <div class="card-body" id="opening-materials-list">
                                    <table @if(isset($item->specifications) && count($item->specifications)) id="simpleDataTableCustom" @endif class="table display">
                                        <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Entry Date</th>
                                            <th>Materials (Code)</th>
                                            <th>Specifications</th>
                                            <th>Unit</th>
                                            <th>Rate</th>
                                            <th>Qty.</th>
                                            <th>Total</th>
                                            <th>Purpose</th>
                                            <th>Remarks</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($item->specifications) && count($item->specifications))
                                            @php($n=1)
                                            @foreach($item->specifications as $tm)
                                                <tr>
                                                    <td>{!! $n++ !!}</td>
                                                    <td>{!! date('d-M-Y', strtotime($tm->created_at)) !!}</td>
                                                    <td>{!! $tm->asset->materials_name !!} ({!! $tm->asset->recourse_code !!})</td>
                                                    <td>{!! ($tm->spec_id == '0')?'None':$tm->specification->specification !!}</td>
                                                    <td>{!! $tm->asset->unit !!}</td>
                                                    <td>{!! $tm->rate !!}</td>
                                                    <td>{!! $tm->qty !!}</td>
                                                    <td>{!! (float)($tm->qty * $tm->rate) !!}</td>
                                                    <td>{!! (isset($tm->purpose))?$tm->purpose:'' !!}</td>
                                                    <td>{!! (isset($tm->remarks))?$tm->remarks:'' !!}</td>
                                                    <td>
                                                        <button class="text-success border-0 inline-block bg-none" ref="{!! $tm->id !!}" onclick="return Obj.fixedAssetTransferSpecEdit(this)"><i class="fas fa-edit"></i></button>
                                                        <button class="text-danger border-0 inline-block bg-none" ref="{!! $tm->id !!}" onclick="return Obj.deleteFixedAssetTransferSpec(this)"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center" colspan="12">Not Found!</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <script>
                                        (function ($){
                                            $(document).ready(function(){
                                                $('#simpleDataTableCustom').DataTable({
                                                    dom: 'lfrtip',
                                                    lengthMenu: [[5, 10, 15, 25, 50, 100, -1],[5, 10, 15, 25, 50, 100, "ALL"]],
                                                    pageLength: 15,
                                                })
                                            })
                                        }(jQuery))
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-1" id="documents">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Attested Documents</h5>
                    </div>
                    <div class="card-body">
                        <table @if(count($item->documents))id="dataTableSmall" class="display table-sm" @else id="datatablesSimple" class="table table-sm" @endif>
                            <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($item->documents))
                                @php($n=1)
                                @foreach($item->documents as $d)
                                    <tr>
                                        <td>{!! $n++ !!}</td>
                                        <td>{!! $d->document_name !!}</td>
                                        <td class="text-center">
                                            <a href="{!! url($d->document_url.$d->document_name) !!}" class="" target="_blank"><i class="fas fa-eye"></i></a>
                                            <button class="text-danger border-0 inline-block bg-none" onclick="return Obj.deleteFixedAssetTransferDocument(this)" ref="{!! $d->id !!}"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">Not Found!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-xl fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="v_document_name"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="fixed-asset-spec-edit">
                    {{--                <div class="row" >--}}
                    {{--                </div>--}}
                    {{--                <div id="documentPreview"></div>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{--                <button type="button" class="btn btn-primary">Understood</button>--}}
                </div>
                <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
                    <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
                </div>
            </div>
        </div>
    </div>
@stop

