<script>
    $(document).ready(function() {
        $('select').selectize({
            // create: true,
            sortField: 'text'
        });
    })
</script>
<div class="col-md-12">
    <div class="card mb-1 mt-1">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h5 class="text-capitalize mb-0"><i class="fa-solid fa-arrow-right-arrow-left"></i> Fixed Asset Transfer</h5>
                </div>
            </div>
        </div>
        <div class="card-body" id="fixed-asset-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row mt-0">
                        <div class="col">
                        @isset($from_company)
                                <span class="float-start"><strong>From Company: </strong><span class="badge bg-success">{!! $from_company->company_name !!}</span></span>
                            <input type="hidden" id="from_company_id_hide" value="{!! $from_company->id !!}">
                        @endisset
                        </div>
                        <div class="col text-center">
                        @isset($from_project)
                                <span class=""><strong>From Project: </strong><span class="badge bg-success">{!! $from_project->branch_name !!}</span></span>
                            <input type="hidden" id="from_project_id_hide" value="{!! $from_project->id !!}">
                        @endisset
                        </div>
                        <div class="col text-center">
                            @isset($to_company)
                                <span class=""><strong>To Company: </strong><span class="badge bg-info">{!! $to_company->company_name !!}</span></span>
                                <input type="hidden" id="to_company_id_hide" value="{!! $to_company->id !!}">
                            @endisset
                        </div>
                        <div class="col text-center">
                            @isset($to_project)
                                <span><strong>To Project: </strong><span class="badge bg-info">{!! $to_project->branch_name !!}</span></span>
                                <input type="hidden" id="to_project_id_hide" value="{!! $to_project->id !!}">
                            @endisset
                        </div>
                        <div class="col text-center">
                            @isset($gp_reference)
                            <span><strong>Reference: </strong>GP-{!! $gp_reference !!}</span>
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
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <h5 class="m-0"><i class="fas fa-list"></i> Added item List</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12" id="materials-list">
                    @include('back-end.asset.transfer.__list_table_only')
                </div>
                <div class="modal modal-xl fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title fs-5" id="v_document_name"><i class="fas fa-edit"></i> Edit Data</h3>
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
                {{--                    @if(isset($withRefData->withSpecifications) && count($withRefData->withSpecifications))--}}
                <form action="{!! route('fixed.asset.distribution.update') !!}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <label for="remarks">Narration:</label>
                                <textarea class="form-control form-control-sm"  id="narration" name="narration">{!! (old('narration')?old('narration'):'') !!}</textarea>
                                {{--                                        <input type="hidden" name="id" value="{!! //$withRefData->id !!}">--}}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="remarks">Attachments:</label>
                            <input class="form-control" type="file" name="attachment[]" id="attachment" multiple>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-lg btn-outline-success float-end mt-4" type="submit"><i class="fas fa-save"></i> Final Update</button>
                        </div>
                    </div>
                </form>
                {{--                    @endif--}}
            </div>
        </div>
    </div>
</div>
