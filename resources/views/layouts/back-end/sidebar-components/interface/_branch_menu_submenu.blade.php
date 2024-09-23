@if(auth()->user()->hasPermission('add_branch') || auth()->user()->hasPermission('edit_branch') || auth()->user()->hasPermission('list_branch') || auth()->user()->hasPermission('list_branch_type') || auth()->user()->hasPermission('add_branch_type') || auth()->user()->hasPermission('edit_branch_type'))
    @if(Route::currentRouteName() == 'add.branch' || Route::currentRouteName() == 'edit.branch' || Route::currentRouteName() == 'branch.list' || Route::currentRouteName() == 'branch.type.list' || Route::currentRouteName() == 'add.branch.type' || Route::currentRouteName() == 'edit.branch.type')
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#branch" aria-expanded="true" aria-controls="branch">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-code-branch"></i></div>
            Branch
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="branch" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @else
        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#branch" aria-expanded="false" aria-controls="branch">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-code-branch"></i></div>
            Branch
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
    <div class="collapse" id="branch" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @endif
            <nav class="sb-sidenav-menu-nested nav">
                @if(auth()->user()->hasPermission('add_branch_type'))
                    @if(Route::currentRouteName() == 'add.branch.type')
                        <a class="nav-link" href="{{route("add.branch.type")}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-plus"></i></div> Add Type</a>
                    @else
                        <a class="nav-link text-chl" href="{{route("add.branch.type")}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-plus"></i></div> Add Type</a>
                    @endif
                @endif
                @if(auth()->user()->hasPermission('add_branch'))
                    @if(Route::currentRouteName() == 'add.branch')
                        <a class="nav-link" href="{{route("add.branch")}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-plus"></i></div> Add Branch</a>
                    @else
                        <a class="nav-link text-chl" href="{{route("add.branch")}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-plus"></i></div> Add Branch</a>
                    @endif
                @endif
                @if(auth()->user()->hasPermission('list_branch'))
                    @if(Route::currentRouteName() == 'branch.list')
                        <a class="nav-link" href="{{route("branch.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> List</a>
                    @else
                        <a class="nav-link text-chl" href="{{route("branch.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> List</a>
                    @endif
                @endif
                @if(auth()->user()->hasPermission('edit_branch'))
                    @if(Route::currentRouteName() == 'edit.branch')
                        <a class="nav-link" href="{{route("edit.branch",['branchID'=>\Illuminate\Support\Facades\Request::route('branchID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> Edit Branch</a>
                    @endif
                @endif
                @if(auth()->user()->hasPermission('edit_branch_type'))
                    @if(Route::currentRouteName() == 'edit.branch.type')
                        <a class="nav-link" href="{{route("edit.branch.type",['branchTypeID'=>\Illuminate\Support\Facades\Request::route('branchTypeID')])}}" title="Edit Branch Type"><div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> Edit B. Type</a>
                    @endif
                @endif
            </nav>
        </div>
@endif
