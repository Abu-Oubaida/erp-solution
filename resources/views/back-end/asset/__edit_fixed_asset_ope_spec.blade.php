@if($data)
    <div class="row">
        <div class="col-md-2">
            <label for="edit-date">Date<span class="text-danger">*</span></label>
            <input type="date" value="{!! $data->date !!}" id="edit-date" class="form-control">
            <input type="hidden" id="edit-id" value="{!! $data->id !!}">
        </div>
        <div class="col-md-2">
            <label for="edit-materials">Materials (Code)<span class="text-danger">*</span></label>
            <input type="text" value="{!! $data->asset->materials_name !!} ({!! $data->asset->recourse_code !!})" id="edit-materials" class="form-control" disabled readonly>
        </div>
        <div class="col-md-2">
            <label for="edit-specification">Specification<span class="text-danger">*</span></label>
            <input type="text" value="{!! isset($data->specification->specification)?$data->specification->specification:'None' !!}" id="edit-specification" class="form-control" disabled readonly>
        </div>
        <div class="col-md-1">
                <label for="edit-unit">Unite<span class="text-danger">*</span></label>
                <input class="form-control" id="edit-unit" type="text" value="{!! $data->asset->unit !!}" required readonly disabled/>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-4">
                    <label for="rate-edit">Rate<span class="text-danger">*</span></label>
                    <input class="form-control" id="rate-edit" onfocusout="return Obj.priceTotal(this, 'qty-edit','total-edit')" type="text" placeholder="Rate" value="{!! $data->rate !!}" required/>
                </div>
                <div class="col-md-4">
                    <label for="qty-edit">Qty.<span class="text-danger">*</span></label>
                    <input class="form-control" onfocusout="return Obj.priceTotal(this, 'rate-edit','total-edit')" id="qty-edit" type="text" placeholder="Qty" value="{!! $data->qty !!}" required/>
                </div>
                <div class="col-md-4">
                    <label for="total-edit">Total<span class="text-danger">*</span></label>
                    <input class="form-control" value="{!! ($data->rate * $data->qty) !!}" id="total-edit" type="text" placeholder="Total" required readonly step="100" disabled/>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="">
                <label for="edit-purpose">Purpose of use</label>
                <input class="form-control" value="{!! (isset($data->purpose)?$data->purpose:'') !!}" id="edit-purpose" placeholder="Purpose use" type="text">
            </div>
        </div>
        <div class="col-md-3">
            <div class="">
                <label for="edit-remarks">Remarks</label>
                <input class="form-control" value="{!! (isset($data->remarks)?$data->remarks:'') !!}" id="edit-remarks" placeholder="Remarks" type="text">
            </div>
        </div>
        <div class="col">
            <div class="mb-3 mt-4 float-end">
                <button class="btn btn-chl-outline btn-sm" type="button" onclick="return Obj.updateFixedAssetOpeningSpec(this)"> <i class="fas fa-save"></i> Update</button>
            </div>
        </div>
    </div>
@else
    <h3 class="text-center text-capitalize">Data not found!</h3>
@endif

