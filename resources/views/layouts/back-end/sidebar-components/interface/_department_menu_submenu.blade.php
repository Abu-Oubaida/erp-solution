@if(auth()->user()->hasPermission('add_department') || auth()->user()->hasPermission('edit_department'))
    @if(Route::currentRouteName() == 'add.department' || Route::currentRouteName() == 'edit.department')
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#department" aria-expanded="true" aria-controls="department">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-building-user"></i></div>
            Department
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#department" aria-expanded="false" aria-controls="department">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-building-user"></i></div>
                    Department
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
        <div class="collapse" id="department" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @endif
            <nav class="sb-sidenav-menu-nested nav">
                @if(auth()->user()->hasPermission('add_department'))
                    @if(Route::currentRouteName() == 'add.department')
                        <a class="nav-link" href="{{route("add.department")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Add New</a>
                    @else
                        <a class="nav-link text-chl" href="{{route("add.department")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Add New</a>
                    @endif
                @endif
                @if(auth()->user()->hasPermission('edit_department'))
                    @if(Route::currentRouteName() == 'edit.department')
                        <a class="nav-link" href="{{route("edit.department",['departmentID'=>\Illuminate\Support\Facades\Request::route('departmentID')])}}"><div class="sb-nav-link-icon" title="Edit Department"><i class="fas fa-edit"></i></div> Edit Dept.</a>
                    @endif
                @endif
            </nav>
        </div>
@endif
