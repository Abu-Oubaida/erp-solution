<div class="row">
    <div class="col-md-6 mb-3">
        <button type="button" class="btn btn-chl-outline mt-3" onclick="Sales.addNewLeadForm()"><i
                class="fa-solid fa-arrow-right"></i>
            Add New Lead</button>
    </div>
    <div class="col-md-6 mb-3">
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <input type="hidden" id="company_id" value="{{ $lead->company_id }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <input type="hidden" id="lead_id" value="{{ $lead->id }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="preference_note" placeholder="Preference Note" value="{{$lead->preference_note??null}}">
            <label for="preference_note">Preference Note</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="apartment_type_id">Apartment Type</label>
            <select class="text-capitalize form-control" id="apartment_type_id">
                <option value="">Pick options...</option>
                @isset($preference)
                    @foreach ($preference->salesLeadApartmentType as $apartment_type)
                        <option value="{{ $apartment_type->id }}" @if (($lead->apartment_type_id ?? 0) == $apartment_type->id) selected @endif>
                            {{ $apartment_type->title }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="apartment_size_id">Apartment Size</label>
            <select class="text-capitalize form-control" id="apartment_size_id">
                <option value="">Pick options...</option>
                @isset($preference)
                    @foreach ($preference->salesLeadApartmentSize as $apartment_size)
                        <option value="{{ $apartment_size->id }}" @if (($lead->apartment_size_id ?? 0) == $apartment_size->id) selected @endif>
                            {{ $apartment_size->title }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="floor_id">Floor</label>
            <select class="text-capitalize form-control" id="floor_id">
                <option value="">Pick options...</option>
                @isset($preference)
                    @foreach ($preference->salesLeadFloor as $apartment_floor)
                        <option value="{{ $apartment_floor->id }}" @if (($lead->floor_id ?? 0) == $apartment_floor->id) selected @endif>
                            {{ $apartment_floor->title }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="facing_id">Facing</label>
            <select class="text-capitalize form-control" id="facing_id">
                <option value="">Pick options...</option>
                @isset($preference)
                    @foreach ($preference->salesLeadFacing as $apartment_facing)
                        <option value="{{ $apartment_facing->id }}" @if (($lead->facing_id ?? 0) == $apartment_facing->id) selected @endif>
                            {{ $apartment_facing->title }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="view_id">View</label>
            <select class="text-capitalize form-control" id="view_id">
                <option value="">Pick options...</option>
                @isset($preference)
                    @foreach ($preference->salesLeadView as $apartment_view)
                        <option value="{{ $apartment_view->id }}" @if (($lead->view_id ?? 0) == $apartment_view->id) selected @endif>
                            {{ $apartment_view->title }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="budget_id">Budget</label>
            <select class="text-capitalize form-control" id="budget_id">
                <option value="">Pick options...</option>
                @isset($preference)
                    @foreach ($preference->salesLeadBudget as $apartment_budget)
                        <option value="{{ $apartment_budget->id }}" @if (($lead->budget_id ?? 0) == $apartment_budget->id) selected @endif>
                            {{ $apartment_budget->title }}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-2 mt-2">
        <button type="button" class="btn btn-chl-outline mt-3"
            onclick="return backLeadStep3({!! @$lead->id !!},{!! @$lead->company_id !!})"><i class="fa-solid fa-arrow-right"></i>
            Back
        </button>
        <button type="button" class="btn btn-chl-outline mt-3" onclick="return addLeadStep4()"><i
                class="fa-solid fa-arrow-right"></i>
            Next</button>
    </div>
</div>
<script>
    function addLeadStep4() 
    {
        let add_lead_step4_data = {
            preference_note: $("#preference_note").val(),
            apartment_type_id: $("#apartment_type_id").val(),
            apartment_size_id: $("#apartment_size_id").val(),
            company_id: $("#company_id").val(),
            lead_id: $("#lead_id").val(),
            floor_id: $("#floor_id").val(),
            facing_id: $("#facing_id").val(),
            view_id: $("#view_id").val(),
            budget_id: $("#budget_id").val()
        };
        const url =
            window.location.origin + sourceDir + "/add-lead-step4";

        $.ajax({
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            method: "POST",
            data: {
                add_lead_step4_data
            },
            success: function(response) {
                if (response.status === "error") {
                    alert("Error: " + response.message);
                } else if (response.status === "success") {
                    alert(response.message);
                }
            },
        });

        return false;
    }
    function backLeadStep3(lead_id,company_id){
        if(!lead_id){
            return false;
        }
        const url =
                    window.location.origin +
                    sourceDir +
                    "/back-lead-step3";
        $.ajax({
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
            url:url,
            method:"POST",
            data:{lead_id:lead_id,company_id:company_id},
            success:function(response){
                if(response.status==='error'){
                    alert(response.message);
                }else{
                     $("#commonSlot_for_multiple_step").html(response.data.view);
                }
            }
        })
    }
</script>
