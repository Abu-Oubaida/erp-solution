<div class="card mb-4">
    <div class="card-header">
        <div class="row">
            <h3 class="text-capitalize"><i class="fas fa-leaf"></i>
                {{ str_replace('.', ' ', \Route::currentRouteName()) }}</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h6># Lead Profession Information</h6>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="profession" list="profession-list" name="profession"
                        placeholder="profession">
                    <datalist id="profession-list">
                        <option value="Business">Business</option>
                        <option value="Teacher">Teacher</option>
                        <option value="Solder">Solder</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Engineer">Engineer</option>
                        <option value="Bankar">Bankar</option>
                        <option value="Politician">Politician</option>
                        <option value="Actor">Actor</option>
                    </datalist>
                    <label for="branch">Main Profession <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="profession" list="profession-list" name="profession"
                        placeholder="profession">
                    <datalist id="profession-list">
                        <option value="Business">Business</option>
                        <option value="Teacher">Teacher</option>
                        <option value="Solder">Solder</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Engineer">Engineer</option>
                        <option value="Bankar">Bankar</option>
                        <option value="Politician">Politician</option>
                        <option value="Actor">Actor</option>
                    </datalist>
                    <label for="branch">Profession <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="company" name="company" placeholder="Company">
                    <label for="company">Company</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="designation" name="designation"
                            placeholder="Designation">
                        <label for="designation">Designation</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
