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
                    <h5 class="text-capitalize"><svg class="svg-inline--fa fa-table me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="table" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M64 256V160H224v96H64zm0 64H224v96H64V320zm224 96V320H448v96H288zM448 256H288V160H448v96zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"></path></svg> Fixed Asset Distribution</h5>
                </div>
            </div>
        </div>
        <div class="card-body" id="fixed-asset-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row mt-0">
                        <div class="col">
                            <span class="float-start"><strong>Company: </strong>{!! isset($company)?$company->company_name: '' !!}</span>
                        </div>
                        <div class="col text-center">
                            <span class=""><strong>Project: </strong>{!! isset($for_project_name)?$for_project_name: '' !!}</span>
                        </div>
                        <div class="col">
                            <span class="float-end"><strong>Reference: </strong>{!! isset($ref_type_name)?$ref_type_name:'' !!}-{!! isset($reference)?$reference: '' !!}</span>
                        </div>
                        <div class="col-md-12">
                            <hr class="text-secondary">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-1">
                        <label for="date">Date<span class="text-danger">*</span></label>
                        <input class="form-control" id="date" name="date" type="date" placeholder="date" value="" required/>
                    </div>
                </div>
                <input type="hidden" id="ref_hide" value="{!! isset($reference)?$reference:'' !!}">
                <input type="hidden" id="company_id_hide" value="{!! isset($company)?$company->id:'' !!}">
                <input type="hidden" id="project_id_hide" value="{!! isset($for_project_id)?$for_project_id:'' !!}">
                <input type="hidden" id="r_type_id_hide" value="{!! isset($ref_type_id)?$ref_type_id:'' !!}">
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
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body" id="load-view">
                            @include('back-end.asset._fixed_asset_opening_body_list')
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
            </div>
        </div>
    </div>
</div>
