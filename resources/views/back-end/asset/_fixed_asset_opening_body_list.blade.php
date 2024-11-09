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
