<div class="card mb-4">
    <div class="card-header">
        <div class="row">
            <h3 class="text-capitalize"><i class="fas fa-leaf"></i> Add Lead Basic Information</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" type="text" placeholder="Enter full name" />
                            <label for="name">Full name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="relation" type="text"
                                placeholder="Enter husband/wife name" />
                            <label for="relation">Husband/Wife </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="primary_mobile" type="number" placeholder="Primary Mobile"
                                value="" />
                            <label for="primary_mobile">Primary Mobile <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input class="form-control" id="phone2" type="number" placeholder="Enter phone 1" />
                            <label for="phone2">Alternative Mobile 1</label>
                        </div>
                    </div>
                    <div class="col-12 mt-1">
                        <a href="#" class="float-end text-decoration-none"
                            onclick="Sales.addEmailPhoneForLead(this,'mobile','add_extra_info')"><i
                                class="fas fa-plus small"></i> Add
                            Another Mobile</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="primary_email" type="email" placeholder="Primary Email"
                                value="" />
                            <label for="primary_email">Primary Email <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input class="form-control" id="alternative_email_1" type="email"
                                placeholder="Enter Email 2" />
                            <label for="alternative_email_1">Alternative Email 1</label>
                        </div>
                    </div>

                    <div class="col-12 mt-1">
                        <a href="#" class="float-end text-decoration-none"
                            onclick="Sales.addEmailPhoneForLead(this,'email','add_extra_info')"><i
                                class="fas fa-plus small"></i> Add
                            Another
                            Email</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <div class="form-floating mb-3">
                        <textarea type="text" class="form-control" id="notes" name="notes" placeholder="Notes"></textarea>
                        <label for="Designation">Notes</label>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row" id="add_extra_info">

                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-chl-outline float-end mt-3"><i class="fa-solid fa-arrow-right"></i>
                            Next</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
