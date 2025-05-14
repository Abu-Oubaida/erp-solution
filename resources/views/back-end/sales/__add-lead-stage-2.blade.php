<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <input type="hidden" id="company_id" value="{!! @$lead->company_id !!}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <input type="hidden" id="lead_id" value="{!! @$lead->id !!}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="main_profession">Main Profession<span class="text-danger">*</span></label>
            <select class="text-capitalize form-control" id="lead_main_profession_id" onchange="return mainProfessionWiseSubProfession(this)">
                <option value="">Pick options...</option>
                @isset($profession)
                    @foreach ($profession->mainProfessions as $main_profession)
                        <option value="{{$main_profession->id}}" @if(($lead->lead_main_profession_id ?? 0) == $main_profession->id) selected @endif>{{$main_profession->title}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <input type="hidden" id="lead_sub_profession_id_hidden" value="{{$lead->lead_sub_profession_id}}">
            <label for="profession">Profession<span class="text-danger">*</span></label>
            <select class="text-capitalize form-control" id="lead_sub_profession_id">
                <option value="">Pick options...</option>
                @isset($profession)
                    @if($lead->id)
                        @foreach ($profession->professions as $profession)
                            <option value="{{$profession->id}}" disabled @if( ($lead->lead_sub_profession_id ?? 0) == $profession->id) selected @endif>{{$profession->title}}</option>
                        @endforeach
                    @endif
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <label for="company">Company Of Lead</label>
        <div class="mb-3">
            <input type="text" class="form-control" id="lead_company" name="company" placeholder="Company Of Lead" value="{{$lead->lead_company??null}}">
        </div>
    </div>
    <div class="col-md-3">
        <label for="designation">Designation Of Lead</label>
        <div class="mb-5">
            <div class="mb-3">
                <input type="text" class="form-control" id="lead_designation" name="designation"
                    placeholder="Designation Of Lead" value="{{$lead->lead_designation??null}}">
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-chl-outline mt-3" onclick="return backLeadStep1({!! @$lead->id !!})"><i
                class="fa-solid fa-arrow-right"></i>
            Back
        </button>
        <button type="button" class="btn btn-chl-outline mt-3" onclick="return addLeadStep2()"><i
                class="fa-solid fa-arrow-right"></i>
            Next</button>
    </div>
</div>
<script>
    function mainProfessionWiseSubProfession(e){
        let val = $(e).val();
        if(val.length === 0){
            return false;
        }
        const url =
                    window.location.origin +
                    sourceDir +
                    "/get-sales-profession";
        $.ajax({
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
            url:url,
            method:"POST",
            data:{lead_profession_parent_id:val},
            success:function(response){
                if(response.status==='error'){
                    alert(response.message);
                }else{
                    $("#lead_sub_profession_id").empty()
                    $("#lead_sub_profession_id").append(`<option value="">Select One...</option>`)
                    $.each(response.profession,function(index,item){
                        $("#lead_sub_profession_id").append(`<option value="${item.id}">${item.title}</option>`)
                    })
                }
            }
        })
    }
    function backLeadStep1(lead_id){
        if(!lead_id){
            return false;
        }
        const url =
                    window.location.origin +
                    sourceDir +
                    "/back-lead-step1";
        $.ajax({
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
            url:url,
            method:"POST",
            data:{lead_id:lead_id},
            success:function(response){
                if(response.status==='error'){
                    alert(response.message);
                }else{
                     $("#commonSlot_for_multiple_step").html(response.data.view);
                }
            }
        })
    }
    function addLeadStep2() // company_group
    {
        let add_lead_step2_data = {
            lead_main_profession_id:$("#lead_main_profession_id").val(),
            lead_sub_profession_id:$("#lead_sub_profession_id").val() || $("#lead_sub_profession_id_hidden").val(),
            lead_company:$("#lead_company").val(),
            lead_designation:$("#lead_designation").val(),
        };
        let hidden_company_lead = {
            company_id:$("#company_id").val(),
            lead_id:$("#lead_id").val(),
        };
        console.log(add_lead_step2_data);
        const url =
            window.location.origin + sourceDir + "/add-lead-step2";

        $.ajax({
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            method: "POST",
            data: { add_lead_step2_data, hidden_company_lead },
            success: function (response) {
                if (response.status === "error") {
                    alert("Error: " + response.message);
                } else if (response.status === "success") {
                    $("#commonSlot_for_multiple_step").html(response.data.view);
                }
            },
        });

        return false;
    }
</script>