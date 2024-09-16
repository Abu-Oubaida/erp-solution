<script>
    $(document).ready(function() {
        $('select').selectize({
            // create: true,
            sortField: 'text'
        });
    })
</script>
<div class="col-md-7 mb-1">
    <div class="card mb-4">
        <div class="row card-body">
            <div class="col-md-7">
                <label for="project">Project Name<span class="text-danger">*</span></label>
                <select id="project" name="project" class="select-search cursor-pointer" multiple="multiple">
                    <option value="">Pick options...</option>
                    @if(count($projects))
                        @foreach($projects as $p)
                            <option value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                        @endforeach
                    @endif
                </select>
                <input type="hidden" id="user_id" name="user_id" value="@if(isset($user)){!! $user !!} @endif">
            </div>
            <div class="col-md-5 mt-4">
                <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.userProjectPermissionAdd(this)">
                    <i class="fa fa-plus"></i> Add
                </button>
                <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.userProjectPermissionAddAll(this)">
                    <i class="fa fa-plus"></i> Add All
                </button>
                <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.userProjectPermissionDeleteAll(this)">
                    <i class="fa fa-trash"></i> Delete All
                </button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-5 mb-1">
    <div class="card mb-4">
        <div class="row card-body">
            <div class="col-md-8">
                <label for="copy_user">Copy User Name<span class="text-danger">*</span></label>
                <select id="copy_user" name="copy_user" class="select-search cursor-pointer">
                    <option value="">Pick options...</option>
                    @if(count($permission_users))
                        @foreach($permission_users as $p_user)
                            <option value="{!! $p_user->user_id !!}">{!! $p_user->user->name !!} (ID: {!! $p_user->user->employee_id !!})</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4 mt-4">
                <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.userProjectPermissionCopy(this)">
                    <i class="fa fa-copy"></i> Copy Permission
                </button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="card mb-4" id="user-permission-list">
        @include('back-end.control-panel.__user-permission-list')
    </div>
</div>
