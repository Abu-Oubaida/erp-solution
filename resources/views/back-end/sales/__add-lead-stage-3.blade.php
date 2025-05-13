<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <input type="hidden" id="company_id" value="{{$lead->company_id}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <input type="hidden" id="lead_id" value="{{$lead->id}}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="main_source_id">Main Source<span class="text-danger">*</span></label>
            <select class="text-capitalize form-control" id="main_source_id">
                <option value="">Pick options...</option>
                @isset($source)
                    @foreach ($source->mainSource as $mainSource)
                        <option value="{{$mainSource->id}}" @if(($lead->id ?? 0) == $mainSource->id) selected @endif>{{$mainSource->title}}</option>
                    @endforeach
                @endisset
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="sub_source_id">Source<span class="text-danger">*</span></label>
            <select class="text-capitalize form-control" id="sub_source_id">
                <option value="">Pick options...</option>
                @isset($source)
                    @if($lead->id)
                        @foreach ($source->source as $source)
                            <option value="{{$source->id}}" @if(($lead->id ?? 0) == $source->id) selected @endif>{{$source->title}}</option>
                        @endforeach
                    @endif
                @endisset
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
            onclick="return backToStep2('{{$lead->id}}','{{$lead->company_id}}')"><i class="fa-solid fa-arrow-right"></i>
            Back
        </button>
        <button type="button" class="btn btn-chl-outline mt-3" onclick="return addLeadStep3()"><i
                class="fa-solid fa-arrow-right"></i>
            Next</button>

    </div>
</div>
<script>
    function backToStep2(lead_id,company_id){
        if(!lead_id){
            return false;
        }
        const url =
                    window.location.origin +
                    sourceDir +
                    "/back-lead-step2";
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
