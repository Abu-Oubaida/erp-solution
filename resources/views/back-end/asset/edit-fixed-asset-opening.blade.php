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
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="text-capitalize"><i class="fas fa-edit"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{!! route('edit.fixed.asset.distribution.with.reference.balance',['faobid'=>\Illuminate\Support\Facades\Crypt::encryptString($item->id)]) !!}" enctype="multipart/form-data" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <label for="project">Enter Project Name<span class="text-danger">*</span></label>
                                    <select id="project" name="project_id" class="select-search cursor-pointer">
                                        <option value="">Pick a state...</option>
                                        @if(count($projects))
                                            @foreach($projects as $p)
                                                <option @if($item && $item->branch_id == $p->projects->id)selected @endif value="{!! $p->projects->id !!}">{!! $p->projects->branch_name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-3 mb-1">
                                    <label for="ref">Reference No<span class="text-danger">*</span></label>
                                    <input class="form-control" name="reference" value="{!! $item->references !!}" type="text" placeholder="Reference number..." id="ref-src">
                                </div>

                                <div class="col-md-3 mb-1">
                                    <label for="r_type">Reference Type<span class="text-danger">*</span></label>
                                    <select id="r_type" name="r_type" class="select-search cursor-pointer">
                                        <option value="">Pick a state...</option>
                                        @if(count($ref_types))
                                            @foreach($ref_types as $rt)
                                                <option @if($item && $item->ref_type_id == $rt->id)selected @endif value="{!! $rt->id !!}">{!! $rt->name !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="remarks">Attachments: ({!! count($item->attestedDocuments) !!})</label>
                                    <input class="form-control" type="file" name="attachment[]" id="attachment" multiple>
                                </div>
                                <div class="col-md-10">
                                    <div>
                                        <label for="remarks">Narration:</label>
                                        <textarea class="form-control form-control-sm"  id="narration" name="narration">{!! ($item->narration)?$item->narration:'' !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-chl-outline float-end mt-4"><i class="fas fa-save"></i> Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list"></i> Attested Documents</h5>
                    </div>
                    <div class="card-body">
                        <table @if(count($item->attestedDocuments))id="dataTableSmall" class="display table-sm" @else id="datatablesSimple" class="table table-sm" @endif>
                            <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($item->attestedDocuments))
                                @php($n=1)
                                @foreach($item->attestedDocuments as $d)
                                    <tr>
                                        <td>{!! $n++ !!}</td>
                                        <td>{!! $d->document_name !!}</td>
                                        <td class="text-center">
                                            <a href="{!! url($d->document_url.$d->document_name) !!}" class="" target="_blank"><i class="fas fa-eye"></i></a>
                                            <button class="text-danger border-0 inline-block bg-none" onclick="return Obj.deleteFixedAssetWithRefDocument(this)" ref="{!! $d->id !!}"><i class="fas fa-trash"></i></button>
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
            <div class="col-md-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-plus"></i> Add More Item on Reference No. ({!! isset($item->references)?$item->references:'' !!})</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-1">
                                            <label for="date">Date<span class="text-danger">*</span></label>
                                            <input class="form-control" id="date" name="date" type="date" placeholder="date" value="" required/>
                                        </div>
                                    </div>
                                    <input type="hidden" id="ref_hide" value="{!! isset($item->references)?$item->references:'' !!}">
                                    <input type="hidden" id="project_id_hide" value="{!! isset($item->branch_id)?$item->branch_id:'' !!}">
                                    <input type="hidden" id="r_type_id_hide" value="{!! isset($item->ref_type_id)?$item->ref_type_id:'' !!}">
                                    <div class="col-md-3">
                                        <div class="mb-1">
                                            <label for="recourse_code">Materials<span class="text-danger">*</span></label>
                                            <select class="select-search" id="materials_id" onchange="return Obj.getFixedAssetSpecification(this)">
                                                <option value="">Pick a state...</option>
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
                                            <select class="select-search" id="specification" >
                                                <option value="">Pick a state...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="mb-1">
                                            <label for="unite">Unite<span class="text-danger">*</span></label>
                                            <input class="form-control bg-secondary text-white" id="unite" type="text" placeholder="Unite" value="" required readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="mb-1">
                                            <label for="ref">Rate<span class="text-danger">*</span></label>
                                            <input class="form-control" id="rate" onfocusout="return Obj.priceTotal(this, 'qty','total')" type="text" placeholder="Rate" value="" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="mb-1">
                                            <label for="qty">Qty.<span class="text-danger">*</span></label>
                                            <input class="form-control" onfocusout="return Obj.priceTotal(this, 'rate','total')" id="qty" type="text" placeholder="Qty" value="" required/>
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
                                            <button class="btn btn-chl-outline btn-sm" type="button" onclick="return Obj.fixedAssetOpeningAddList(this)"> <i class="fas fa-plus"></i> Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                        @include('back-end.asset.__edit_fixed_asset_opening_body_list')--}}
                        <div class="col-md-12">
                            <div class="card-body" id="opening-materials-list">
                                <table @if(count($item->withSpecifications))id="simpleDataTable2" class="display" @else class="table" @endif style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Date</th>
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
                                    @if(isset($item->withSpecifications) && count($item->withSpecifications))
                                        @php($n=1)
                                        @foreach($item->withSpecifications as $opm)
                                            <tr>
                                                <td>{!! $n++ !!}</td>
                                                <td>{!! date('d-M-Y', strtotime($opm->date)) !!}</td>
                                                <td>{!! $opm->asset->materials_name !!} ({!! $opm->asset->recourse_code !!})</td>
                                                <td>{!! $opm->specification->specification !!}</td>
                                                <td>{!! $opm->asset->unit !!}</td>
                                                <td>{!! $opm->rate !!}</td>
                                                <td>{!! $opm->qty !!}</td>
                                                <td>{!! (float)($opm->qty * $opm->rate) !!}</td>
                                                <td>{!! (isset($opm->purpose))?$opm->purpose:'' !!}</td>
                                                <td>{!! (isset($opm->remarks))?$opm->remarks:'' !!}</td>
                                                <td>
                                                    <button class="text-success border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.fixedAssetOpeningSpecEdit(this)"><i class="fas fa-edit"></i></button>
                                                    <button class="text-danger border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.deleteFixedAssetOpeningSpec(this)"><i class="fas fa-trash"></i></button>
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
                            </div>
                        </div>
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

