@if(auth()->user()->hasPermission('add_department'))
    @if(Route::currentRouteName() == 'add.department')
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#department" aria-expanded="true" aria-controls="department">
            <div class="sb-nav-link-icon"><i class="fas fa-table-list"></i></div>
            Department
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#department" aria-expanded="false" aria-controls="department">
                <div class="sb-nav-link-icon"><i class="fas fa-table-list"></i></div>
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
            </nav>
        </div>
@endif
