<div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="v_document_title">Add New Document</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <form action="{!! route('store.voucher.document.individual') !!}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12">
                    <strong>Reference No: {!! $voucherInfo->voucher_number !!}</strong>
                    <div class="form-floating mb-3">
                        <input type="file" class="form-control" name="voucher_file[]" multiple id="voucher_file" >
                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($voucherInfo->id) !!}">
                        <label for="voucher_file">Voucher Document<span class="text-danger">*</span></label>
                        <small>jpeg,png,pdf/ Maximum size 500 MB</small>
                    </div>
                    <div class="form-floating mb-3 float-end">
                        <button type="submit" name="submit" class="btn btn-chl-outline"> <i class="fas fa-upload"></i> Upload</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
    </div>
</div>

