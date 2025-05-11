<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <input type="hidden" id="company_id" value="${company_id}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <input type="hidden" id="lead_id" value="${lead_id}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="main_source_id">Main Source<span class="text-danger">*</span></label>
            <select class="text-capitalize form-control" id="main_source_id">
                <option value="">Pick options...</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="sub_source_id">Source<span class="text-danger">*</span></label>
            <select class="text-capitalize form-control" id="sub_source_id">
                <option value="">Pick options...</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="reference_name" placeholder="Company Of Lead">
            <label for="reference_name">Reference Name</label>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-chl-outline mt-3"
            onclick="return Sales.getLeadStep2Form(${company_id},${lead_id})"><i class="fa-solid fa-arrow-right"></i>
            Back
        </button>
        <button type="button" class="btn btn-chl-outline mt-3" onclick="return Sales.addLeadStep3()"><i
                class="fa-solid fa-arrow-right"></i>
            Next</button>

    </div>
</div>
