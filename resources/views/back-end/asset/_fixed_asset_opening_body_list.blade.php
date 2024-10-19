<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            @if(isset($withRefData))
            <div class="row">
                <div class="col text-start"><strong>Company:</strong> {!! $withRefData->company->company_name !!}</div>
                <div class="col text-center"><strong>Project:</strong> {!! $withRefData->branch->branch_name !!}</div>
                <div class="col text-center"><strong>References:</strong> {!! $withRefData->references !!}</div>
                <div class="col text-end"><strong>Date:</strong> {!! date('d-F-Y',strtotime($withRefData->date)) !!}</div>
                <div class="col-md-12">
                    <hr class="text-secondary">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="opening-materials-list">
                    @include('back-end.asset.__edit_fixed_asset_opening_body_list')
                </div>
                @if(isset($withRefData->withSpecifications) && count($withRefData->withSpecifications))
                <form action="{!! route('fixed.asset.distribution.update') !!}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <label for="remarks">Narration:</label>
                                <textarea class="form-control form-control-sm"  id="narration" name="narration">{!! (old('narration')?old('narration'):'') !!}</textarea>
                                <input type="hidden" name="id" value="{!! $withRefData->id !!}">
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
                @endif
            </div>
            @endif
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
