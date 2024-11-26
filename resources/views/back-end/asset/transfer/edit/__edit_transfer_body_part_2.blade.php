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
                        <button class="btn btn-chl-outline btn-sm" type="button" onclick="return Obj.fixedAssetGpAddList(this)"> <i class="fas fa-plus"></i> Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
