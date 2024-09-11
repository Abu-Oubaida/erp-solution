@if(auth()->user()->hasPermission('add_designation') || auth()->user()->hasPermission('list_designation'))
    @if(Route::currentRouteName() == 'add.designation' || Route::currentRouteName() == 'designation.list')
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#designation" aria-expanded="true" aria-controls="designation">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-user-tag"></i></div>
            Designation
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#designation" aria-expanded="false" aria-controls="designation">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-user-tag"></i></div>
                    Designation
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
        <div class="collapse" id="designation" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @endif
            <nav class="sb-sidenav-menu-nested nav">
                @if(auth()->user()->hasPermission('add_designation'))
                    @if(Route::currentRouteName() == 'add.designation')
                        <a class="nav-link" href="{{route("add.designation")}}"><div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div> Add New</a>
                    @else
                        <a class="nav-link text-chl" href="{{route("add.designation")}}"><div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div> Add New</a>
                    @endif
                @endif
                @if(auth()->user()->hasPermission('list_designation'))
                    @if(Route::currentRouteName() == 'designation.list')
                        <a class="nav-link" href="{{route("designation.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> List Show</a>
                    @else
                        <a class="nav-link text-chl" href="{{route("designation.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> List Show</a>
                    @endif
                @endif
            </nav>
        </div>
@endif
