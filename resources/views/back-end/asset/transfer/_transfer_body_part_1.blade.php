<script>
    $(document).ready(function() {
        $('select').selectize({
            // create: true,
            sortField: 'text'
        });
    })
</script>
<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h5 class="text-capitalize"><svg class="svg-inline--fa fa-table me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="table" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M64 256V160H224v96H64zm0 64H224v96H64V320zm224 96V320H448v96H288zM448 256H288V160H448v96zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"></path></svg> Fixed Asset Transfer</h5>
                </div>
            </div>
        </div>
        <div class="card-body" id="fixed-asset-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row mt-0">
                        <div class="col">
                        @isset($from_company)
                            <span class="float-start"><strong>From Company: </strong>{!! $from_company->company_name !!}</span>
                            <input type="hidden" id="from_company_id_hide" value="{!! $from_company->id !!}">
                        @endisset
                        </div>
                        <div class="col text-center">
                        @isset($from_project)
                            <span class=""><strong>From Project: </strong>{!! $from_project->branch_name !!}</span>
                            <input type="hidden" id="from_project_id_hide" value="{!! $from_project->id !!}">
                        @endisset
                        </div>
                        <div class="col">
                            @isset($to_company)
                                <span class="float-start"><strong>To Company: </strong>{!! $to_company->company_name !!}</span>
                                <input type="hidden" id="from_company_id_hide" value="{!! $to_company->id !!}">
                            @endisset
                        </div>
                        <div class="col text-center">
                            @isset($to_project)
                                <span class=""><strong>To Project: </strong>{!! $to_project->branch_name !!}</span>
                                <input type="hidden" id="from_project_id_hide" value="{!! $to_project->id !!}">
                            @endisset
                        </div>
                        <div class="col">
                            @isset($gp_reference)
                            <span class="float-end"><strong>Reference: </strong>GP-{!! $gp_reference !!}</span>
                            <input type="hidden" id="gp_ref_hide" value="{!! $gp_reference !!}">
                            @endisset
                        </div>
                        <div class="col">
                            @isset($gp_date)
                            <span class="float-end"><strong>Date: </strong>{!! date('d-M-y',strtotime($gp_date)) !!}</span>
                            <input type="hidden" id="gp_date_hidden" value="{!! date('d-M-y',strtotime($gp_date)) !!}">
                            @endisset
                        </div>
                        <div class="col-md-12">
                            <hr class="text-secondary">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-1">
                        <label for="recourse_code">Materials<span class="text-danger">*</span></label>
                        <select class="select-search" id="materials_id" onchange="return Obj.getFixedAssetSpecification(this)">
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
                        <select class="select-search" id="specification" >
                            <option value="">Pick options...</option>
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
            <hr>
            <div>
                @include('back-end.asset._fixed_asset_opening_body_list')
            </div>
        </div>
    </div>
</div>
