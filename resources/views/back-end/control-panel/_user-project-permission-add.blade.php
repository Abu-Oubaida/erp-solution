<script>
    $(document).ready(function() {
        $('select').selectize({
            // create: true,
            sortField: 'text'
        });
    })
</script>
<div class="col-md-9 mb-1">
    <label for="project">Project Name<span class="text-danger">*</span></label>
    <select id="project" name="project" class="select-search cursor-pointer">
        <option value="">Pick a state...</option>
        @if(count($projects))
            @foreach($projects as $p)
                <option value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
            @endforeach
        @endif
    </select>
    <input type="hidden" id="user_id" name="user_id" value="@if(isset($user)){!! $user !!} @endif">
</div>
<div class="col-md-3 mt-4">
    <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.userProjectPermissionAdd(this)">
        <i class="fa fa-plus"></i> Add
    </button>
</div>

<div class="col-md-12">
    <div class="card mb-4" id="user-permission-list">
        @include('back-end.control-panel.__user-permission-list')
    </div>
</div>
