<div class="col-md-12 mt-1">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-plus"></i> Add More Item on Reference No.
                ({!! isset($item->reference)?$item->reference:'' !!})</h5>
        </div>
        <div class="card-body">
            @include('back-end.asset.transfer.edit.__edit_transfer_body_part_2')
            {{--                        @include('back-end.asset.__edit_fixed_asset_opening_body_list')--}}
            <div class="col-md-12">
                <div class="row">
                    @include('back-end.asset.transfer.edit.__list_table_only')
                </div>
            </div>
        </div>
    </div>
</div>
@include('back-end.asset.transfer.edit.__document_list')
