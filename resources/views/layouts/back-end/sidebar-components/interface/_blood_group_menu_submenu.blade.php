@if(auth()->user()->hasPermission('add_blood_group') || auth()->user()->hasPermission('list_blood_group') )
    @if(Route::currentRouteName() == 'add.blood.group' || Route::currentRouteName() == 'blood.group.list')
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#blood_group" aria-expanded="true" aria-controls="blood_group">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-droplet"></i></div>
            Blood Group
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#blood_group" aria-expanded="false" aria-controls="blood_group">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-droplet"></i></div>
                Blood Group
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
        <div class="collapse" id="blood_group" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @endif
            <nav class="sb-sidenav-menu-nested nav">
                @if(auth()->user()->hasPermission('add_blood_group'))
                    @if(Route::currentRouteName() == 'add.blood.group')
                        <a class="nav-link" href="{{route("add.blood.group")}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
                    @else
                        <a class="nav-link text-chl" href="{{route("add.blood.group")}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
                    @endif
                @endif
                    @if(auth()->user()->hasPermission('list_blood_group'))
                        @if(Route::currentRouteName() == 'blood.group.list')
                            <a class="nav-link" href="{{route("blood.group.list")}}"> <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> List</a>
                        @else
                            <a class="nav-link text-chl" href="{{route("blood.group.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> List</a>
                        @endif
                    @endif
            </nav>
        </div>
@endif
